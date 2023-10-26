<?php

namespace App\Services;

use App\Repositories\PasswordResetInterface;

class PasswordResetService
{
    protected PasswordResetInterface $passwordResetRepository;

    public function __construct(
        PasswordResetInterface $passwordResetRepository
    ) {
        $this->passwordResetRepository = $passwordResetRepository;
    }
}
