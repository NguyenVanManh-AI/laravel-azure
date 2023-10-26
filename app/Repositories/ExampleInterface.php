<?php

namespace App\Repositories;

/**
 * Interface ExampleRepository.
 */
interface ExampleInterface extends RepositoryInterface
{
    public function getExamples($filter);
}
