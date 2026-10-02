<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Reservation;

class ReservationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lab_id' => 'required|exists:labs,id',
            'additional_labs' => 'nullable|array',
            'additional_labs.*' => 'exists:labs,id',
            'schedules' => 'required|array|min:1',
            'schedules.*.start_time' => 'required|date',
            'schedules.*.end_time' => 'required|date|after:schedules.*.start_time',
            'reason' => 'required|string|max:1000',
            'pj_nama' => 'required|string|max:255',
            'pj_hp' => 'required|string|max:20',
            'surat' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $userId = auth()->id();
        $suratPath = null;
        
        if ($request->hasFile('surat')) {
            $suratPath = $request->file('surat')->store('surat_peminjaman', 'public');
        }
        
        $schedules = $request->schedules;
        
        // Find the absolute start and end time for sorting purposes
        $startTimes = array_column($schedules, 'start_time');
        $endTimes = array_column($schedules, 'end_time');
        
        sort($startTimes);
        rsort($endTimes);
        
        Reservation::create([
            'user_id' => $userId,
            'lab_id' => $validated['lab_id'],
            'additional_labs' => !empty($validated['additional_labs']) ? $validated['additional_labs'] : null,
            'start_time' => $startTimes[0],
            'end_time' => $endTimes[0],
            'reason' => $validated['reason'],
            'pj_nama' => $validated['pj_nama'],
            'pj_hp' => $validated['pj_hp'],
            'surat_path' => $suratPath,
            'schedules' => $schedules,
            'status' => 'pending'
        ]);

        return redirect()->route('dashboard')->with('success', 'Pengajuan peminjaman berhasil dibuat. Silakan tunggu konfirmasi operator.');
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        // Pastikan hanya admin / operator yang bisa update (Bisa juga dipindah ke Middleware)
        if (auth()->user()->role === 'user') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'confirmed_replace' => 'nullable|boolean',
            'replace_mode' => 'nullable|in:all,partial'
        ]);

        if ($validated['status'] === 'approved') {
            $conflicts = $reservation->getConflictingReservations();
            if ($conflicts->count() > 0) {
                if (empty($validated['confirmed_replace'])) {
                    return redirect()->back()->with('conflict_confirm', [
                        'id' => $reservation->id,
                        'count' => $conflicts->count(),
                        'action' => route('reservations.updateStatus', $reservation->id),
                        'method' => 'PATCH'
                    ]);
                } else {
                    $reservation->resolveConflicts($request->replace_mode ?? 'all');
                }
            }
        }

        $reservation->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Status peminjaman berhasil diperbarui.');
    }

    public function edit(Reservation $reservation)
    {
        if (auth()->user()->role === 'user') abort(403);
        $labs = \App\Models\Lab::all();
        return view('admin.schedules.edit', compact('reservation', 'labs'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        if (auth()->user()->role === 'user') abort(403);
        
        $validated = $request->validate([
            'lab_id' => 'required|exists:labs,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'reason' => 'required|string|max:1000',
            'pj_nama' => 'required|string|max:255',
            'pj_hp' => 'required|string|max:20',
            'status' => 'required|in:pending,approved,rejected',
        ]);
        
        $reservation->update($validated);
        
        if ($validated['status'] === 'approved') {
            $conflicts = $reservation->getConflictingReservations();
            if ($conflicts->count() > 0) {
                if (empty($request->confirmed_replace)) {
                    return redirect()->back()->with('conflict_confirm', [
                        'id' => $reservation->id,
                        'count' => $conflicts->count(),
                        'action' => route('reservations.update', $reservation->id),
                        'method' => 'PUT',
                        'data' => $request->all() // store data to resubmit
                    ]);
                } else {
                    $reservation->resolveConflicts($request->replace_mode ?? 'all');
                }
            }
        }
        
        return redirect()->route('admin.schedules.index')->with('success', 'Data booking berhasil diperbarui.');
    }

    public function create()
    {
        if (auth()->user()->role === 'user') abort(403);
        $labs = \App\Models\Lab::all();
        return view('admin.schedules.create', compact('labs'));
    }

    public function adminStore(Request $request)
    {
        if (auth()->user()->role === 'user') abort(403);

        $validated = $request->validate([
            'lab_id' => 'required|exists:labs,id',
            'additional_labs' => 'nullable|array',
            'additional_labs.*' => 'exists:labs,id',
            'schedules' => 'required|array|min:1',
            'schedules.*.start_time' => 'required|date',
            'schedules.*.end_time' => 'required|date|after:schedules.*.start_time',
            'reason' => 'required|string|max:1000',
            'pj_nama' => 'required|string|max:255',
            'pj_hp' => 'required|string|max:20',
            'status' => 'required|in:pending,approved,rejected',
            'surat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $userId = auth()->id();
        $suratPath = null;
        
        if ($request->hasFile('surat')) {
            $suratPath = $request->file('surat')->store('surat_peminjaman', 'public');
        }
        
        $schedules = $request->schedules;
        
        $startTimes = array_column($schedules, 'start_time');
        $endTimes = array_column($schedules, 'end_time');
        
        sort($startTimes);
        rsort($endTimes);
        
        $res = Reservation::create([
            'user_id' => $userId,
            'lab_id' => $validated['lab_id'],
            'additional_labs' => !empty($validated['additional_labs']) ? $validated['additional_labs'] : null,
            'start_time' => $startTimes[0],
            'end_time' => $endTimes[0],
            'reason' => $validated['reason'],
            'pj_nama' => $validated['pj_nama'],
            'pj_hp' => $validated['pj_hp'],
            'surat_path' => $suratPath,
            'schedules' => $schedules,
            'status' => $validated['status']
        ]);

        if ($validated['status'] === 'approved') {
            $conflicts = $res->getConflictingReservations();
            if ($conflicts->count() > 0) {
                if (empty($request->confirmed_replace)) {
                    $res->update(['status' => 'pending']); // revert to pending
                    return redirect()->back()->with('conflict_confirm', [
                        'id' => $res->id,
                        'count' => $conflicts->count(),
                        'action' => route('reservations.updateStatus', $res->id), // Just approve the pending one
                        'method' => 'PATCH'
                    ]);
                } else {
                    $res->resolveConflicts($request->replace_mode ?? 'all');
                }
            }
        }

        return redirect()->route('admin.schedules.index')->with('success', 'Booking berhasil dibuat.');
    }

    public function destroy(Reservation $reservation)
    {
        if (auth()->user()->role === 'user') abort(403);
        $reservation->delete();
        return redirect()->back()->with('success', 'Data booking berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        if (auth()->user()->role === 'user') abort(403);
        
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:reservations,id'
        ]);

        Reservation::whereIn('id', $request->ids)->delete();
        
        return redirect()->back()->with('success', count($request->ids) . ' data booking berhasil dihapus sekaligus.');
    }
}
