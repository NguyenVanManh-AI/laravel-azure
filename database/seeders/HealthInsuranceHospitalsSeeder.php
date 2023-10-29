<?php

namespace Database\Seeders;

use App\Models\HealthInsurance;
use App\Models\HealthInsuranceHospital;
use App\Models\User;
use Illuminate\Database\Seeder;

class HealthInsuranceHospitalsSeeder extends Seeder
{
    public function run()
    {
        $hospitals = User::where('role', 'hospital')->get();
        $idHealthInsurances = HealthInsurance::pluck('id')->all();
        foreach ($hospitals as $index => $hospital) {
            for ($i = 0; $i <= 4; $i++) {
                // $randomHealthInsuranceId = $idHealthInsurances[array_rand($idHealthInsurances)];
                HealthInsuranceHospital::create([
                    'id_health_insurance' => $idHealthInsurances[$i],
                    'id_hospital' => $hospital->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
