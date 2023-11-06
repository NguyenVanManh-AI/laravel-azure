<?php

namespace Database\Seeders;

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
        $this->call([
            AdminsSeeder::class,
            CategoriesSeeder::class,
            DepartmentsSeeder::class,
            ProvincesSeeder::class,
            UsersSeeder::class,
            HospitalDepartmentsSeeder::class,
            HospitalServicesSeeder::class,
            HealthInsurancesSeeder::class,
            HealthInsuranceHospitalsSeeder::class,
            DoctorsSeeder::class,
            InforExtendDoctorsSeeder::class,
            ArticlesSeeder::class,
        ]);
    }
}
