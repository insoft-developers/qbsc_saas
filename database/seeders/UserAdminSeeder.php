<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            "name" => "Indra Rahdian",
            "email" => "irdn.software@gmail.com",
            "password" => '$2y$10$dts0y1cxsu9s79HUrEIZOu9L2NN2VeRWegW51zz27fF53gj2iX0lG',
            "level" => "super",
            
        ]);
    }
}
