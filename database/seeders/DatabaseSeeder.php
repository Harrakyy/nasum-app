<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Package;
use App\Models\PackageDate;
use App\Models\Booking;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ======================
        // ADMIN
        // ======================
        $admin = User::updateOrCreate(
            ['email' => 'admin@nasrotulummah.com'],
            [
                'name' => 'Admin Nasrotul Ummah',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '085710615365'
            ]
        );

        // ======================
        // USER
        // ======================
        $user = User::updateOrCreate(
            ['email' => 'syafrinamaulana@gmail.com'],
            [
                'name' => 'Syafrina Maulana',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'phone' => '081234567890',
                'address' => 'Jl. Contoh No. 123, Jakarta',
                'emergency_contact_name' => 'Ahmad',
                'emergency_contact_phone' => '081298765432'
            ]
        );

        // ======================
        // PACKAGES
        // ======================
        $packagesData = [
            [
                'name' => 'Umroh + Dubai',
                'slug' => 'umroh-dubai',
                'description' => 'Paket Umroh yang ditawarkan oleh Nasrotul Ummah adalah salah satu pilihan untuk melaksanakan Ibadah Umroh ke Tanah Suci sekaligus berwisata ke kota Dubai.',
                'duration_days' => 12,
                'type' => 'plus_dubai',
                'double_price' => 30000000,
                'triple_price' => 25000000,
                'quad_price' => 22000000,
                'airline' => 'Saudia Airlines',
                'hotel_madinah' => 'Concorde Al Khair 4**',
                'hotel_makkah' => 'Shuhada 5**',
                'facilities' => json_encode(['Flight Direct', 'City Tour Dubai', 'Buku Umroh Gratis']),
                'image_url' => 'umroh_dubai_potrait.png'
            ],
            [
                'name' => 'Umroh + Turki',
                'slug' => 'umroh-turki',
                'description' => 'Paket Umroh yang ditawarkan oleh Nasrotul Ummah adalah salah satu pilihan untuk melaksanakan Ibadah Umroh ke Tanah Suci sekaligus berwisata ke kota Turki.',
                'duration_days' => 12,
                'type' => 'plus_turki',
                'double_price' => 32000000,
                'triple_price' => 27000000,
                'quad_price' => 24000000,
                'airline' => 'Saudia Airlines',
                'hotel_madinah' => 'Concorde Al Khair 4**',
                'hotel_makkah' => 'Shuhada 5**',
                'facilities' => json_encode(['Flight Direct', 'City Tour Turki', 'Buku Umroh Gratis']),
                'image_url' => 'turki.jpeg'
            ],
            
            [ 'name' => 'Umroh Reguler', 
            'slug' => 'umroh-reguler', 
            'description' => 'Paket Umroh reguler dengan fokus utama pada ibadah umroh sesuai sunnah.', 
            'duration_days' => 9, 
            'type' => 'reguler', 
            'double_price' => 25000000, 
            'triple_price' => 20000000, 
            'quad_price' => 18000000, 
            'airline' => 'Saudia Airlines', 
            'hotel_madinah' => 'Concorde Al Khair 4**',
             'hotel_makkah' => 'Shuhada 5**', 
             'facilities' => json_encode(['Flight Direct', 'Kereta Cepat Haramain', 'Buku Umroh Gratis']), 
             'image_url' => 'umroh_reguler_potrait.png'
            ]
        ];

        foreach ($packagesData as $packageData) {
            $package = Package::firstOrCreate(
                ['slug' => $packageData['slug']],
                $packageData
            );

            // ======================
            // PACKAGE DATES
            // ======================
            $dates = [
                ['departure_date' => '2025-01-22', 'display_date' => '22 Januari 2025'],
                ['departure_date' => '2025-02-15', 'display_date' => '15 Februari 2025'],
                [ 'departure_date' => '2025-03-10', 'display_date' => '10 Maret 2025', 'available_slots' => 30 ]
            ];

            foreach ($dates as $date) {
                PackageDate::firstOrCreate(
                    [
                        'package_id' => $package->id,
                        'departure_date' => $date['departure_date']
                    ],
                    [
                        'display_date' => $date['display_date'],
                        'available_slots' => 30
                    ]
                );
            }
        }

        // ======================
        // AMBIL DATA UNTUK BOOKING
        // ======================
        $package = Package::first();
        $packageDate = PackageDate::where('package_id', $package->id)->first();

        // ======================
        // BOOKING
        // ======================
        Booking::updateOrCreate(
            [
                'user_id' => $user->id,
                'package_id' => $package->id,
                'package_date_id' => $packageDate->id,
            ],
            [
                'booking_code' => 'BOOK-' . strtoupper(Str::random(8)),
                'room_type' => 'triple',
                'jumlah_jamaah' => 1,
                'total_price' => 25000000,
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => $user->phone,
            ]
        );
    }
}
