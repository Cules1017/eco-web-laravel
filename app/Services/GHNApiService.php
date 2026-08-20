<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Dịch vụ lấy danh mục hành chính Việt Nam.
 *
 * Ưu tiên dùng API công cộng https://provinces.open-api.vn/ (miễn phí, không cần token).
 * Nếu GHN_API_TOKEN được cấu hình, sẽ fallback dùng GHN để đồng bộ với đơn vị vận chuyển.
 * Kết quả được chuẩn hoá về định dạng GHN (ProvinceID/ProvinceName, DistrictID/DistrictName,
 * WardCode/WardName) để tương thích ngược với các view/JS hiện tại.
 */
class GHNApiService
{
    protected string $openApiUrl = 'https://provinces.open-api.vn/api/v1';
    protected string $ghnBaseUrl;
    protected ?string $ghnToken;

    public function __construct()
    {
        $this->ghnBaseUrl = (string) config('services.ghn.base_url', 'https://dev-online.ghn.vn');
        $this->ghnToken   = config('services.ghn.token');
    }

    public function getProvinces(): array
    {
        return Cache::remember('vn_provinces_v2', 86400, function () {
            if ($this->ghnToken) {
                $data = $this->fetchGhn('/shiip/public-api/master-data/province');
                if (!empty($data)) return $data;
            }

            $response = Http::timeout(10)->get("{$this->openApiUrl}/p/");
            if (!$response->successful()) {
                Log::warning('[GHNApiService] getProvinces: open-api.vn failed', ['status' => $response->status()]);
                return [];
            }

            return array_map(fn ($p) => [
                'ProvinceID'   => $p['code'] ?? null,
                'ProvinceName' => $p['name'] ?? '',
            ], $response->json() ?? []);
        });
    }

    public function getDistricts($provinceId): array
    {
        $provinceId = (int) $provinceId;
        if ($provinceId <= 0) return [];

        return Cache::remember("vn_districts_v2_{$provinceId}", 86400, function () use ($provinceId) {
            if ($this->ghnToken) {
                $data = $this->fetchGhn('/shiip/public-api/master-data/district', ['province_id' => $provinceId]);
                if (!empty($data)) return $data;
            }

            $response = Http::timeout(10)->get("{$this->openApiUrl}/p/{$provinceId}", ['depth' => 2]);
            if (!$response->successful()) {
                Log::warning('[GHNApiService] getDistricts failed', ['province' => $provinceId, 'status' => $response->status()]);
                return [];
            }

            $districts = $response->json()['districts'] ?? [];
            return array_map(fn ($d) => [
                'DistrictID'   => $d['code'] ?? null,
                'DistrictName' => $d['name'] ?? '',
                'ProvinceID'   => $provinceId,
            ], $districts);
        });
    }

    public function getWards($districtId): array
    {
        $districtId = (int) $districtId;
        if ($districtId <= 0) return [];

        return Cache::remember("vn_wards_v2_{$districtId}", 86400, function () use ($districtId) {
            if ($this->ghnToken) {
                $data = $this->fetchGhn('/shiip/public-api/master-data/ward', ['district_id' => $districtId]);
                if (!empty($data)) return $data;
            }

            $response = Http::timeout(10)->get("{$this->openApiUrl}/d/{$districtId}", ['depth' => 2]);
            if (!$response->successful()) {
                Log::warning('[GHNApiService] getWards failed', ['district' => $districtId, 'status' => $response->status()]);
                return [];
            }

            $wards = $response->json()['wards'] ?? [];
            return array_map(fn ($w) => [
                'WardCode'   => (string) ($w['code'] ?? ''),
                'WardName'   => $w['name'] ?? '',
                'DistrictID' => $districtId,
            ], $wards);
        });
    }

    protected function fetchGhn(string $path, array $query = []): array
    {
        try {
            $response = Http::withHeaders(['Token' => $this->ghnToken])
                ->timeout(10)
                ->get("{$this->ghnBaseUrl}{$path}", $query);

            if ($response->successful()) {
                return $response->json()['data'] ?? [];
            }
            Log::warning('[GHNApiService] GHN request failed', [
                'path' => $path, 'status' => $response->status(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('[GHNApiService] GHN exception', ['error' => $e->getMessage()]);
        }
        return [];
    }
}
