<?php

namespace App\Repositories;

use App\Models\PasswordReset;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Class ExampleRepository.
 */
class PasswordResetRepository extends BaseRepository implements PasswordResetInterface
{
    public function getModel()
    {
        return PasswordReset::class;
    }

    public static function findPasswordReset($email, $isUser)
    {
        return (new self)->model->where('email', $email)->where('is_user', $isUser)->first();
    }

    public static function createToken($email, $isUser, $token)
    {
        DB::beginTransaction();
        try {
            (new self)->model->create([
                'email' => $email,
                'is_user' => $isUser,
                'token' => $token,
            ]);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    public static function findByToken($token)
    {
        return (new self)->model->where('token', $token)->first();
    }
}
