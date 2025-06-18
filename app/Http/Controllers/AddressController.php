<?php

namespace App\Http\Controllers;

use App\Services\GHNApiService;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    protected $ghnService;

    public function __construct(GHNApiService $ghnService)
    {
        $this->ghnService = $ghnService;
    }

    public function getProvinces()
    {
        $provinces = $this->ghnService->getProvinces();
        return response()->json($provinces);
    }

    public function getDistricts(Request $request)
    {
        $request->validate([
            'province_id' => 'required|integer'
        ]);

        $districts = $this->ghnService->getDistricts($request->province_id);
        return response()->json($districts);
    }

    public function getWards(Request $request)
    {
        $request->validate([
            'district_id' => 'required|integer'
        ]);

        $wards = $this->ghnService->getWards($request->district_id);
        return response()->json($wards);
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'province' => 'required',
            'district' => 'required',
            'ward' => 'required',
        ]);
        $address = $request->user()->addresses()->create([
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'province_id' => $request->province,
            'province_name' => $request->province_name,
            'district_id' => $request->district,
            'district_name' => $request->district_name,
            'ward_code' => $request->ward,
            'ward_name' => $request->ward_name,
            'is_default' => false,
        ]);
        return response()->json($address, 201);
    }
} 