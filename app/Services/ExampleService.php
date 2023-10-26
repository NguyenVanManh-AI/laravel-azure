<?php

namespace App\Services;

use App\Repositories\ExampleInterface;

class ExampleService
{
    protected ExampleInterface $exampleRepository;

    /**
     * __construct
     */
    public function __construct(
        ExampleInterface $exampleRepository
    ) {
        $this->exampleRepository = $exampleRepository;
    }

    /**
     * getList
     *
     * @return mixed
     */
    public function getList(object $filter)
    {
        return $this->exampleRepository->getExamples($filter);
    }

    /**
     * findExampleById
     *
     * @return mixed
     */
    public function findExampleById($id)
    {
        return $this->exampleRepository->find($id);
    }
}
