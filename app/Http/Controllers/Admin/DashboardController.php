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
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View|JsonResponse
    {
        $this->authorizeAdmin();

        // 1. Calculate Today Revenue (Paid Orders ONLY + Completed Appointments ONLY)
        $todayOrderRevenue = (float) Order::where('payment_status', PaymentStatus::Paid)
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

        // 2. Calculate Current Month Revenue (Paid Orders ONLY + Completed Appointments ONLY)
        $monthlyOrderRevenue = (float) Order::where('payment_status', PaymentStatus::Paid)
            ->where(function ($q) {
                $q->whereMonth('created_at', now()->month)
                    ->orWhereMonth('order_date', now()->month);
            })
            ->whereYear('created_at', now()->year)
            ->sum('total');

        $monthlyApptRevenue = (float) Appointment::join('services', 'appointments.service_id', '=', 'services.id')
            ->where('appointments.status', AppointmentStatus::Completed)
            ->whereMonth('appointments.appointment_at', now()->month)
            ->whereYear('appointments.appointment_at', now()->year)
            ->sum('services.price');

        $monthlyRevenue = $monthlyOrderRevenue + $monthlyApptRevenue;

        // 3. Compute Chart Data for 3 Timeframes (Week, Month, Year)

        // --- A. Weekly Trend (7 Days of Current Week) ---
        $startOfWeek = now()->startOfWeek(); // Monday
        $chartWeekLabels = [];
        $chartWeekData = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $chartWeekLabels[] = $date->format('D (M d)');

            $orderSum = (float) Order::where('payment_status', PaymentStatus::Paid)
                ->whereDate('created_at', $date->toDateString())
                ->sum('total');

            $apptSum = (float) Appointment::join('services', 'appointments.service_id', '=', 'services.id')
                ->where('appointments.status', AppointmentStatus::Completed)
                ->whereDate('appointments.appointment_at', $date->toDateString())
                ->sum('services.price');

            $chartWeekData[] = $orderSum + $apptSum;
        }

        // --- B. Monthly Trend (Days of Current Month) ---
        $daysInMonth = now()->daysInMonth;
        $chartMonthLabels = [];
        $chartMonthData = [];

        $salesByDay = Order::where('payment_status', PaymentStatus::Paid)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->selectRaw('DAY(created_at) as day, SUM(total) as total')
            ->groupBy('day')
            ->pluck('total', 'day')
            ->toArray();

        $apptsByDay = Appointment::join('services', 'appointments.service_id', '=', 'services.id')
            ->where('appointments.status', AppointmentStatus::Completed)
            ->whereYear('appointments.appointment_at', now()->year)
            ->whereMonth('appointments.appointment_at', now()->month)
            ->selectRaw('DAY(appointments.appointment_at) as day, SUM(services.price) as total')
            ->groupBy('day')
            ->pluck('total', 'day')
            ->toArray();

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $chartMonthLabels[] = now()->format('M').' '.$d;
            $chartMonthData[] = (float) ($salesByDay[$d] ?? 0) + (float) ($apptsByDay[$d] ?? 0);
        }

        // --- C. Yearly Trend (12 Months of Current Year) ---
        $chartYearLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $salesByMonth = Order::where('payment_status', PaymentStatus::Paid)
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

        $chartYearData = [];
        for ($m = 1; $m <= 12; $m++) {
            $chartYearData[] = (float) ($salesByMonth[$m] ?? 0) + (float) ($apptsByMonth[$m] ?? 0);
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
            'chartWeekLabels' => $chartWeekLabels,
            'chartWeekData' => $chartWeekData,
            'chartMonthLabels' => $chartMonthLabels,
            'chartMonthData' => $chartMonthData,
            'chartYearLabels' => $chartYearLabels,
            'chartYearData' => $chartYearData,
            'appointmentStatusesChart' => $appointmentStatusesChart,
        ]);
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()?->role === Role::Admin, 403);
    }
}
