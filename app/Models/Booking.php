<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'package_date_id',
        'room_type',
        'total_price',
        'booking_code',
        'jumlah_jamaah',
        'customer_name',
        'customer_email',
        'customer_phone',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'nama_darurat',
        'nomor_darurat',
        'status',
        'payment_status',
        'payment_proof',
        'payment_method',
        'payment_date',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function packageDate()
    {
        return $this->belongsTo(PackageDate::class);
    }

    public function getFormattedTotalPriceAttribute()
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    public function getFormattedStatusAttribute()
    {
        $statuses = [
            'pending' => 'Menunggu',
            'confirmed' => 'Dikonfirmasi',
            'cancelled' => 'Dibatalkan',
            'completed' => 'Selesai',
        ];
        
        return $statuses[$this->status] ?? $this->status;
    }

    public function getFormattedPaymentStatusAttribute()
    {
        $statuses = [
            'unpaid' => 'Belum Bayar',
            'pending' => 'Menunggu Verifikasi',
            'paid' => 'Lunas',
            'failed' => 'Gagal',
        ];
        
        return $statuses[$this->payment_status] ?? $this->payment_status;
    }
}