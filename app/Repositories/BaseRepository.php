<?php

namespace App\Repositories;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;

abstract class BaseRepository implements RepositoryInterface
{
    protected $model;

    protected $query;

    /**
     * Constructor to bind model to repo
     *
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->setModel();
        $this->query = $this->model->newQuery();
    }

    /**
     * @return mixed
     */
    abstract public function getModel();

    /**
     *  Set the associated model
     *
     * @return void
     *
     * @throws BindingResolutionException
     */
    public function setModel()
    {
        $this->model = app()->make($this->getModel());
    }

    /**
     * Get all instances of model
     *
     * @return mixed
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * create a new record in the database
     *
     * @return mixed
     */
    public function create($attributes = [])
    {
        return $this->model->create($attributes);
    }

    /**
     * update record in the database
     *
     * @return mixed
     */
    public function update($id, $attributes = [])
    {
        $record = $this->find($id);

        return $record->update($attributes);
    }

    /**
     * remove record from the database
     *
     * @return mixed
     */
    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    /**
     * show the record with the given id
     *
     * @return mixed
     */
    public function show($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * find the record with the given id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * find the first record
     *
     * @return mixed
     */
    public function first()
    {
        return $this->model->first();
    }

    /**
     *  Eager load database relationships
     *
     * @return mixed
     */
    public function with($relations)
    {
        return $this->model->with($relations);
    }

    /**
     * getQuery
     *
     * @return Interfaces\Builder|Builder
     */
    public function getQuery()
    {
        return $this->query->getQuery();
    }

    /**
     * clearQuery
     *
     * @return Builder
     */
    public function clearQuery()
    {
        $this->query = $this->model->newQuery();

        return $this->query->getQuery();
    }

    /**
     * findBy
     *
     * @return mixed|null
     */
    public function findBy(array $filter)
    {
        $builder = $this->model->newQuery();
        foreach ($filter as $key => $val) {
            $builder->where($key, $val);
        }
        $find = $builder->get();

        return $find ? $find->toArray() : null;
    }

    /**
     * findOneBy
     *
     * @return array|mixed
     */
    public function findOneBy(array $filter)
    {
        $builder = $this->model->newQuery();
        foreach ($filter as $key => $val) {
            $builder->where($key, $val);
        }
        $data = $builder->first();

        return $data ? $data->toArray() : [];
    }

    /**
     * paginate
     *
     * @return LengthAwarePaginator|mixed
     */
    public function paginate($page)
    {
        return $this->query->paginate($page);
    }
}
