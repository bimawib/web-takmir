<?php

namespace Database\Seeders;

use App\Models\Found;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FoundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Found::factory()->count(13)->create();
    }
}
