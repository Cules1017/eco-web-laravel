<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Address;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('cart', []);
        $cart[$product->id] = [
            'id' => $product->id,
            'slug' => $product->slug,
            'name' => $product->name,
            'price' => $product->price,
            'image' => $product->image,
            'quantity' => $request->quantity,
        ];
        session(['cart' => $cart]);

        $cartCount = array_sum(array_column($cart, 'quantity'));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'cart_count' => $cartCount,
                'message' => __('messages.added_to_cart'),
            ]);
        }

        if ($request->boolean('buy_now')) {
            return redirect()->route('checkout');
        }

        return redirect()->route('cart.index')->with('success', __('messages.added_to_cart'));
    }

    public function checkout()
    {
        $cart = session('cart', []);
        $addresses = auth()->user()->addresses()->latest()->take(3)->get();
        return view('client.cart.checkout', compact('cart', 'addresses'));
    }

    public function index()
    {
        $cart = session('cart', []);
        $cartItems = [];
        $total = 0;
        foreach ($cart as $item) {
            $product = \App\Models\Product::find($item['id']);
            if ($product) {
                $cartItems[] = (object)[
                    'product' => $product,
                    'quantity' => $item['quantity'],
                ];
                $total += $product->price * $item['quantity'];
            }
        }
        return view('client.cart.index', compact('cartItems', 'total'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        $cart = session()->get('cart', []);
        $id = $request->product_id;
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            session(['cart' => $cart]);
            $msg = __('messages.cart_updated');
            $success = true;
        } else {
            $msg = __('messages.product_not_found');
            $success = false;
        }
        if ($request->ajax()) {
            return response()->json(['success' => $success, 'message' => $msg]);
        }
        return redirect()->route('cart.index')->with($success ? 'success' : 'error', $msg);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
        $cart = session()->get('cart', []);
        $id = $request->product_id;
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session(['cart' => $cart]);
            $msg = __('messages.product_removed');
            $success = true;
        } else {
            $msg = __('messages.product_not_found');
            $success = false;
        }
        if ($request->ajax()) {
            return response()->json(['success' => $success, 'message' => $msg]);
        }
        return redirect()->route('cart.index')->with($success ? 'success' : 'error', $msg);
    }

    public function applyVoucher(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        $voucher = \App\Models\Voucher::where('code', $request->code)->first();
        
        if (!$voucher) {
            return back()->with('error', 'Mã giảm giá không tồn tại.');
        }

        $cart = session('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        if (!$voucher->isValidForAmount($total)) {
            return back()->with('error', 'Mã giảm giá đã hết hạn, hết lượt dùng hoặc đơn hàng chưa đủ điều kiện áp dụng.');
        }

        session(['voucher' => $voucher]);
        return back()->with('success', 'Đã áp dụng mã giảm giá.');
    }

    public function removeVoucher()
    {
        session()->forget('voucher');
        return back()->with('success', 'Đã gỡ mã giảm giá.');
    }
}
