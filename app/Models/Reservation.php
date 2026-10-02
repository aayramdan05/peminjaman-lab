<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['user_id', 'lab_id', 'additional_labs', 'start_time', 'end_time', 'status', 'reason', 'surat_path', 'pj_nama', 'pj_hp', 'schedules'])]
class Reservation extends Model
{
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'schedules' => 'array',
        'additional_labs' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }

    public function getConflictingReservations()
    {
        $myLabs = collect([$this->lab_id])->merge($this->additional_labs ?? [])->filter()->unique()->toArray();
        $mySchedules = is_array($this->schedules) ? $this->schedules : json_decode($this->schedules, true);
        
        if (empty($mySchedules)) return collect();

        $approved = self::where('status', 'approved')->where('id', '!=', $this->id)->get();
        $conflicts = collect();

        foreach ($approved as $r) {
            $rLabs = collect([$r->lab_id])->merge($r->additional_labs ?? [])->filter()->unique()->toArray();
            if (count(array_intersect($myLabs, $rLabs)) === 0) continue;

            $rSchedules = is_array($r->schedules) ? $r->schedules : json_decode($r->schedules, true);
            if (empty($rSchedules)) continue;

            $isOverlap = false;
            foreach ($mySchedules as $mSched) {
                $mStart = \Carbon\Carbon::parse($mSched['start_time']);
                $mEnd = \Carbon\Carbon::parse($mSched['end_time']);
                foreach ($rSchedules as $rSched) {
                    $rStart = \Carbon\Carbon::parse($rSched['start_time']);
                    $rEnd = \Carbon\Carbon::parse($rSched['end_time']);
                    
                    if ($mStart < $rEnd && $mEnd > $rStart) {
                        $isOverlap = true;
                        break 2;
                    }
                }
            }
            if ($isOverlap) {
                $conflicts->push($r);
            }
        }
        
        return $conflicts;
    }

    public function resolveConflicts($mode = 'all')
    {
        $myLabs = collect([$this->lab_id])->merge($this->additional_labs ?? [])->filter()->unique()->toArray();
        $mySchedules = is_array($this->schedules) ? $this->schedules : json_decode($this->schedules, true);
        
        if (empty($mySchedules)) return;

        $approved = self::where('status', 'approved')->where('id', '!=', $this->id)->get();

        foreach ($approved as $r) {
            $rLabs = collect([$r->lab_id])->merge($r->additional_labs ?? [])->filter()->unique()->toArray();
            $intersectingLabs = array_intersect($myLabs, $rLabs);
            
            if ($mode !== 'all' && count($intersectingLabs) === 0) {
                continue;
            }

            $rSchedules = is_array($r->schedules) ? $r->schedules : json_decode($r->schedules, true);
            if (empty($rSchedules)) continue;

            $survivingSchedules = [];
            $wasModified = false;
            
            foreach ($rSchedules as $rSched) {
                $rStart = \Carbon\Carbon::parse($rSched['start_time']);
                $rEnd = \Carbon\Carbon::parse($rSched['end_time']);
                
                $overlaps = false;
                foreach ($mySchedules as $mSched) {
                    $mStart = \Carbon\Carbon::parse($mSched['start_time']);
                    $mEnd = \Carbon\Carbon::parse($mSched['end_time']);
                    
                    if ($mStart < $rEnd && $mEnd > $rStart) {
                        $overlaps = true;
                        break;
                    }
                }
                
                if (!$overlaps) {
                    $survivingSchedules[] = $rSched;
                } else {
                    $wasModified = true;
                }
            }
            
            if ($wasModified) {
                $nonIntersectingLabs = array_diff($rLabs, $myLabs);
                
                if ($mode === 'partial' && count($nonIntersectingLabs) > 0) {
                    // Split the reservation
                    // Clone the old reservation for the non-intersecting labs, keeping the original schedules
                    $newLabId = array_values($nonIntersectingLabs)[0];
                    $newAdditionalLabs = array_slice(array_values($nonIntersectingLabs), 1);
                    
                    $splitRes = $r->replicate();
                    $splitRes->lab_id = $newLabId;
                    $splitRes->additional_labs = count($newAdditionalLabs) > 0 ? $newAdditionalLabs : null;
                    $splitRes->reason = $r->reason . "\n[Sistem: Dipecah karena sebagian lab ditimpa jadwal lain]";
                    $splitRes->save();
                    
                    // Update the original reservation to only contain the intersecting labs
                    $remainingLabId = array_values($intersectingLabs)[0];
                    $remainingAdditionalLabs = array_slice(array_values($intersectingLabs), 1);
                    
                    $r->lab_id = $remainingLabId;
                    $r->additional_labs = count($remainingAdditionalLabs) > 0 ? $remainingAdditionalLabs : null;
                }
                
                if (empty($survivingSchedules)) {
                    $r->update([
                        'status' => 'rejected', 
                        'reason' => $r->reason . "\n[Sistem: Seluruh jadwal dibatalkan karena digantikan oleh kegiatan prioritas tinggi]"
                    ]);
                } else {
                    $startTimes = array_column($survivingSchedules, 'start_time');
                    $endTimes = array_column($survivingSchedules, 'end_time');
                    sort($startTimes);
                    rsort($endTimes);
                    
                    $r->update([
                        'schedules' => $survivingSchedules,
                        'start_time' => $startTimes[0],
                        'end_time' => $endTimes[0],
                        'reason' => $r->reason . "\n[Sistem: Sebagian jadwal (yang bentrok) telah dibatalkan otomatis karena kegiatan prioritas tinggi]"
                    ]);
                }
            }
        }
    }
}
