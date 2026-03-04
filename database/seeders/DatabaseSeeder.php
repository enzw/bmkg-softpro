<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Alat;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'role' => 'admin',
                'password' => Hash::make('PelayananGeof2026!'),
                'npwp' => fake()->randomNumber(9, true),
                'no_identitas' => fake()->randomNumber(9, true),
                'pekerjaan' => 'admin',
                'pendidikan' => 's2',
                'telp' => fake()->unique()->e164PhoneNumber(),
                'alamat' => fake()->address(),
            ]
        );

        if (User::count() < 7) {
            User::factory(5)->create();
        }

        User::updateOrCreate(
            ['email' => 'nyala.ittaqi@example.com'],
            [
                'name' => 'Nihala Nyala Ittaqi',
                'role' => 'member',
                'password' => Hash::make('12345678'),
                'npwp' => fake()->randomNumber(9, true),
                'no_identitas' => fake()->unique()->randomNumber(9, true),
                'pekerjaan' => 'Pelajar',
                'pendidikan' => 'sma',
                'telp' => fake()->unique()->e164PhoneNumber(),
                'alamat' => fake()->address(),
            ]
        );

        Alat::updateOrCreate(
            ['slug' => 'proton-magnetometer'],
            [
                'nama' => 'Proton Magnetometer',
                'harga' => 400000,
                'deskripsi' => 'Per Unit / Per Hari',
            ]
        );

        Alat::updateOrCreate(
            ['slug' => 'portable-digital-short-period-seismograph'],
            [
                'nama' => 'Portable Digital Short Period Seismograph',
                'harga' => 640000,
                'deskripsi' => 'Per Unit / Per Hari',
            ]
        );

        Alat::updateOrCreate(
            ['slug' => 'gps-geodesi'],
            [
                'nama' => 'GPS Geodesi',
                'harga' => 270000,
                'deskripsi' => 'Per Unit / Per Hari',
            ]
        );

        $this->call([
            GuestUserSeeder::class,
        ]);
    }
}
