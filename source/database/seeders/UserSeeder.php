<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return  void
     */
    public function run()
    {
        // Admin user
        User::create([
            'name' => 'Faruk Ojikutu',
            'email' => 'faruk@weldapp.co',
            'phone' => '07659465656',
            'password' => Hash::make('secret123!@'),
            'address' => null,
            'latitude' => null,
            'longitude' => null,
            'role' => 'admin'
        ]);
    }
}