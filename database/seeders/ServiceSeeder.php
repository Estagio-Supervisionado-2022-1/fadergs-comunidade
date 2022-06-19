<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $NUMBER_OF_RECORDS = 10;

        for ($i = 1; $i <= $NUMBER_OF_RECORDS ; $i++){
            Service::insert([
                'name' => 'serviço '.$i,
                'duration' => '00:30',
                'description' => 'Lorem ipsum description',
                'departament_id' => rand(1,5), 
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
