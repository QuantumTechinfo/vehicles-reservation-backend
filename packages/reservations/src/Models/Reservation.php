<?php

namespace Reservation\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Reservation\Models\ReservationHistory;
use User;

class Reservation extends Model
{
    use HasFactory;
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'from_location',
        'to_location',
        'start_time',
        'end_time',
        'ride_option',
        'client_name',
        'client_phone',
        'client_email',
        'description',
        'busID',
        'status'
    ];
    public function history()
    {
        return $this->hasOne(ReservationHistory::class);
    }
}

