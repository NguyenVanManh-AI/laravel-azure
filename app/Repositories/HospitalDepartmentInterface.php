<?php

namespace App\Repositories;

interface HospitalDepartmentInterface extends RepositoryInterface
{
    public static function findById($id);

    public static function getHospitalDepartment($filter);

    public static function searchHospitalDepartment($filter);

    public static function createHosDepart($data);

    public static function updateHospitalDepartment($result, $data);
}
