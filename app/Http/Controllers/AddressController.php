<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Services\GHNApiService;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    protected $ghnService;

    public function __construct(GHNApiService $ghnService)
    {
        $this->ghnService = $ghnService;
    }

    public function index(Request $request)
    {
        $addresses = $request->user()
            ->addresses()
            ->orderByDesc('is_default')
            ->orderByDesc('id')
            ->get();

        return view('client.addresses.index', compact('addresses'));
    }

    public function create()
    {
        return view('client.addresses.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $isDefault = $request->boolean('is_default');
        if ($isDefault || $request->user()->addresses()->count() === 0) {
            $request->user()->addresses()->update(['is_default' => false]);
            $data['is_default'] = true;
        } else {
            $data['is_default'] = false;
        }

        $request->user()->addresses()->create($data);

        if ($request->wantsJson()) {
            return response()->json(['success' => true], 201);
        }
        return redirect()->route('addresses.index')->with('success', __('messages.address_saved') ?? 'Đã lưu địa chỉ.');
    }

    public function edit(Address $address, Request $request)
    {
        $this->authorizeOwn($address, $request);
        return view('client.addresses.edit', compact('address'));
    }

    public function update(Request $request, Address $address)
    {
        $this->authorizeOwn($address, $request);

        $data = $this->validated($request);
        $data['is_default'] = $request->boolean('is_default');

        if ($data['is_default']) {
            $request->user()->addresses()
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }

        $address->update($data);

        return redirect()->route('addresses.index')->with('success', __('messages.address_updated') ?? 'Đã cập nhật địa chỉ.');
    }

    public function destroy(Address $address, Request $request)
    {
        $this->authorizeOwn($address, $request);

        $wasDefault = $address->is_default;
        $address->delete();

        if ($wasDefault) {
            $next = $request->user()->addresses()->orderByDesc('id')->first();
            if ($next) {
                $next->update(['is_default' => true]);
            }
        }

        return redirect()->route('addresses.index')->with('success', __('messages.address_deleted') ?? 'Đã xoá địa chỉ.');
    }

    public function setDefault(Address $address, Request $request)
    {
        $this->authorizeOwn($address, $request);

        $request->user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return redirect()->route('addresses.index')->with('success', __('messages.default_address_set') ?? 'Đã đặt làm địa chỉ mặc định.');
    }

    public function getProvinces()
    {
        return response()->json($this->ghnService->getProvinces());
    }

    public function getDistricts(Request $request)
    {
        $request->validate(['province_id' => 'required|integer']);
        return response()->json($this->ghnService->getDistricts($request->province_id));
    }

    public function getWards(Request $request)
    {
        $request->validate(['district_id' => 'required|integer']);
        return response()->json($this->ghnService->getWards($request->district_id));
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'full_name'     => 'required|string|max:255',
            'phone'         => 'required|string|max:20',
            'address'       => 'required|string|max:255',
            'province_name' => 'required|string|max:255',
            'district_name' => 'required|string|max:255',
            'ward_name'     => 'required|string|max:255',
        ]);
    }

    protected function authorizeOwn(Address $address, Request $request): void
    {
        abort_if($address->user_id !== $request->user()->id, 403);
    }
}
