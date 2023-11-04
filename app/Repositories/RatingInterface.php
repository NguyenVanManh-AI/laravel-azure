<?php

namespace App\Repositories;

interface RatingInterface extends RepositoryInterface
{
    public static function findById($id);

    public static function searchRating($filter);

    public static function getRating($filter);
}
