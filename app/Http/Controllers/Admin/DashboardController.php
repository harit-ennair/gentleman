<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AppointmentStatus;
use App\Enums\PaymentStatus;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard statistics.
     */
    public function index(): JsonResponse
    {
        $today = today();

        $clientsCount = User::where('role', Role::Customer)->count();
        $appointmentsCount = Appointment::count();
        $ordersCount = Order::count();
        $totalRevenue = Order::where('payment_status', PaymentStatus::Paid)->sum('total');
        $outOfStockCount = Product::where('stock_quantity', '<=', 0)->count();

        $todayAppointmentsCount = Appointment::whereDate('appointment_at', $today)->count();
        $pendingAppointmentsCount = Appointment::where('status', AppointmentStatus::Pending)->count();

        $monthlyRevenue = Order::where('payment_status', PaymentStatus::Paid)
            ->whereMonth('order_date', $today->month)
            ->whereYear('order_date', $today->year)
            ->sum('total');

        $todayRevenue = Order::where('payment_status', PaymentStatus::Paid)
            ->whereDate('order_date', $today)
            ->sum('total');

        $todayOrdersCount = Order::whereDate('order_date', $today)->count();

        $latestOrders = Order::with('user')
            ->latest()
            ->limit(5)
            ->get();

        $latestAppointments = Appointment::with(['user', 'service'])
            ->latest('appointment_at')
            ->limit(5)
            ->get();

        return response()->json([
            'clients_count' => $clientsCount,
            'appointments_count' => $appointmentsCount,
            'orders_count' => $ordersCount,
            'revenues' => $totalRevenue,
            'out_of_stock_products_count' => $outOfStockCount,
            'today_appointments_count' => $todayAppointmentsCount,
            'pending_appointments_count' => $pendingAppointmentsCount,
            'monthly_revenue' => $monthlyRevenue,
            'today_revenue' => $todayRevenue,
            'today_orders_count' => $todayOrdersCount,
            'latest_orders' => $latestOrders,
            'latest_appointments' => $latestAppointments,
        ]);
    }
}
