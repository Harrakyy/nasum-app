<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'duration_days',
        'type',
        'double_price',
        'triple_price',
        'quad_price',
        'airline',
        'hotel_madinah',
        'hotel_makkah',
        'facilities',
        'image_url',
        'is_active',
    ];

    protected $casts = [
        'facilities' => 'array',
        'is_active' => 'boolean',
        'double_price' => 'decimal:2',
        'triple_price' => 'decimal:2',
        'quad_price' => 'decimal:2',
    ];

    public function packageDates()
    {
        return $this->hasMany(PackageDate::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getPriceByRoomType($roomType)
    {
        switch ($roomType) {
            case 'double':
                return $this->double_price;
            case 'triple':
                return $this->triple_price;
            case 'quad':
                return $this->quad_price;
            default:
                return $this->double_price;
        }
    }

    public function getFormattedDoublePriceAttribute()
    {
        return 'Rp ' . number_format($this->double_price, 0, ',', '.');
    }

    public function getFormattedTriplePriceAttribute()
    {
        return 'Rp ' . number_format($this->triple_price, 0, ',', '.');
    }

    public function getFormattedQuadPriceAttribute()
    {
        return 'Rp ' . number_format($this->quad_price, 0, ',', '.');
    }
}