<?php

namespace App\Repositories;

/**
 * Interface ExampleRepository.
 */
interface AdminInterface extends RepositoryInterface
{
    public function getAdmin();

    public static function findAdminByEmail($email);

    public function findAdminById($id);

    public function findAdminByTokenVerifyEmail($token);

    public function createAdmin($data);

    public static function updateAdmin($id, $data);

    public static function searchAdmin($filter);
}
