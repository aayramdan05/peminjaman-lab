<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'capacity', 'pc_specs', 'facilities', 'image_path', 'layout_image_path', 'layout_data', 'gallery_images'])]
class Lab extends Model
{
    protected $casts = [
        'layout_data' => 'array',
        'gallery_images' => 'array'
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
