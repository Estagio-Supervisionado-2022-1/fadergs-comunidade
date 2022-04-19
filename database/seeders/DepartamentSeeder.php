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

        $departaments = ['NuPJur', 'NuPSau', 'NuPTecInfo', 'NuPCont'];

        foreach ($departaments as $departament) {
            Departament::insert([
                'name'          => $departament,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

    }
}
