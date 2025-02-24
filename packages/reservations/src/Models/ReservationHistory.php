<?php

namespace Reservation\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Vehicle\Models\Vehicle;
use Reservation\Models\Reservation;

class ReservationHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'reservation_id',
        'vehicle_id',
        'no_of_days',
        'rate',
        'total_amount',
        'commission',
        'entry_date',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}

