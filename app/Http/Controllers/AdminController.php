<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lab;
use App\Models\User;

class AdminController extends Controller
{
    public function labsIndex()
    {
        $labs = Lab::all();
        return view('admin.labs.index', compact('labs'));
    }

    public function schedulesIndex()
    {
        $reservations = \App\Models\Reservation::with(['lab', 'user'])->orderBy('created_at', 'desc')->get();
        $allLabs = \App\Models\Lab::all()->keyBy('id');
        return view('admin.schedules.index', compact('reservations', 'allLabs'));
    }

    public function usersIndex()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate(['role' => 'required|in:user,operator,admin']);
        
        if ($user->id === auth()->id()) {
            return back()->withErrors(['msg' => 'Tidak dapat mengubah peran Anda sendiri.']);
        }

        $user->update(['role' => $request->role]);
        return back()->with('success', 'Peran pengguna berhasil diperbarui.');
    }
}
