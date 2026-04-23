<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'shippingAddress']);
        // Lọc theo ngày
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        // Lọc theo khách hàng
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $orders = $query->latest()->paginate(10);
        $users = \App\Models\User::select('id','first_name','last_name','email')->get();
        return view('admin.orders.index', compact('orders', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        try {
            // Load relationships
            $order->load(['user', 'shippingAddress', 'items.product']);
            
            // Debug thông tin
            Log::info('Order ID: ' . $order->id);
            Log::info('Shipping Address ID: ' . $order->shipping_address_id);
            Log::info('Shipping Address: ' . ($order->shippingAddress ? 'exists' : 'null'));
            
            return view('admin.orders.show', compact('order'));
        } catch (\Exception $e) {
            Log::error('Error in OrderController@show: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra khi tải thông tin đơn hàng: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipping,completed,cancelled',
        ]);
        $order->status = $request->status;
        $order->save();
        return back()->with('success', 'Cập nhật trạng thái đơn hàng thành công.');
    }

    /**
     * Xác nhận thủ công đã nhận được thanh toán (chuyển khoản ngân hàng).
     */
    public function markPaid(Request $request, Order $order)
    {
        $request->validate([
            'payment_transaction_id' => 'nullable|string|max:100',
            'payment_note' => 'nullable|string|max:500',
        ]);

        if ($order->payment_status === 'paid') {
            return back()->with('info', 'Đơn hàng đã được xác nhận thanh toán trước đó.');
        }

        $payload = $order->payment_payload ?? [];
        $payload['confirmed_by']    = auth()->id();
        $payload['confirmed_at']    = now()->toIso8601String();
        $payload['confirm_note']    = $request->input('payment_note');

        $order->update([
            'payment_status'         => 'paid',
            'payment_transaction_id' => $request->input('payment_transaction_id'),
            'paid_at'                => now(),
            'status'                 => $order->status === 'pending' ? 'processing' : $order->status,
            'payment_payload'        => $payload,
        ]);

        return back()->with('success', 'Đã xác nhận thanh toán cho đơn hàng #' . $order->order_number);
    }

    /**
     * Đánh dấu thanh toán thất bại (huỷ xác nhận / khi hoàn tiền).
     */
    public function markUnpaid(Request $request, Order $order)
    {
        if ($order->payment_status !== 'paid') {
            return back()->with('info', 'Đơn này chưa được đánh dấu đã thanh toán.');
        }

        $payload = $order->payment_payload ?? [];
        $payload['unconfirmed_by']    = auth()->id();
        $payload['unconfirmed_at']    = now()->toIso8601String();

        $order->update([
            'payment_status'  => 'failed',
            'paid_at'         => null,
            'payment_payload' => $payload,
        ]);

        return back()->with('success', 'Đã chuyển trạng thái thanh toán về "Thất bại".');
    }
}
