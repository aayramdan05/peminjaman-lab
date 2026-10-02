<?php

namespace App\Http\Controllers;

use App\Models\Lab;
use Illuminate\Http\Request;

class LabController extends Controller
{
    public function index()
    {
        $labs = Lab::all();
        return view('welcome', compact('labs'));
    }

    public function show(Lab $lab)
    {
        return view('labs.show', compact('lab'));
    }

    public function edit(Lab $lab)
    {
        if (auth()->user()->role === 'user') abort(403);
        return view('labs.edit', compact('lab'));
    }

    public function book(Lab $lab)
    {
        $allLabs = Lab::where('id', '!=', $lab->id)->get();
        return view('labs.book', compact('lab', 'allLabs'));
    }

    public function update(Request $request, Lab $lab)
    {
        if (auth()->user()->role === 'user') abort(403);
        
        $validated = $request->validate([
            'capacity' => 'required|integer',
            'pc_specs' => 'nullable|string',
            'facilities' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'layout_data' => 'nullable|string', // JSON string
            'gallery.*' => 'image|max:2048' // multi upload
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('labs', 'public');
        }

        if ($request->has('layout_data')) {
            $validated['layout_data'] = json_decode($request->layout_data, true);
        }
        
        $currentGallery = $lab->gallery_images ?? [];
        
        if ($request->hasFile('gallery')) {
            foreach($request->file('gallery') as $file) {
                $path = $file->store('labs/gallery', 'public');
                $currentGallery[] = $path;
            }
        }
        
        if ($request->has('remove_gallery')) {
            foreach($request->remove_gallery as $indexToRemove) {
                if (isset($currentGallery[$indexToRemove])) {
                    // Optional: Storage::disk('public')->delete($currentGallery[$indexToRemove]);
                    unset($currentGallery[$indexToRemove]);
                }
            }
            $currentGallery = array_values($currentGallery); // reindex
        }
        
        $validated['gallery_images'] = $currentGallery;

        $lab->update($validated);
        return redirect()->route('admin.labs.index')->with('success', 'Data lab berhasil diperbarui.');
    }

    public function getReservations(Lab $lab)
    {
        $reservations = \App\Models\Reservation::where('status', 'approved')
            ->where(function($q) use ($lab) {
                $q->where('lab_id', $lab->id)
                  ->orWhereJsonContains('additional_labs', (string)$lab->id)
                  ->orWhereJsonContains('additional_labs', $lab->id);
            })->get();
        return response()->json($this->formatEvents($reservations, false, $lab->id));
    }

    public function getAllReservations()
    {
        $reservations = \App\Models\Reservation::with('lab')->where('status', 'approved')->get();
        return response()->json($this->formatEvents($reservations, true));
    }

    private function formatEvents($reservations, $includeLabName = false, $filterLabId = null)
    {
        $events = [];
        $allLabs = null;
        if ($includeLabName) {
            $allLabs = \App\Models\Lab::all()->keyBy('id');
        }

        foreach ($reservations as $res) {
            $labIds = [$res->lab_id];
            if (!empty($res->additional_labs) && is_array($res->additional_labs)) {
                $labIds = array_unique(array_merge($labIds, $res->additional_labs));
            }

            if ($filterLabId !== null) {
                $labIds = [$filterLabId];
            }

            foreach ($labIds as $lid) {
                $title = 'Telah Dipesan';
                if ($includeLabName && $allLabs && isset($allLabs[$lid])) {
                    $title = $allLabs[$lid]->name . ' (Terpakai)';
                }

                $extendedProps = [
                    'pj' => $res->pj_nama ?: ($res->user ? $res->user->name : '-'),
                    'reason' => $res->reason ?: '-',
                ];

                if (!empty($res->schedules)) {
                    $schedules = is_array($res->schedules) ? $res->schedules : json_decode($res->schedules, true);
                    foreach ($schedules as $sched) {
                        $extendedProps['time'] = \Carbon\Carbon::parse($sched['start_time'])->format('H:i') . ' - ' . \Carbon\Carbon::parse($sched['end_time'])->format('H:i');
                        $events[] = [
                            'title' => $title,
                            'start' => $sched['start_time'],
                            'end' => $sched['end_time'],
                            'color' => '#EA580C',
                            'display' => 'block',
                            'extendedProps' => $extendedProps
                        ];
                    }
                } else {
                    // Fallback for old data
                    $extendedProps['time'] = $res->start_time->format('H:i') . ' - ' . $res->end_time->format('H:i');
                    $events[] = [
                        'title' => $title,
                        'start' => $res->start_time->format('Y-m-d\TH:i:s'),
                        'end' => $res->end_time->format('Y-m-d\TH:i:s'),
                        'color' => '#EA580C',
                        'display' => 'block',
                        'extendedProps' => $extendedProps
                    ];
                }
            }
        }
        return $events;
    }
}
