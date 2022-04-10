<?php

namespace Database\Seeders;

use App\Models\Departament;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DepartamentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $NUMBER_OF_RECORDS = 10;

        for ($i = 0; $i < $NUMBER_OF_RECORDS ; $i++){
            Departament::insert([
                'name'      => Str::random(10),
            ]);
        }
    }
}
