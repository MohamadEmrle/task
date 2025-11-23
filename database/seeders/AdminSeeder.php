<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [
                "name" => "mohamad",
                "email" => "mohamad@gmail.com",
                "password" => Hash::make("123123"),
            ],
            [
                "name" => "amer",
                "email" => "amer@gmail.com",
                "password" => Hash::make("123123"),
            ],
        ];
        foreach ($admins as $admin) {
            Admin::create($admin);
        }
    }
}
