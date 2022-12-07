<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Blog;
use App\Models\User;
use App\Models\Agenda;
use App\Models\AgendaDetail;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // $this->call([
        //     UserSeeder::class,
        //     BlogSeeder::class
        // ]);

        User::factory(13)->create();
        Blog::factory(13)->create();
        Agenda::factory(3)->create();
        AgendaDetail::factory(13)->create();
    }
}
