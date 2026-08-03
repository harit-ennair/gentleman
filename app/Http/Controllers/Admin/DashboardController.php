<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AppointmentStatus;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View|JsonResponse
    {
        $this->authorizeAdmin();

        // 1. Calculate Today Revenue (Completed/Paid Orders + Completed Appointments ONLY)
        $todayOrderRevenue = (float) Order::where(function ($q) {
            $q->where('payment_status', PaymentStatus::Paid)
                ->orWhere('status', OrderStatus::Completed);
        })
            ->where(function ($q) {
                $q->whereDate('created_at', today())
                    ->orWhereDate('order_date', today());
            })
            ->sum('total');

        $todayApptRevenue = (float) Appointment::join('services', 'appointments.service_id', '=', 'services.id')
            ->where('appointments.status', AppointmentStatus::Completed)
            ->whereDate('appointments.appointment_at', today())
            ->sum('services.price');

        $todayRevenue = $todayOrderRevenue + $todayApptRevenue;

        // 2. Calculate Current Month Revenue (Completed/Paid Orders + Completed Appointments ONLY)
        $monthlyOrderRevenue = (float) Order::where(function ($q) {
            $q->where('payment_status', PaymentStatus::Paid)
                ->orWhere('status', OrderStatus::Completed);
        })
            ->where(function ($q) {
                $q->whereMonth('created_at', now()->month)
                    ->orWhereMonth('order_date', now()->month);
            })
            ->sum('total');

        $monthlyApptRevenue = (float) Appointment::join('services', 'appointments.service_id', '=', 'services.id')
            ->where('appointments.status', AppointmentStatus::Completed)
            ->whereMonth('appointments.appointment_at', now()->month)
            ->whereYear('appointments.appointment_at', now()->year)
            ->sum('services.price');

        $monthlyRevenue = $monthlyOrderRevenue + $monthlyApptRevenue;

        // 3. Compute Monthly Revenue Trend Chart Data for Current Year (Jan..Dec)
        $chartMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $salesByMonth = Order::where(function ($q) {
            $q->where('payment_status', PaymentStatus::Paid)
                ->orWhere('status', OrderStatus::Completed);
        })
            ->whereYear('created_at', now()->year)
            ->selectRaw('MONTH(created_at) as month, SUM(total) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $apptsByMonth = Appointment::join('services', 'appointments.service_id', '=', 'services.id')
            ->where('appointments.status', AppointmentStatus::Completed)
            ->whereYear('appointments.appointment_at', now()->year)
            ->selectRaw('MONTH(appointments.appointment_at) as month, SUM(services.price) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $chartRevenueData = [];
        for ($m = 1; $m <= 12; $m++) {
            $orderSum = (float) ($salesByMonth[$m] ?? 0);
            $apptSum = (float) ($apptsByMonth[$m] ?? 0);
            $chartRevenueData[] = $orderSum + $apptSum;
        }

        // 4. Compute Appointment Status Distribution Data
        $appointmentStatusesChart = [
            'Confirmed' => Appointment::where('status', AppointmentStatus::Confirmed)->count(),
            'Pending' => Appointment::where('status', AppointmentStatus::Pending)->count(),
            'Completed' => Appointment::where('status', AppointmentStatus::Completed)->count(),
            'Cancelled' => Appointment::where('status', AppointmentStatus::Cancelled)->count(),
        ];

        if (request()->wantsJson()) {
            return response()->json([
                'clients_count' => User::where('role', Role::Customer)->count(),
                'appointments_count' => Appointment::count(),
                'orders_count' => Order::count(),
                'revenues' => (float) $monthlyRevenue,
            ]);
        }

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
            'chartMonths' => $chartMonths,
            'chartRevenueData' => $chartRevenueData,
            'appointmentStatusesChart' => $appointmentStatusesChart,
        ]);
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->role === Role::Admin, 403);
    }
}
