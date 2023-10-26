<?php

namespace App\Repositories;

/**
 * Interface ExampleRepository.
 */
interface UserInterface extends RepositoryInterface
{
    public static function getUser();

    public static function findUserByEmail($email);

    public static function findUserById($id);

    public static function updateUser($id, $data);

    public function findUserByTokenVerifyEmail($token);

    public static function findUser($filter);

    public static function createUser($data);

    public static function searchUser($filter);

    public static function doctorOfHospital($filter);
}
