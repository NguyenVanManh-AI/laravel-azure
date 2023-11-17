<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\HospitalDepartment;
use App\Models\User;
use Illuminate\Database\Seeder;

class HospitalDepartmentsSeeder extends Seeder
{
    public function run()
    {
        $hospitals = User::where('role', 'hospital')->get();
        // $idDepartments = Department::all()->id; // sai
        $idDepartments = Department::pluck('id')->all(); // đúng (lấy ra mảng id)
        foreach ($hospitals as $index => $hospital) {
            for ($i = 0; $i < 10; $i++) {
                // $randomDepartmentId = $idDepartments[array_rand($idDepartments)];
                HospitalDepartment::create([
                    'id_department' => $idDepartments[$i],
                    'id_hospital' => $hospital->id,
                    // 'time_advise' => rand(1, 4) * 30,
                    'time_advise' => 30, // để cho bên workschedule dễ seed
                    'price' => rand(1, 60) * 50000,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
