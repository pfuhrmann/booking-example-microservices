<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    use HasUuids;

    protected $casts = [
        'time_arrival' => 'datetime:H:i',
        'time_departure' => 'datetime:H:i',
    ];

    protected $fillable = ['date_arrival', 'date_departure', 'time_arrival', 'time_departure', 'status'];

    protected $hidden = ['deleted_at'];
}
