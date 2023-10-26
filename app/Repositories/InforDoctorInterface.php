<?php

namespace App\Repositories;

/**
 * Interface ExampleRepository.
 */
interface InforDoctorInterface extends RepositoryInterface
{
    public static function getInforDoctor($filter);

    public static function createDoctor($data);

    public static function updateInforDoctor($id, $data);

    public static function updateResult($result, $data);
}
