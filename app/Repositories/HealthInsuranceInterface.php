<?php

namespace App\Repositories;

/**
 * Interface ExampleRepository.
 */
interface HealthInsuranceInterface extends RepositoryInterface
{
    public static function findById($id);

    public static function createHealthInsur($data);

    public static function updateHealthInsur($result, $data);

    public static function searchHealthInsur($filter);

    // public static function getHealthInsur($filter);
}
