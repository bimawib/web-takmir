<?php

namespace Database\Seeders;

use App\Models\AgendaDetail;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AgendaDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AgendaDetail::factory()->count(13)->create();
    }
}
