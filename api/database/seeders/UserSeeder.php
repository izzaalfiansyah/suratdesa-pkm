<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            'username' => 'superadmin',
            'password' => Hash::make('superadmin'),
            'nama' => 'Muhammad Izza Alfiansyah',
            'nomor_telepon' => '081231921351',
            'alamat' => 'Dusun Karanganyar',
            'role' => '1',
        ]);
    }
}
