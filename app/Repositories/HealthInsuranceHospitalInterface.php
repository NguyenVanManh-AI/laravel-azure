<?php

namespace App\Repositories;

/**
 * Interface ExampleRepository.
 */
interface HealthInsuranceHospitalInterface extends RepositoryInterface
{
    public static function getHealInsurHos($filter);

    public static function findById($id);

    public static function createHealInsurHos($data);

    public static function searchHealInsurHos($filter);
}
