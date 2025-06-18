<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class GHNApiService
{
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = config('services.ghn.base_url', 'https://dev-online.ghn.vn');
        $this->token = config('services.ghn.token');
    }

    public function getProvinces()
    {
        return Cache::remember('ghn_provinces', 86400, function () {
            $response = Http::withHeaders([
                'Token' => $this->token
            ])->get("{$this->baseUrl}/shiip/public-api/master-data/province");

            if ($response->successful()) {
                return $response->json()['data'] ?? [];
            }
            return [];
        });
    }

    public function getDistricts($provinceId)
    {
        return Cache::remember("ghn_districts_{$provinceId}", 86400, function () use ($provinceId) {
            $response = Http::withHeaders([
                'Token' => $this->token
            ])->get("{$this->baseUrl}/shiip/public-api/master-data/district", [
                'province_id' => $provinceId
            ]);

            if ($response->successful()) {
                return $response->json()['data'] ?? [];
            }
            return [];
        });
    }

    public function getWards($districtId)
    {
        return Cache::remember("ghn_wards_{$districtId}", 86400, function () use ($districtId) {
            $response = Http::withHeaders([
                'Token' => $this->token
            ])->get("{$this->baseUrl}/shiip/public-api/master-data/ward", [
                'district_id' => $districtId
            ]);

            if ($response->successful()) {
                return $response->json()['data'] ?? [];
            }
            return [];
        });
    }
} 