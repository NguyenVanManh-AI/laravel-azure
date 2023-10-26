<?php

namespace App\Repositories;

/**
 * Interface ExampleRepository.
 */
interface PasswordResetInterface extends RepositoryInterface
{
    public static function findPasswordReset($email, $isUser);

    public static function createToken($email, $isUser, $token);

    public static function findByToken($token);
}
