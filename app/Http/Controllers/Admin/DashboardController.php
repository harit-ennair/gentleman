<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AppointmentStatus;
use App\Enums\PaymentStatus;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $this->authorizeAdmin();

        $todayRevenue = Order::where('payment_status', PaymentStatus::Paid)
            ->whereDate('order_date', today())
            ->sum('total');
        $monthlyRevenue = Order::where('payment_status', PaymentStatus::Paid)
            ->whereYear('order_date', now()->year)
            ->whereMonth('order_date', now()->month)
            ->sum('total');

        return view('admin.dashboard', [
            'clientsCount' => User::where('role', Role::Customer)->count(),
            'appointmentsCount' => Appointment::count(),
            'ordersCount' => Order::count(),
            'todayRevenue' => $todayRevenue,
            'monthlyRevenue' => $monthlyRevenue,
            'latestAppointments' => Appointment::with(['user', 'service'])->latest('appointment_at')->limit(5)->get(),
            'latestOrders' => Order::with('user')->latest()->limit(5)->get(),
            'lowStockProducts' => Product::with('category')->where('stock_quantity', '<=', 5)->orderBy('stock_quantity')->get(),
            'pendingAppointmentsCount' => Appointment::where('status', AppointmentStatus::Pending)->count(),
            'categories' => Category::orderBy('name')->get(),
            'products' => Product::with('category')->latest()->get(),
            'services' => Service::orderBy('name')->get(),
        ]);
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->role === Role::Admin, 403);
    }
}
