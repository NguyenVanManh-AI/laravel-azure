<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Database\Factories\FakeImageFactory;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\Department;
use App\Models\HealthInsurance;
use App\Models\HealthInsuranceHospital;
use App\Models\HospitalDepartment;
use App\Models\User;
use App\Repositories\InforHospitalRepository;
use App\Repositories\InforUserRepository;
use App\Repositories\TimeWorkRepository;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class HealthInsuranceHospitalsSeeder extends Seeder
{
    public function run()
    {
        $hospitals = User::where('role','hospital')->get();
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
