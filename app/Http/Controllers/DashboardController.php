<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $query = Reservation::query();
        if ($user->role === 'user') {
            $query->where('user_id', $user->id);
        }

        $stats = [
            'total' => (clone $query)->count(),
            'approved' => (clone $query)->where('status', 'approved')->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'rejected' => (clone $query)->where('status', 'rejected')->count(),
        ];

        if ($user->role === 'admin' || $user->role === 'operator') {
            $reservations = Reservation::with(['user', 'lab'])->orderBy('created_at', 'desc')->get();
        } else {
            $reservations = Reservation::with(['lab'])->where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        }

        $allLabs = \App\Models\Lab::all()->keyBy('id');

        return view('dashboard', compact('reservations', 'stats', 'allLabs'));
    }
}
