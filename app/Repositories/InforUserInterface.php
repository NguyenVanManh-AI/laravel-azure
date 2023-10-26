<?php

namespace App\Repositories;

/**
 * Interface ExampleRepository.
 */
interface InforUserInterface extends RepositoryInterface
{
    public static function getInforUser($filter);

    public static function updateInforUser($id, $data);

    public static function createInforUser($data);
}
