<?php

namespace App\Repositories;

interface WorkScheduleInterface extends RepositoryInterface
{
    public static function findById($id);

    public static function createWorkSchedule($data);

    public static function getWorkSchedule($filter);

    public static function searchWorkSchedule($filter);
}
