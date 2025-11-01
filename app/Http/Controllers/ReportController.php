<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display sales report
     */
    public function sales(Request $request)
    {
        // Get filter parameters
        $period = $request->get('period', 'month'); // day, week, month, year
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now());
        
        // Generate dummy sales data
        $salesData = $this->generateSalesData($period, $startDate, $endDate);
        
        // Calculate summary statistics
        $summary = $this->calculateSalesSummary($salesData);
        
        // Get top products
        $topProducts = $this->getTopSellingProducts();
        
        // Get sales by category
        $categoryBreakdown = $this->getSalesByCategory();
        
        // Get payment methods breakdown
        $paymentMethods = $this->getPaymentMethodsBreakdown();
        
        return view('reports.sales', compact(
            'salesData',
            'summary',
            'topProducts',
            'categoryBreakdown',
            'paymentMethods',
            'period',
            'startDate',
            'endDate'
        ));
    }
    
    /**
     * Display users report
     */
    public function users(Request $request)
    {
        // Get filter parameters
        $role = $request->get('role', 'all');
        $period = $request->get('period', 'month');
        
        // Get users data
        $query = User::query();
        
        if ($role !== 'all') {
            $query->where('role', $role);
        }
        
        $users = $query->latest()->paginate(20);
        
        // Get user statistics
        $statistics = [
            'total' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'doctors' => User::where('role', 'doctor')->count(),
            'patients' => User::where('role', 'patient')->count(),
            'active_today' => User::whereDate('updated_at', today())->count(),
            'new_this_month' => User::whereYear('created_at', now()->year)
                                   ->whereMonth('created_at', now()->month)
                                   ->count(),
        ];
        
        // Get registration trend
        $registrationTrend = $this->getUserRegistrationTrend($period);
        
        // Get user activity data (dummy)
        $activityData = $this->getUserActivityData();
        
        // Get user demographics (dummy)
        $demographics = $this->getUserDemographics();
        
        return view('reports.users', compact(
            'users',
            'statistics',
            'registrationTrend',
            'activityData',
            'demographics',
            'role',
            'period'
        ));
    }
    
    /**
     * Generate dummy sales data
     */
    private function generateSalesData($period, $startDate, $endDate)
    {
        $data = [];
        
        // Generate 20 dummy sales transactions
        for ($i = 0; $i < 20; $i++) {
            $data[] = [
                'id' => 'ORD-' . str_pad($i + 1000, 4, '0', STR_PAD_LEFT),
                'date' => now()->subDays(rand(0, 30))->format('Y-m-d H:i:s'),
                'customer' => $this->getRandomCustomerName(),
                'product' => $this->getRandomProduct(),
                'quantity' => rand(1, 10),
                'unit_price' => rand(50000, 500000),
                'total' => rand(50000, 5000000),
                'payment_method' => $this->getRandomPaymentMethod(),
                'status' => $this->getRandomStatus(),
            ];
        }
        
        // Sort by date descending
        usort($data, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        return $data;
    }
    
    /**
     * Calculate sales summary
     */
    private function calculateSalesSummary($salesData)
    {
        $totalRevenue = array_sum(array_column($salesData, 'total'));
        $totalOrders = count($salesData);
        $completedOrders = count(array_filter($salesData, function($item) {
            return $item['status'] === 'Completed';
        }));
        
        return [
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders,
            'completed_orders' => $completedOrders,
            'pending_orders' => $totalOrders - $completedOrders,
            'average_order_value' => $totalOrders > 0 ? $totalRevenue / $totalOrders : 0,
            'completion_rate' => $totalOrders > 0 ? ($completedOrders / $totalOrders) * 100 : 0,
        ];
    }
    
    /**
     * Get top selling products
     */
    private function getTopSellingProducts()
    {
        return [
            ['name' => 'Paracetamol 500mg', 'units_sold' => rand(800, 1500), 'revenue' => rand(8000000, 15000000)],
            ['name' => 'Amoxicillin 250mg', 'units_sold' => rand(600, 1200), 'revenue' => rand(6000000, 12000000)],
            ['name' => 'Vitamin C 1000mg', 'units_sold' => rand(500, 1000), 'revenue' => rand(5000000, 10000000)],
            ['name' => 'Blood Pressure Monitor', 'units_sold' => rand(200, 500), 'revenue' => rand(20000000, 40000000)],
            ['name' => 'Surgical Mask (Box)', 'units_sold' => rand(1000, 2000), 'revenue' => rand(3000000, 8000000)],
        ];
    }
    
    /**
     * Get sales by category
     */
    private function getSalesByCategory()
    {
        return [
            ['category' => 'Medicine', 'revenue' => rand(20000000, 40000000), 'percentage' => rand(30, 40)],
            ['category' => 'Medical Devices', 'revenue' => rand(15000000, 30000000), 'percentage' => rand(20, 30)],
            ['category' => 'Supplements', 'revenue' => rand(10000000, 25000000), 'percentage' => rand(15, 25)],
            ['category' => 'PPE', 'revenue' => rand(5000000, 15000000), 'percentage' => rand(10, 15)],
            ['category' => 'Others', 'revenue' => rand(3000000, 10000000), 'percentage' => rand(5, 10)],
        ];
    }
    
    /**
     * Get payment methods breakdown
     */
    private function getPaymentMethodsBreakdown()
    {
        return [
            ['method' => 'Credit Card', 'count' => rand(40, 60), 'percentage' => rand(35, 45)],
            ['method' => 'Bank Transfer', 'count' => rand(30, 50), 'percentage' => rand(25, 35)],
            ['method' => 'E-Wallet', 'count' => rand(20, 40), 'percentage' => rand(20, 30)],
            ['method' => 'Cash', 'count' => rand(10, 20), 'percentage' => rand(8, 15)],
        ];
    }
    
    /**
     * Get user registration trend
     */
    private function getUserRegistrationTrend($period)
    {
        $labels = [];
        $data = [];
        
        $iterations = $period === 'year' ? 12 : 6;
        
        for ($i = $iterations - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->format('M Y');
            
            $count = User::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count();
            
            // Use dummy data if no real data
            $data[] = $count > 0 ? $count : rand(5, 25);
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
    
    /**
     * Get user activity data (dummy)
     */
    private function getUserActivityData()
    {
        return [
            'daily_active' => rand(50, 150),
            'weekly_active' => rand(200, 400),
            'monthly_active' => rand(500, 1000),
            'average_session_duration' => rand(5, 30) . ' minutes',
        ];
    }
    
    /**
     * Get user demographics (dummy)
     */
    private function getUserDemographics()
    {
        return [
            'age_groups' => [
                '18-25' => rand(15, 25),
                '26-35' => rand(25, 35),
                '36-45' => rand(20, 30),
                '46-55' => rand(15, 20),
                '56+' => rand(10, 15),
            ],
            'gender' => [
                'Male' => rand(45, 55),
                'Female' => rand(45, 55),
            ],
        ];
    }
    
    /**
     * Helper methods for dummy data
     */
    private function getRandomCustomerName()
    {
        $names = ['Ahmad Fadli', 'Budi Santoso', 'Citra Dewi', 'Dian Pertiwi', 'Eko Prasetyo', 
                  'Fitri Handayani', 'Gani Kurniawan', 'Hani Wijaya', 'Indra Gunawan', 'Joko Susilo'];
        return $names[array_rand($names)];
    }
    
    private function getRandomProduct()
    {
        $products = ['Paracetamol 500mg', 'Amoxicillin 250mg', 'Vitamin C 1000mg', 
                     'Blood Pressure Monitor', 'Surgical Mask', 'Hand Sanitizer', 
                     'Thermometer', 'Bandage Set', 'Multivitamin', 'Aspirin'];
        return $products[array_rand($products)];
    }
    
    private function getRandomPaymentMethod()
    {
        $methods = ['Credit Card', 'Bank Transfer', 'E-Wallet', 'Cash'];
        return $methods[array_rand($methods)];
    }
    
    private function getRandomStatus()
    {
        $statuses = ['Completed', 'Pending', 'Processing'];
        $weights = [70, 20, 10]; // 70% completed, 20% pending, 10% processing
        
        $rand = rand(1, 100);
        if ($rand <= 70) return 'Completed';
        if ($rand <= 90) return 'Pending';
        return 'Processing';
    }
}
