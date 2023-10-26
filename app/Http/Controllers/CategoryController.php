<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestCreateCategory;
use App\Http\Requests\RequestUpdateCategory;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function add(RequestCreateCategory $request)
    {
        return $this->categoryService->add($request);
    }

    public function edit(RequestUpdateCategory $request, $id)
    {
        return $this->categoryService->edit($request, $id);
    }

    public function delete($id)
    {
        return $this->categoryService->delete($id);
    }

    public function deleteMany(Request $request)
    {
        return $this->categoryService->deleteMany($request);
    }

    public function all(Request $request)
    {
        return $this->categoryService->all($request);
    }

    public function details(Request $request, $id)
    {
        return $this->categoryService->details($request, $id);
    }
}
