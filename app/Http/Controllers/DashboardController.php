<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with statistics
     */
    public function index()
    {
        // User Statistics
        $totalUsers = User::count();
        $adminUsers = User::where('role', 'admin')->count();
        $doctorUsers = User::where('role', 'doctor')->count();
        $patientUsers = User::where('role', 'patient')->count();
        
        // Monthly user registration data (last 6 months)
        $monthlyUsers = $this->getMonthlyUserRegistrations();
        
        // User role distribution
        $userRoleDistribution = [
            'Admin' => $adminUsers,
            'Doctor' => $doctorUsers,
            'Patient' => $patientUsers,
        ];
        
        // Sales Statistics (Dummy Data)
        $totalSales = $this->getDummySalesData();
        $monthlySales = $this->getMonthlySalesData();
        $topProducts = $this->getTopProducts();
        
        // Recent activities (Dummy Data)
        $recentActivities = $this->getRecentActivities();
        
        // Calculate growth percentages
        $userGrowth = $this->calculateGrowthPercentage('users');
        $salesGrowth = $this->calculateGrowthPercentage('sales');
        
        return view('dashboard.index', compact(
            'totalUsers',
            'adminUsers',
            'doctorUsers',
            'patientUsers',
            'monthlyUsers',
            'userRoleDistribution',
            'totalSales',
            'monthlySales',
            'topProducts',
            'recentActivities',
            'userGrowth',
            'salesGrowth'
        ));
    }
    
    /**
     * Get monthly user registrations for the last 6 months
     */
    private function getMonthlyUserRegistrations()
    {
        $data = [];
        $months = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->format('M Y');
            $months[] = $month;
            
            // Get user count for this month
            $count = User::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count();
            
            // If no real data, use dummy data
            if ($count == 0 && $i > 0) {
                $count = rand(5, 20);
            }
            
            $data[] = $count;
        }
        
        return [
            'labels' => $months,
            'data' => $data
        ];
    }
    
    /**
     * Get dummy sales data
     */
    private function getDummySalesData()
    {
        return [
            'today' => rand(100000, 500000),
            'week' => rand(500000, 2000000),
            'month' => rand(2000000, 10000000),
            'year' => rand(10000000, 50000000),
        ];
    }
    
    /**
     * Get monthly sales data for the last 6 months
     */
    private function getMonthlySalesData()
    {
        $data = [];
        $months = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->format('M Y');
            $months[] = $month;
            
            // Dummy sales data
            $sales = rand(1000000, 5000000);
            $data[] = $sales;
        }
        
        return [
            'labels' => $months,
            'data' => $data
        ];
    }
    
    /**
     * Get top selling products (dummy data)
     */
    private function getTopProducts()
    {
        return [
            [
                'name' => 'Paracetamol 500mg',
                'category' => 'Medicine',
                'sold' => rand(500, 1000),
                'revenue' => rand(5000000, 10000000)
            ],
            [
                'name' => 'Amoxicillin 250mg',
                'category' => 'Antibiotic',
                'sold' => rand(300, 800),
                'revenue' => rand(3000000, 8000000)
            ],
            [
                'name' => 'Vitamin C 1000mg',
                'category' => 'Supplement',
                'sold' => rand(400, 900),
                'revenue' => rand(4000000, 9000000)
            ],
            [
                'name' => 'Blood Pressure Monitor',
                'category' => 'Medical Device',
                'sold' => rand(100, 300),
                'revenue' => rand(10000000, 20000000)
            ],
            [
                'name' => 'Surgical Mask (Box)',
                'category' => 'PPE',
                'sold' => rand(600, 1200),
                'revenue' => rand(2000000, 6000000)
            ],
        ];
    }
    
    /**
     * Get recent activities (dummy data)
     */
    private function getRecentActivities()
    {
        return [
            [
                'type' => 'order',
                'message' => 'New order #ORD-' . rand(1000, 9999) . ' placed',
                'time' => now()->subMinutes(rand(5, 30))->diffForHumans(),
                'icon' => 'shopping-cart',
                'color' => 'blue'
            ],
            [
                'type' => 'user',
                'message' => 'New user registered: Dr. ' . $this->getRandomName(),
                'time' => now()->subHours(rand(1, 5))->diffForHumans(),
                'icon' => 'user-plus',
                'color' => 'green'
            ],
            [
                'type' => 'payment',
                'message' => 'Payment received: Rp ' . number_format(rand(100000, 1000000)),
                'time' => now()->subHours(rand(2, 8))->diffForHumans(),
                'icon' => 'credit-card',
                'color' => 'yellow'
            ],
            [
                'type' => 'appointment',
                'message' => 'New appointment scheduled',
                'time' => now()->subHours(rand(3, 12))->diffForHumans(),
                'icon' => 'calendar',
                'color' => 'purple'
            ],
            [
                'type' => 'alert',
                'message' => 'Low stock alert: Paracetamol 500mg',
                'time' => now()->subDay()->diffForHumans(),
                'icon' => 'alert-triangle',
                'color' => 'red'
            ],
        ];
    }
    
    /**
     * Calculate growth percentage
     */
    private function calculateGrowthPercentage($type)
    {
        if ($type === 'users') {
            $currentMonth = User::whereYear('created_at', now()->year)
                               ->whereMonth('created_at', now()->month)
                               ->count();
            $lastMonth = User::whereYear('created_at', now()->subMonth()->year)
                            ->whereMonth('created_at', now()->subMonth()->month)
                            ->count();
            
            // Use dummy data if no real data
            if ($currentMonth == 0 && $lastMonth == 0) {
                return rand(5, 25);
            }
            
            if ($lastMonth == 0) return 100;
            
            return round((($currentMonth - $lastMonth) / $lastMonth) * 100, 1);
        }
        
        // Sales growth (dummy)
        return rand(-5, 30);
    }
    
    /**
     * Get random name for dummy data
     */
    private function getRandomName()
    {
        $names = ['Ahmad', 'Budi', 'Citra', 'Dewi', 'Eko', 'Fitri', 'Gani', 'Hani'];
        return $names[array_rand($names)];
    }
}
