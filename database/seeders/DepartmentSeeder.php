<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Electronics', 
                'slug' => 'electronics', 
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Fashion', 
                'slug' => 'fashion', 
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Home, Garden & Kitchen', 
                'slug' => Str::slug('home', 'garden & kitchen'), 
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Sports & Outdoors', 
                'slug' => Str::slug('sports','Outdoors'), 
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('departments')->insert($departments);
    }
}
