<?php

namespace App\Repositories;

use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

interface RepositoryInterface
{
    /**
     * Get all
     *
     * @return mixed
     */
    public function all();

    /**
     * Get one
     *
     * @return mixed
     */
    public function find($id);

    /**
     * Get one
     *
     * @return mixed
     */
    public function first();

    /**
     * Create
     *
     * @param array $attributes
     * @return mixed
     */
    public function create($attributes = []);

    /**
     * Update
     *
     * @param array $attributes
     * @return mixed
     */
    public function update($id, $attributes = []);

    /**
     * Delete
     *
     * @return mixed
     */
    public function delete($id);

    /**
     * Show
     *
     * @return mixed
     */
    public function show($id);

    /**
     * Get query
     *
     * @return Builder
     */
    public function getQuery();

    /**
     * Clear query
     *
     * @return Builder
     */
    public function clearQuery();

    /**
     * findBy
     *
     * @return mixed
     */
    public function findBy(array $filter);

    /**
     * Find one
     *
     * @return mixed
     */
    public function findOneBy(array $filter);

    /**
     * paginate
     *
     * @return LengthAwarePaginator|mixed
     */
    public function paginate($page);
}
