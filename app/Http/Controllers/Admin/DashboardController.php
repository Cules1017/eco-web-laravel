<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Lọc theo thời gian
        $date_from = $request->input('date_from');
        $date_to = $request->input('date_to');
        $orderQuery = Order::query();
        if ($date_from) {
            $orderQuery->whereDate('created_at', '>=', $date_from);
        }
        if ($date_to) {
            $orderQuery->whereDate('created_at', '<=', $date_to);
        }
        $orders = $orderQuery->get();
        $order_count = $orders->count();
        $revenue = $orders->sum('total_amount');
        // Sản phẩm bán chạy
        $top_products = Product::select('products.*')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->when($date_from, function($q) use ($date_from) {
                $q->whereDate('orders.created_at', '>=', $date_from);
            })
            ->when($date_to, function($q) use ($date_to) {
                $q->whereDate('orders.created_at', '<=', $date_to);
            })
            ->selectRaw('SUM(order_items.quantity) as total_sold')
            ->groupBy('products.id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();
        $user_count = User::count();
        return view('admin.dashboard', compact('order_count', 'revenue', 'top_products', 'user_count', 'date_from', 'date_to'));
    }
} 