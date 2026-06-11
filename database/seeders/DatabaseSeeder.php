<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // 1. Membuat Akun Demo Admin Default
        User::create([
            'name' => 'Admin Mediatama',
            'email' => 'admin@edustream.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now()
        ]);

        // 2. Membuat Akun Demo Customer Default
        User::create([
            'name' => 'Faizal Dwi Al Farizi',
            'email' => 'faizaldwialfarizi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'email_verified_at' => now()
        ]);

        // Memanggil seeder secara berurutan (Kategori wajib dibuat duluan)
        $this->call([
            CategorySeeder::class,
            VideoSeeder::class,
        ]);
    }
}
