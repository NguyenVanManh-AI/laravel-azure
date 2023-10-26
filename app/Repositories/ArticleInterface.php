<?php

namespace App\Repositories;

/**
 * Interface ExampleRepository.
 */
interface ArticleInterface extends RepositoryInterface
{
    public static function getArticle($filter);

    public static function findById($id);

    public static function getRawArticle($filter);

    public static function createArticle($data);

    public static function updateArticle($result, $data);

    public static function searchAll($filter);
}
