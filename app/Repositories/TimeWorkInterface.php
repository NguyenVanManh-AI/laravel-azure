<?php

namespace App\Repositories;

interface TimeWorkInterface extends RepositoryInterface
{
    public static function findById($id);

    public static function createTimeWork($data);

    public static function getTimeWork($filter);

    public static function updateTimeWork($result, $data);
}
