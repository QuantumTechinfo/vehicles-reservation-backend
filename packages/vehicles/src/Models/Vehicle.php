<?php

namespace Vehicle\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Vehicle\Models\VehicleDetail;
class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'uploader_id',
        'vehicle_name',
        'vehicle_number',
        'vehicle_description',
        'blue_book',
        'drivers',
        'vehicle_images',
    ];

}
