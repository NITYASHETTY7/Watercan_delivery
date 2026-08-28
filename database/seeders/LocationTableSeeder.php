<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Laratrust\Models\LaratrustRole;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class LocationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Country
        Country::unguard();
        $path = public_path('sql/countries.sql');
        \DB::unprepared(file_get_contents($path));
        $this->command->info('Countries table seeded!');

        // State
        State::unguard();
        $path = public_path('sql/states.sql');
        \DB::unprepared(file_get_contents($path));
        $this->command->info('States table seeded!');

        // // City
        City::unguard();
        $path = public_path('sql/cities.sql');
        \DB::unprepared(file_get_contents($path));
        $this->command->info('Cities table seeded!');
    }
}
