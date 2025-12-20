<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'departure_date',
        'display_date',
        'available_slots',
        'is_available'
    ];

    protected $casts = [
        'departure_date' => 'date',
        'is_available' => 'boolean'
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getAvailableSlotsAttribute()
    {
        $booked = $this->bookings()->whereIn('status', ['confirmed', 'paid'])->count();
       return max(0, $this->attributes['available_slots'] - $booked);
    }
}