<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\GameConfig;
use App\Models\GameSession;
use App\Models\QuizQuestion;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function index()
    {
        if (GameConfig::get('game_enabled', '0') !== '1') {
            return redirect()->route('home')->with('error', 'Trò chơi hiện đang đóng.');
        }

        $user = Auth::user();
        $session = GameSession::firstOrCreate(
            ['user_id' => $user->id, 'date' => Carbon::today()],
            [
                'questions_answered' => 0,
                'correct_streak' => 0,
                'total_correct' => 0,
                'bonus_questions' => 0,
                'has_won_today' => false,
            ]
        );

        $streakRequired = (int) GameConfig::get('streak_required', 3);
        $questionsAvailable = $session->questionsAvailable();

        return view('client.game.index', compact('session', 'streakRequired', 'questionsAvailable'));
    }

    public function getQuestion()
    {
        if (GameConfig::get('game_enabled', '0') !== '1') {
            return response()->json(['error' => 'Trò chơi hiện đang đóng.'], 403);
        }

        $session = GameSession::where('user_id', Auth::id())
            ->where('date', Carbon::today())
            ->first();

        if (!$session || !$session->canPlay()) {
            return response()->json(['error' => 'Bạn đã hết lượt chơi hôm nay. Hãy mua hàng để nhận thêm lượt!'], 400);
        }

        // Get a random active question
        $question = QuizQuestion::active()->inRandomOrder()->first();
        if (!$question) {
            return response()->json(['error' => 'Không tìm thấy câu hỏi nào.'], 404);
        }

        return response()->json([
            'id' => $question->id,
            'question' => $question->question,
        ]);
    }

    public function answerQuestion(Request $request)
    {
        $request->validate([
            'question_id' => 'required|exists:quiz_questions,id',
            'answer' => 'required|boolean',
        ]);

        $session = GameSession::where('user_id', Auth::id())
            ->where('date', Carbon::today())
            ->first();

        if (!$session || !$session->canPlay()) {
            return response()->json(['error' => 'Bạn đã hết lượt chơi hôm nay.'], 400);
        }

        $question = QuizQuestion::find($request->question_id);
        $isCorrect = ($question->is_correct_true == $request->answer);

        $session->questions_answered += 1;
        
        if ($isCorrect) {
            $session->correct_streak += 1;
            $session->total_correct += 1;
        } else {
            $session->correct_streak = 0;
        }
        $session->save();

        $streakRequired = (int) GameConfig::get('streak_required', 3);
        $canShake = $session->correct_streak >= $streakRequired;

        return response()->json([
            'is_correct' => $isCorrect,
            'explanation' => $question->explanation,
            'correct_streak' => $session->correct_streak,
            'can_shake' => $canShake,
            'questions_available' => $session->questionsAvailable(),
        ]);
    }

    public function shakeJar(Request $request)
    {
        $session = GameSession::where('user_id', Auth::id())
            ->where('date', Carbon::today())
            ->first();

        if (!$session) {
            return response()->json(['error' => 'Không tìm thấy phiên chơi.'], 400);
        }

        $streakRequired = (int) GameConfig::get('streak_required', 3);
        if ($session->correct_streak < $streakRequired) {
            return response()->json(['error' => 'Chưa đủ điều kiện lắc hũ.'], 400);
        }

        // Reset streak after shaking regardless of win or lose
        $session->correct_streak = 0;
        
        $winProb = (int) GameConfig::get('win_probability', 30);
        $isWin = (rand(1, 100) <= $winProb);

        if ($isWin) {
            $voucherId = GameConfig::get('game_voucher_id');
            if ($voucherId) {
                $voucher = Voucher::find($voucherId);
                // Check if voucher is valid (active and usage_limit not reached)
                if ($voucher && $voucher->is_active && ($voucher->usage_limit === null || $voucher->used_count < $voucher->usage_limit)) {
                    $session->has_won_today = true;
                    $session->save();
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Chúc mừng bạn đã nhận được Voucher!',
                        'voucher' => [
                            'code' => $voucher->code,
                            'discount_type' => $voucher->discount_type,
                            'discount_value' => $voucher->discount_value,
                        ],
                    ]);
                }
            }
        }
        
        $session->save();
        
        return response()->json([
            'success' => false,
            'message' => 'Rất tiếc, hũ rỗng! Chúc bạn may mắn lần sau.',
        ]);
    }
}
