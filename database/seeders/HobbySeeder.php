<?php

namespace Database\Seeders;

use App\Models\Hobby;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HobbySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Hobby::insert([ 
            ['name' => 'Coding', 'created_at' => now(), 'updated_at' => now()], 
            ['name' => 'Painting', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Trees Planting', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
