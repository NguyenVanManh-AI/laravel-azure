<?php

namespace App\Services;

use App\Http\Requests\RequestCreateCategory;
use App\Http\Requests\RequestUpdateCategory;
use App\Models\Category;
use App\Repositories\ArticleRepository;
use App\Repositories\CategoryInterface;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Throwable;

class CategoryService
{
    protected CategoryInterface $categoryRepository;

    public function __construct(
        CategoryInterface $categoryRepository
    ) {
        $this->categoryRepository = $categoryRepository;
    }

    public function responseOK($status = 200, $data = null, $message = '')
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => $status,
        ], $status);
    }

    public function responseError($status = 400, $message = '')
    {
        return response()->json([
            'message' => $message,
            'status' => $status,
        ], $status);
    }

    public function saveAvatar(Request $request)
    {
        if ($request->hasFile('thumbnail')) {
            $image = $request->file('thumbnail');
            $filename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '_category_' . time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/image/thumbnail/categories/', $filename);

            return 'storage/image/thumbnail/categories/' . $filename;
        }
    }

    public function add(RequestCreateCategory $request)
    {
        try {
            $category = CategoryRepository::createCategory($request->all());
            $thumbnail = $this->saveAvatar($request);
            $data = [
                'thumbnail' => $thumbnail,
            ];
            $category = CategoryRepository::updateCategory($category->id, $data);

            return $this->responseOK(201, $category, 'Thêm danh mục thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function edit(RequestUpdateCategory $request, $id)
    {
        try {
            $category = CategoryRepository::getCategory(['id' => $id])->first();
            if ($request->hasFile('thumbnail')) {
                if ($category->thumbnail) {
                    File::delete($category->thumbnail);
                }
                $thumbnail = $this->saveAvatar($request);
                $data = array_merge($request->all(), ['thumbnail' => $thumbnail]);
                $category = CategoryRepository::updateCategory($category->id, $data);
            } else {
                $category = CategoryRepository::updateCategory($category->id, $request->all());
            }

            return $this->responseOK(200, $category, 'Cập nhật thông tin danh mục thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $category = CategoryRepository::getCategory(['id' => $id])->first();
            if ($category) {
                $article = ArticleRepository::getArticle(['id_category' => $id]);
                ArticleRepository::updateArticle($article, ['id_category' => null]);

                if ($category->thumbnail) {
                    File::delete($category->thumbnail);
                }
                $category->delete();

                return $this->responseOK(200, null, 'Xóa danh mục thành công !');
            } else {
                return $this->responseError(404, 'Không tìm thấy danh mục !');
            }
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function deleteMany(Request $request)
    {
        try {
            $list_id = $request->list_id;
            // dd($list_id); // vẫn là một mảng , nếu k lưu vào database thì k cần encode
            $categories = CategoryRepository::getCategory(['list_id' => $list_id])->get();
            if (!$categories->isEmpty()) { // hay
                $articles = ArticleRepository::getArticle(['list_id' => $list_id]);
                ArticleRepository::updateArticle($articles, ['id_category' => null]);
                foreach ($categories as $category) {
                    if ($category->thumbnail) {
                        // Kiểm tra và xóa tệp
                        if (File::delete($category->thumbnail)) {
                            $category->delete(); // xóa tửng bảng ghi cũng được
                        } else {
                            return $this->responseError(400, 'Lỗi khi xóa tệp liên quan đến danh mục.');
                        }
                    }
                }

                return $this->responseOK(200, null, 'Xóa các danh mục thành công!');
            } else {
                return $this->responseError(404, 'Không tìm thấy danh mục nào để xóa.');
            }
        } catch (Throwable $e) {
            // Ghi log lỗi hoặc trả về thông báo lỗi
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function all(Request $request)
    {
        try {
            $search = $request->search;
            $orderBy = 'id';
            $orderDirection = 'ASC';

            if ($request->sortlatest == 'true') {
                $orderBy = 'id';
                $orderDirection = 'DESC';
            }

            if ($request->sortname == 'true') {
                $orderBy = 'name';
                $orderDirection = ($request->sortlatest == 'true') ? 'DESC' : 'ASC';
            }

            $filter = (object) [
                'orderBy' => $orderBy,
                'orderDirection' => $orderDirection,
                'search' => $search,
            ];

            if (!(empty($request->paginate))) {// lấy cho category
                $categorys = CategoryRepository::searchCategory($filter)->paginate($request->paginate);
            } else {
                $categorys = CategoryRepository::getCategory($filter)->get();
            }

            return $this->responseOK(200, $categorys, 'Xem tất cả danh mục thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function details(Request $request, $id)
    {
        try {
            $category = CategoryRepository::getCategory(['id' => $id])->first();
            if ($category) {
                // search number
                $search_number = $category->search_number + 1;
                $category = CategoryRepository::updateResultCategory($category, ['search_number' => $search_number]);
                // search number

                return $this->responseOK(200, $category, 'Xem danh mục chi tiết thành công !');
            } else {
                return $this->responseError(404, 'Không tìm thấy danh mục !');
            }
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }
}
