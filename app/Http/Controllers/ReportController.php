<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Monthly statistics for current year
        $currentYear = Carbon::now()->year;
        
        // Get all reservations for the current year
        $reservations = Reservation::whereYear('created_at', $currentYear)->get();
        
        $monthlyStats = $reservations->groupBy(function($item) {
            return $item->created_at->format('n'); // 'n' returns month without leading zero (1-12)
        });

        // Prepare data for Chart.js
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $chartData = [
            'labels' => $months,
            'total' => array_fill(0, 12, 0),
            'approved' => array_fill(0, 12, 0),
            'rejected' => array_fill(0, 12, 0),
        ];

        foreach ($monthlyStats as $monthNum => $monthReservations) {
            $index = $monthNum - 1; // 0-based index
            $chartData['total'][$index] = $monthReservations->count();
            $chartData['approved'][$index] = $monthReservations->where('status', 'approved')->count();
            $chartData['rejected'][$index] = $monthReservations->where('status', 'rejected')->count();
        }

        // Most booked labs
        $topLabs = Reservation::select('lab_id', DB::raw('COUNT(*) as total_bookings'))
            ->where('status', 'approved')
            ->groupBy('lab_id')
            ->orderByDesc('total_bookings')
            ->with('lab')
            ->limit(5)
            ->get();

        return view('admin.reports.index', compact('chartData', 'topLabs', 'currentYear'));
    }
}
