<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestCreateArticle;
use App\Http\Requests\RequestUpdateArticle;
use App\Services\ArticleService;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    protected ArticleService $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    public function add(RequestCreateArticle $request)
    {
        return $this->articleService->add($request);
    }

    public function edit(RequestUpdateArticle $request, $id)
    {
        return $this->articleService->edit($request, $id);
    }

    public function hideShow(Request $request, $id)
    {
        return $this->articleService->hideShow($request, $id);
    }

    public function changeAccept(Request $request, $id)
    {
        return $this->articleService->changeAccept($request, $id);
    }

    public function delete($id)
    {
        return $this->articleService->delete($id);
    }

    public function deleteMany(Request $request)
    {
        return $this->articleService->deleteMany($request);
    }

    public function adminManage(Request $request)
    {
        return $this->articleService->adminManage($request);
    }

    public function articleOfHospital(Request $request)
    {
        return $this->articleService->articleOfHospital($request);
    }

    public function articleOfDoctor(Request $request)
    {
        return $this->articleService->articleOfDoctor($request);
    }

    public function articleHome(Request $request)
    {
        return $this->articleService->articleHome($request);
    }

    public function details(Request $request, $id)
    {
        return $this->articleService->details($request, $id);
    }

    public function detailPrivate(Request $request, $id)
    {
        return $this->articleService->detailPrivate($request, $id);
    }
}
