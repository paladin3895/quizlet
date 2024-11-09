<?php

namespace Database\Seeders;

use App\Models\QuizCountry;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Rinvex\Country\Country;
use Rinvex\Country\CountryLoader;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        collect(CountryLoader::countries(false, true))
            ->each(function (Country $country) {
                QuizCountry::create([
                    'name' => $country->getName(),
                    'code' => $country->getIsoAlpha3(),
                    'flag' => $country->getFlag(),
                ]);
            });
    }
}
