<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->orders()->latest();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $orders = $query->paginate(10);
        return view('client.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }
        return view('client.orders.show', compact('order'));
    }

    public function store(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', __('messages.cart_empty'));
        }

        $address = auth()->user()->addresses()->find($request->shipping_address_id);
        if (!$address) {
            return back()->with('error', 'Địa chỉ giao hàng không hợp lệ!');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $order = auth()->user()->orders()->create([
            'order_number' => 'OD' . time() . rand(100,999),
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => $request->payment_method,
            'shipping_address_id' => $request->shipping_address_id,
            'total_amount' => $total,
            'notes' => $request->notes,
        ]);

        foreach ($cart as $item) {
            $order->items()->create([
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['price'] * $item['quantity']
            ]);
        }

        session()->forget('cart');
        return redirect()->route('orders.show', $order)->with('success', __('messages.order_created'));
    }
}
