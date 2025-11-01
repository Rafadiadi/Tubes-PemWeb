<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display sales report
     */
    public function sales(Request $request)
    {
        // Get date range from request or default to last 30 days
        $startDate = $request->input('start_date', Carbon::now()->subDays(30));
        $endDate = $request->input('end_date', Carbon::now());

        // Daily sales data
        $dailySales = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total_price) as total_revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Product category sales
        $categorySales = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'products.category_id',
                DB::raw('COUNT(DISTINCT orders.id) as total_orders'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
            )
            ->groupBy('products.category_id')
            ->get();

        // Top selling products
        $topProducts = Product::join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'products.id',
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->take(10)
            ->get();

        // Sales summary
        $totalOrders = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $totalRevenue = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_price');

        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        return view('reports.sales', compact(
            'dailySales',
            'categorySales',
            'topProducts',
            'totalOrders',
            'totalRevenue',
            'averageOrderValue',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Display users report
     */
    public function users(Request $request)
    {
        // Get date range from request or default to last 30 days
        $startDate = $request->input('start_date', Carbon::now()->subDays(30));
        $endDate = $request->input('end_date', Carbon::now());

        // New users registration trend
        $userRegistrations = User::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_users')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // User role distribution
        $usersByRole = User::select('role', DB::raw('COUNT(*) as total'))
            ->groupBy('role')
            ->get();

        // Active users (users who made orders)
        $activeUsers = User::join('orders', 'users.id', '=', 'orders.user_id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select('users.id', 'users.name', 'users.email', DB::raw('COUNT(orders.id) as total_orders'))
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('total_orders', 'desc')
            ->take(10)
            ->get();

        // User statistics
        $totalUsers = User::count();
        $newUsers = User::whereBetween('created_at', [$startDate, $endDate])->count();
        $activeUsersCount = User::join('orders', 'users.id', '=', 'orders.user_id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->distinct('users.id')
            ->count();

        // Top customers by revenue
        $topCustomers = User::join('orders', 'users.id', '=', 'orders.user_id')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'users.id',
                'users.name',
                'users.email',
                DB::raw('COUNT(orders.id) as total_orders'),
                DB::raw('SUM(orders.total_price) as total_spent')
            )
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('total_spent', 'desc')
            ->take(10)
            ->get();

        return view('reports.users', compact(
            'userRegistrations',
            'usersByRole',
            'activeUsers',
            'totalUsers',
            'newUsers',
            'activeUsersCount',
            'topCustomers',
            'startDate',
            'endDate'
        ));
    }
}
