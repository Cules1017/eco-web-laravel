<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AiAssistantController extends Controller
{
    public function consult(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'history' => ['nullable', 'array', 'max:60'],
            'history.*.role' => ['nullable', 'string', 'in:user,bot'],
            'history.*.text' => ['nullable', 'string', 'max:2000'],
            'page_context' => ['nullable', 'array'],
            'page_context.title' => ['nullable', 'string', 'max:255'],
            'page_context.url' => ['nullable', 'string', 'max:500'],
            'page_context.product_name' => ['nullable', 'string', 'max:255'],
            'page_context.product_price' => ['nullable', 'string', 'max:100'],
            'page_context.product_description' => ['nullable', 'string', 'max:2000'],
        ]);

        $apiKey = config('services.gemini.api_key');
        if (!$apiKey) {
            Log::channel('ai_assistant')->warning('GEMINI_API_KEY missing', [
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Chưa cấu hình GEMINI_API_KEY trong file .env',
            ], 500);
        }

        $siteName = Setting::getValue('site_name', config('app.name'));
        $contactPhone = Setting::getValue('contact_phone');
        $contactEmail = Setting::getValue('contact_email');
        $context = $validated['page_context'] ?? [];
        $history = $validated['history'] ?? [];

        $relatedProducts = $this->findRelatedProducts($validated['message'], $context);
        $featuredProducts = $this->getFeaturedProducts($relatedProducts->pluck('id')->all());
        $categories = $this->getCategorySummary();

        $systemInstruction = $this->buildSystemInstruction(
            $siteName,
            $contactPhone,
            $contactEmail,
            $categories,
            $relatedProducts,
            $featuredProducts,
            $context
        );

        $contents = $this->buildContents($history, $validated['message']);

        try {
            $responsePack = $this->callGeminiWithFallback(
                $apiKey,
                $systemInstruction,
                $contents,
                $validated['message']
            );

            if (!$responsePack['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể kết nối AI lúc này, vui lòng thử lại sau.',
                ], 502);
            }

            $json = $responsePack['json'];
            $parts = data_get($json, 'candidates.0.content.parts', []);
            $answer = collect($parts)
                ->pluck('text')
                ->filter()
                ->implode("\n");

            $finishReason = data_get($json, 'candidates.0.finishReason');
            if ($finishReason === 'MAX_TOKENS') {
                $answer = rtrim($answer) . "\n\n(...)\n\nBạn muốn mình nói tiếp phần còn lại không ạ?";
            }

            if (trim($answer) === '') {
                $blockReason = data_get($json, 'promptFeedback.blockReason');
                Log::channel('ai_assistant')->warning('Gemini returned empty answer', [
                    'model' => $responsePack['model'],
                    'finish_reason' => $finishReason,
                    'block_reason' => $blockReason,
                    'prompt_feedback' => data_get($json, 'promptFeedback'),
                    'candidates_preview' => Str::limit(json_encode(data_get($json, 'candidates'), JSON_UNESCAPED_UNICODE), 1500),
                    'user_message_preview' => Str::limit($validated['message'], 300),
                ]);
                if ($blockReason) {
                    $answer = 'Xin lỗi, nội dung câu hỏi bị hệ thống lọc. Bạn vui lòng thử lại với cách diễn đạt khác nhé!';
                } else {
                    $answer = 'Xin lỗi, mình chưa thể tư vấn lúc này. Vui lòng thử lại sau ít phút nhé!';
                }
            }

            return response()->json([
                'success' => true,
                'reply' => $answer,
                'suggested_products' => $relatedProducts->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => (float) $product->price,
                        'url' => route('products.show', $product->slug),
                    ];
                })->values(),
            ]);
        } catch (\Throwable $exception) {
            report($exception);
            Log::channel('ai_assistant')->error('AiAssistant consult exception', [
                'exception' => get_class($exception),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'user_message_preview' => Str::limit($validated['message'] ?? '', 300),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Hệ thống AI đang bận. Vui lòng thử lại sau.',
            ], 500);
        }
    }

    private function callGeminiWithFallback(string $apiKey, string $systemInstruction, array $contents, string $userMessage): array
    {
        $models = $this->resolveGeminiModels();
        $lastStatus = 502;

        foreach ($models as $index => $model) {
            try {
                $response = Http::timeout(25)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                        'X-goog-api-key' => $apiKey,
                    ])
                    ->post(
                        "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent",
                        [
                            'system_instruction' => [
                                'parts' => [
                                    ['text' => $systemInstruction],
                                ],
                            ],
                            'contents' => $contents,
                            'generationConfig' => [
                                'temperature' => 0.7,
                                'topP' => 0.95,
                                'maxOutputTokens' => 1500,
                            ],
                        ]
                    );

                if ($response->successful()) {
                    if ($index > 0) {
                        Log::channel('ai_assistant')->info('Gemini fallback model selected', [
                            'model' => $model,
                            'attempt' => $index + 1,
                            'models' => $models,
                        ]);
                    }

                    return [
                        'success' => true,
                        'model' => $model,
                        'json' => $response->json(),
                    ];
                }

                $lastStatus = $response->status();
                $errorJson = $response->json();
                Log::channel('ai_assistant')->error('Gemini API HTTP error', [
                    'model' => $model,
                    'attempt' => $index + 1,
                    'http_status' => $lastStatus,
                    'gemini_error' => data_get($errorJson, 'error'),
                    'body_preview' => Str::limit($response->body(), 2000),
                    'user_message_preview' => Str::limit($userMessage, 300),
                ]);

                if (!$this->shouldTryNextModel($lastStatus)) {
                    break;
                }
            } catch (\Throwable $exception) {
                Log::channel('ai_assistant')->error('Gemini request exception on model', [
                    'model' => $model,
                    'attempt' => $index + 1,
                    'exception' => get_class($exception),
                    'message' => $exception->getMessage(),
                    'user_message_preview' => Str::limit($userMessage, 300),
                ]);

                if (!$this->shouldTryNextModel(503)) {
                    break;
                }
            }
        }

        return [
            'success' => false,
            'status' => $lastStatus,
        ];
    }

    private function resolveGeminiModels(): array
    {
        $models = config('services.gemini.models', []);
        if (!is_array($models) || empty($models)) {
            return ['gemini-flash-latest'];
        }

        return array_values(array_unique(array_filter(array_map('trim', $models))));
    }

    private function shouldTryNextModel(int $status): bool
    {
        return in_array($status, [408, 409, 425, 429, 500, 502, 503, 504], true);
    }

    private function findRelatedProducts(string $message, array $context): Collection
    {
        $keywords = $this->extractKeywords($message . ' ' . ($context['product_name'] ?? ''));

        $query = Product::query()
            ->where('is_active', true)
            ->with('category:id,name,slug');

        if (!empty($keywords)) {
            $query->where(function ($sub) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $sub->orWhere('name', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%")
                        ->orWhereHas('category', function ($q) use ($keyword) {
                            $q->where('name', 'like', "%{$keyword}%");
                        });
                }
            });
        }

        return $query
            ->orderByDesc('is_featured')
            ->orderByDesc('stock')
            ->limit(8)
            ->get(['id', 'name', 'slug', 'description', 'price', 'stock', 'category_id', 'is_featured']);
    }

    private function getFeaturedProducts(array $excludeIds = []): Collection
    {
        return Product::query()
            ->where('is_active', true)
            ->where('is_featured', true)
            ->when(!empty($excludeIds), function ($q) use ($excludeIds) {
                $q->whereNotIn('id', $excludeIds);
            })
            ->with('category:id,name,slug')
            ->limit(5)
            ->get(['id', 'name', 'slug', 'description', 'price', 'stock', 'category_id']);
    }

    private function getCategorySummary(): Collection
    {
        return Category::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->limit(30)
            ->get(['id', 'name', 'slug']);
    }

    private function extractKeywords(string $text): array
    {
        $text = Str::lower(trim($text));
        if ($text === '') {
            return [];
        }

        $stopwords = [
            'toi', 'minh', 'ban', 'co', 'khong', 'la', 'cua', 'cho', 'va', 'de', 'o', 'voi',
            'nao', 'gi', 'nhu', 'the', 'hay', 'duoc', 'muon', 'mua', 'tim', 'kiem', 'san', 'pham',
            'gia', 'bao', 'nhieu', 'a', 'the nao', 'hoi', 'xin', 'chao', 'can', 'tu', 'van',
            'tôi', 'mình', 'bạn', 'có', 'không', 'là', 'của', 'cho', 'và', 'để', 'ở', 'với',
            'nào', 'gì', 'như', 'thế', 'hãy', 'được', 'muốn', 'tìm', 'kiếm', 'sản', 'phẩm',
            'giá', 'bao', 'nhiêu', 'tư', 'vấn', 'chào', 'cần', 'hỏi', 'xin',
        ];

        $parts = preg_split('/[\s,.;:!?\-\/\(\)\[\]"]+/u', $text) ?: [];
        $keywords = [];
        foreach ($parts as $part) {
            $part = trim($part);
            if ($part === '' || mb_strlen($part) < 2) {
                continue;
            }
            if (in_array($part, $stopwords, true)) {
                continue;
            }
            $keywords[] = $part;
        }

        return array_values(array_unique(array_slice($keywords, 0, 6)));
    }

    private function buildSystemInstruction(
        string $siteName,
        ?string $contactPhone,
        ?string $contactEmail,
        Collection $categories,
        Collection $relatedProducts,
        Collection $featuredProducts,
        array $context
    ): string {
        $categoryList = $categories->pluck('name')->implode(', ');
        $relatedText = $this->formatProductList($relatedProducts);
        $featuredText = $this->formatProductList($featuredProducts);

        $currentPage = '';
        if (!empty($context['product_name'])) {
            $currentPage = "Khách đang xem sản phẩm: {$context['product_name']}"
                . (!empty($context['product_price']) ? " - Giá: {$context['product_price']}" : '')
                . (!empty($context['url']) ? " - URL: {$context['url']}" : '');
        } elseif (!empty($context['title'])) {
            $currentPage = "Khách đang ở trang: {$context['title']}"
                . (!empty($context['url']) ? " ({$context['url']})" : '');
        }

        $contactInfo = [];
        if ($contactPhone) {
            $contactInfo[] = "Hotline: {$contactPhone}";
        }
        if ($contactEmail) {
            $contactInfo[] = "Email: {$contactEmail}";
        }
        $contactLine = !empty($contactInfo)
            ? implode(' | ', $contactInfo)
            : 'Chưa có thông tin liên hệ cụ thể.';

        return <<<PROMPT
Bạn là trợ lý AI tư vấn bán hàng của website thương mại điện tử "{$siteName}".

NHIỆM VỤ:
- Tư vấn sản phẩm cho khách hàng Việt Nam bằng tiếng Việt có dấu, văn phong thân thiện, chuyên nghiệp, ngắn gọn.
- Khi khách hỏi về sản phẩm: ưu tiên đề xuất các sản phẩm có trong danh sách dưới đây, nêu rõ tên, giá VND và lý do phù hợp.
- Nếu khách hỏi ngoài phạm vi sản phẩm đang bán (ví dụ sản phẩm shop không có), hãy nói rõ là shop chưa kinh doanh mặt hàng đó và gợi ý sản phẩm tương tự có trong danh mục.
- Không được bịa tên sản phẩm, giá, khuyến mãi, bảo hành nếu không có trong dữ liệu được cung cấp.
- Nếu cần thêm thông tin (ngân sách, nhu cầu, kích thước...) hãy hỏi lại khách 1-2 câu.
- Trả lời ngắn gọn, đủ ý, tối đa 150 từ. Dùng bullet bằng dấu "- " khi liệt kê sản phẩm.
- Khi đề xuất sản phẩm: ghi rõ TÊN SẢN PHẨM và GIÁ theo định dạng "xxx.xxx đ".
- Không lặp lại câu hỏi của khách, đi thẳng vào nội dung trả lời.
- Được phép dùng **in đậm** cho tên sản phẩm hoặc thông tin quan trọng (chỉ dùng cú pháp **text**, không dùng *text* hay ### heading).

THÔNG TIN CỬA HÀNG:
- Tên shop: {$siteName}
- Liên hệ: {$contactLine}

DANH MỤC SẢN PHẨM SHOP ĐANG BÁN:
{$categoryList}

SẢN PHẨM LIÊN QUAN ĐẾN CÂU HỎI CỦA KHÁCH:
{$relatedText}

SẢN PHẨM NỔI BẬT KHÁC:
{$featuredText}

NGỮ CẢNH TRANG HIỆN TẠI:
{$currentPage}

QUY TẮC XUẤT:
- Luôn dùng tiếng Việt có dấu.
- Không chèn URL nếu khách không yêu cầu; nếu có, dùng URL đầy đủ từ dữ liệu bên trên.
- Nếu không có sản phẩm phù hợp trong dữ liệu, hãy thành thật nói "shop hiện chưa có sản phẩm phù hợp" và gợi ý khách để lại liên hệ.
PROMPT;
    }

    private function formatProductList(Collection $products): string
    {
        if ($products->isEmpty()) {
            return '- (Không có sản phẩm phù hợp trong dữ liệu hiện tại)';
        }

        return $products->map(function ($product) {
            $category = optional($product->category)->name ?? 'Khác';
            $price = number_format((float) $product->price, 0, ',', '.') . ' đ';
            $stock = (int) ($product->stock ?? 0);
            $stockText = $stock > 0 ? "Còn hàng ({$stock})" : 'Hết hàng';
            $desc = Str::limit(strip_tags((string) $product->description), 180, '...');
            $url = route('products.show', $product->slug);

            return "- {$product->name} | Danh mục: {$category} | Giá: {$price} | {$stockText} | Link: {$url}"
                . ($desc !== '' ? "\n  Mô tả: {$desc}" : '');
        })->implode("\n");
    }

    private function buildContents(array $history, string $message): array
    {
        $contents = [];
        foreach ($history as $turn) {
            $role = ($turn['role'] ?? 'user') === 'bot' ? 'model' : 'user';
            $text = trim((string) ($turn['text'] ?? ''));
            if ($text === '') {
                continue;
            }
            $contents[] = [
                'role' => $role,
                'parts' => [['text' => $text]],
            ];
        }

        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $message]],
        ];

        return $contents;
    }
}
