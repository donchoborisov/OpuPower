<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\ContactTableSeeder;

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

        $this->call([
            VoyagerDummyDatabaseSeeder::class,
            ContactTableSeeder::class,
            PagesTableSeeder::class,

        ]);
        $this->call(PagesTableSeeder::class);
    }
}
