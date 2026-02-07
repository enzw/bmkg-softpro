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
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => Hash::make('PelayananGeof2026!'),

            'npwp' => fake()->randomNumber(9, true),
            'no_identitas' => fake()->randomNumber(9, true),
            'pekerjaan' => 'admin',
            'pendidikan' => array_rand(['sd', 'smp', 'sma', 'd3', 's1', 's2', 's3']),
            'telp' => fake()->unique()->e164PhoneNumber(),
            'alamat' => fake()->address(),
        ]);
        User::factory(5)->create();
        User::factory()->create([
            'name' => 'Nihala Nyala Ittaqi',
            'email' => 'nyala.ittaqi@example.com',
            'role' => 'member',
            'password' => Hash::make('12345678'),

            'npwp' => fake()->randomNumber(9, true),
            'no_identitas' => fake()->unique()->randomNumber(9, true),
            'pekerjaan' => 'Pelajar',
            'pendidikan' => array_rand(['sd', 'smp', 'sma', 'd3', 's1', 's2', 's3']),
            'telp' => fake()->unique()->e164PhoneNumber(),
            'alamat' => fake()->address(),
        ]);

        Alat::create([
            'nama' => 'Proton Magnetometer',
            'slug' => 'proton-magnetometer',
            'harga' => 400000,
            'deskripsi' => 'Per Unit / Per Hari',
        ]);

        Alat::create([
            'nama' => 'Portable Digital Short Period Seismograph',
            'slug' => 'portable-digital-short-period-seismograph',
            'harga' => 640000,
            'deskripsi' => 'Per Unit / Per Hari',
        ]);

        Alat::create([
            'nama' => 'GPS Geodesi',
            'slug' => 'gps-geodesi',
            'harga' => 270000,
            'deskripsi' => 'Per Unit / Per Hari',
        ]);
    }
}
