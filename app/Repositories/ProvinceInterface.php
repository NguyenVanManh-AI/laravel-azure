<?php

namespace App\Repositories;

/**
 * Interface ExampleRepository.
 */
interface ProvinceInterface extends RepositoryInterface
{
    public static function getProvince($filter);
}
