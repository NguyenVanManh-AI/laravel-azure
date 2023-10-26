<?php

namespace App\Repositories;

use App\Models\Article;
use Illuminate\Support\Facades\DB;
use Throwable;

class ArticleRepository extends BaseRepository implements ArticleInterface
{
    public function getModel()
    {
        return Article::class;
    }

    public static function getArticle($filter)
    {
        $filter = (object) $filter;
        $data = (new self)->model
            ->when(!empty($filter->id_category), function ($q) use ($filter) {
                $q->where('id_category', $filter->id_category);
            })
            ->when(!empty($filter->list_id), function ($q) use ($filter) {
                $q->whereIn('id_category', $filter->list_id);
            });

        return $data;
    }

    public static function updateArticle($result, $data)
    {
        DB::beginTransaction();
        try {
            $result->update($data);
            DB::commit();

            return $result;
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    public static function createArticle($data)
    {
        DB::beginTransaction();
        try {
            $new = (new self)->model->create($data);
            DB::commit();

            return $new;
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    public static function findById($id)
    {
        return (new self)->model->find($id);
    }

    public static function getRawArticle($filter)
    {
        $filter = (object) $filter;
        $data = (new self)->model->selectRaw('articles.*, categories.*, articles.id AS id_article, 
        articles.thumbnail AS thumbnail_article, categories.thumbnail AS thumbnail_categorie, categories.id AS id_category, 
        articles.search_number AS search_number_article,
        articles.created_at AS created_at_article, categories.created_at AS created_at_category,
        articles.updated_at AS updated_at_article, categories.updated_at AS updated_at_category,
        users.name as name_user,users.role as role_user,
        categories.name as name_category')
            ->leftJoin('categories', 'articles.id_category', '=', 'categories.id')
            ->leftJoin('users', 'articles.id_user', '=', 'users.id')
            ->when(!empty($filter->id), function ($query) use ($filter) {
                return $query->where('articles.id', $filter->id);
            })

        // delete many
            ->when(!empty($filter->list_id), function ($q) use ($filter) {
                $q->whereIn('articles.id', $filter->list_id);
            })
            ->when(!empty($filter->id_user), function ($query) use ($filter) {
                if ($filter->id_user === 'admin') {
                    $query->where('articles.id_user', null);
                } else {
                    $query->where('articles.id_user', $filter->id_user);
                }
            });

        return $data;
    }

    public static function searchAll($filter)
    {
        // leftjoin để khi mà id_category trong articles null thì vẫn kết hợp với bản categories để lấy ra
        $filter = (object) $filter;
        $data = (new self)->model->selectRaw('articles.*, categories.*, articles.id AS id_article, 
            articles.thumbnail AS thumbnail_article, categories.thumbnail AS thumbnail_categorie, categories.id AS id_category, 
            articles.search_number AS search_number_article,
            articles.created_at AS created_at_article, categories.created_at AS created_at_category,
            articles.updated_at AS updated_at_article, categories.updated_at AS updated_at_category,
            users.name as name_user,users.role as role_user,users.avatar as avatar_user,
            categories.name as name_category')
            ->leftJoin('categories', 'articles.id_category', '=', 'categories.id')

            // left join thêm bảng user để lấy ta name và role
            ->leftJoin('users', 'articles.id_user', '=', 'users.id')

            // all
            ->when(!empty($filter->search), function ($q) use ($filter) {
                $q->where(function ($query) use ($filter) {
                    $query->where('title', 'LIKE', '%' . $filter->search . '%')
                        ->orWhere('content', 'LIKE', '%' . $filter->search . '%')
                        ->orWhere('users.name', 'LIKE', '%' . $filter->search . '%');
                });
            })
            ->when(!empty($filter->name_category), function ($query) use ($filter) {
                return $query->where('categories.name', '=', $filter->name_category);
            })
            ->when(!empty($filter->orderBy), function ($query) use ($filter) {
                $query->orderBy($filter->orderBy, $filter->orderDirection);
            })

            ->when(isset($filter->is_accept), function ($query) use ($filter) {
                if ($filter->is_accept === 'both') {
                } else {
                    $query->where('articles.is_accept', $filter->is_accept);
                }
            })
            ->when(isset($filter->is_show), function ($query) use ($filter) {
                if ($filter->is_show === 'both') {
                } else {
                    $query->where('articles.is_show', $filter->is_show);
                }
            })

            // detail
            ->when(!empty($filter->id), function ($query) use ($filter) {
                $query->where('articles.id', '=', $filter->id);
            })

            // admin manage all article
            ->when(!empty($filter->role), function ($query) use ($filter) {
                if ($filter->role === 'admin') {
                    $query->where('id_user', null);
                } else {
                    $query->where('users.role', $filter->role);
                }
            })

            // hospital
            ->when(!empty($filter->id_doctor_hospital), function ($query) use ($filter) {
                $query->whereIn('articles.id_user', $filter->id_doctor_hospital);
            })

            // doctor
            ->when(!empty($filter->id_user), function ($query) use ($filter) {
                $query->where('articles.id_user', $filter->id_user);
            });

        return $data;
    }
}
