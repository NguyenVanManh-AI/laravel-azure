<?php

namespace App\Repositories;

interface DepartmentInterface extends RepositoryInterface
{
    public static function findById($id);

    public static function createDepartment($data);

    public static function updateDepartment($result, $data);

    public static function searchDepartment($filter);

    public static function getDepartment($filter);
}
