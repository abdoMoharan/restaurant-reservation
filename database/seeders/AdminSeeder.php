<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('users')->delete();

        User::create([
            'name'=>'Admin',
            'email'=>'admin@gmail.com',
            'password'=>Hash::make('123'),
            'remember_token' => Str::random(10),
            'is_admin'=>1,
        ]);
    }
}
