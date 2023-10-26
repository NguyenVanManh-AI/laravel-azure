<?php

namespace App\Repositories;

interface HospitalServiceInterface extends RepositoryInterface
{
    public static function findById($id);

    public static function getHospitalService($filter);

    public static function createHospitalService($data);

    public static function updateHospitalService($result, $data);

    public static function searchAll($filter);
}
