<?php

namespace App\Repositories;

use App\Models\Example;

/**
 * Class ExampleRepository.
 */
class ExampleRepository extends BaseRepository implements ExampleInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function getModel()
    {
        return Example::class;
    }

    /**
     * getExamples
     *
     * @return mixed
     */
    public function getExamples($filter)
    {
        $data = $this->model
            ->when(!empty($filter->email), function ($q) use ($filter) {
                $q->where('email', '=', "$filter->email");
            })
            ->when(!empty($filter->name), function ($q) use ($filter) {
                $q->where('name', 'like', "%$filter->name%");
            })
            ->when(!empty($filter->start_at), function ($query) use ($filter) {
                $query->whereDate('created_at', '>=', $filter->start_at);
            })
            ->when(!empty($filter->end_at), function ($query) use ($filter) {
                $query->whereDate('created_at', '<=', $filter->end_at);
            });

        return $data;
    }
}
