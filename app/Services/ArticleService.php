<?php

namespace App\Services;

use App\Http\Requests\RequestCreateArticle;
use App\Http\Requests\RequestUpdateArticle;
use App\Models\Category;
use App\Repositories\ArticleInterface;
use App\Repositories\ArticleRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\InforDoctorRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Throwable;

class ArticleService
{
    protected ArticleInterface $articleRepository;

    public function __construct(ArticleInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
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
            $filename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '_article_' . time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/image/thumbnail/articles/', $filename);

            return 'storage/image/thumbnail/articles/' . $filename;
        }
    }

    public function add(RequestCreateArticle $request)
    {
        try {
            $category = CategoryRepository::getCategory(['id' => $request->id_category])->first();
            if (empty($category)) {
                return $this->responseError(400, 'Danh mục không tồn tại !');
            }
            $article = $this->articleRepository->createArticle($request->all());
            $thumbnail = $this->saveAvatar($request);

            $id_user = null;
            $user = Auth::user();
            if (in_array($user->role, ['doctor', 'hospital'])) {
                $id_user = $user->id;
            }

            $is_accept = true;
            if ($user->role == 'doctor') {
                $is_accept = false;
            }
            $data = [
                'thumbnail' => $thumbnail,
                'is_accept' => $is_accept,
                'is_show' => true,
                'id_user' => $id_user,
            ];
            $article = $this->articleRepository->updateArticle($article, $data);

            return $this->responseOK(201, $article, 'Thêm bài viết thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function edit(RequestUpdateArticle $request, $id)
    {
        try {
            $user = Auth::user();
            $article = $this->articleRepository->findById($id);
            if (empty($article)) {
                return $this->responseError(400, 'Không tìm thấy bài viết !');
            }

            if (in_array($user->role, ['doctor', 'hospital']) && ($user->id != $article->id_user)) {
                return $this->responseError(403, 'Bạn không có quyền chỉnh sửa bài viết này !');
            }
            if (in_array($user->role, ['admin', 'superadmin', 'manager']) && ($article->id_user != null)) {
                return $this->responseError(403, 'Bạn không có quyền chỉnh sửa bài viết này !');
            }

            if ($request->hasFile('thumbnail')) {
                if ($article->thumbnail) {
                    File::delete($article->thumbnail);
                }
                $thumbnail = $this->saveAvatar($request);
                $data = array_merge($request->all(), ['thumbnail' => $thumbnail]);
                $article = $this->articleRepository->updateArticle($article, $data);
            } else {
                $request['thumbnail'] = $article->thumbnail;
                $article = $this->articleRepository->updateArticle($article, $request->all());
            }
            $article = $this->articleRepository->getRawArticle(['id' => $id])->first();

            return $this->responseOK(200, $article, 'Cập nhật bài viết thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $user = Auth::user();
            $article = $this->articleRepository->findById($id);
            if (empty($article)) {
                return $this->responseError(400, 'Không tìm thấy bài viết !');
            }

            if (in_array($user->role, ['doctor', 'hospital']) && ($user->id != $article->id_user)) {
                return $this->responseError(403, 'Bạn không có quyền xóa bài viết này !');
            }
            if (in_array($user->role, ['admin', 'superadmin', 'manager']) && ($article->id_user != null)) {
                return $this->responseError(403, 'Bạn không có quyền xóa bài viết này !');
            }
            if ($article->thumbnail) {
                File::delete($article->thumbnail);
            }
            $article->delete();

            return $this->responseOK(200, null, 'Xóa bài viết thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function deleteMany(Request $request)
    {
        try {
            $list_id = $request->list_id ?? [0];
            $user = Auth::user();
            $filter = [
                'list_id' => $list_id,
            ];
            if (in_array($user->role, ['doctor', 'hospital'])) {
                $filter['id_user'] = $user->id;
            } else {
                $filter['id_user'] = 'admin';
            }
            $articles = $this->articleRepository->getRawArticle($filter)->get();

            if (count($articles) > 0) {
                foreach ($articles as $article) {
                    File::delete($article->thumbnail_article);
                    $article = $this->articleRepository->findById($article->id_article)->delete();
                }
            } else {
                return $this->responseError(200, 'Không tìm thấy bài viết nào !');
            }

            return $this->responseOK(200, null, 'Xóa nhiều bài viết thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function hideShow(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $articlePrivate = $this->articleRepository->findById($id);
            if (empty($articlePrivate)) {
                return $this->responseError(400, 'Không tìm thấy bài viết !');
            }

            if (($user->role == 'doctor') && ($user->id != $articlePrivate->id_user)) {
                return $this->responseError(403, 'Bạn không có quyền thay đổi trạng thái hiển thị bài viết này !');
            }

            if ($user->role == 'hospital') {
                $doctors = InforDoctorRepository::getInforDoctor(['id_hospital' => $user->id])->get();
                $idDoctorHospitals = [];
                $idDoctorHospitals[] = $user->id;
                foreach ($doctors as $doctor) {
                    $idDoctorHospitals[] = $doctor->id_doctor;
                }
                if ((!in_array($articlePrivate->id_user, $idDoctorHospitals))) {
                    return $this->responseError(403, 'Bạn không có quyền thay đổi trạng thái hiển thị bài viết này !');
                }
            }

            $articlePrivate = $this->articleRepository->updateArticle($articlePrivate, ['is_show' => $request->is_show]);

            return $this->responseOK(200, $articlePrivate, 'Thay đổi trạng thái hiển thị của bài viết thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function changeAccept(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $articlePrivate = $this->articleRepository->findById($id);
            if (empty($articlePrivate)) {
                return $this->responseError(400, 'Không tìm thấy bài viết !');
            }

            $doctors = InforDoctorRepository::getInforDoctor(['id_hospital' => $user->id])->get();
            $idDoctorHospitals = [];
            $idDoctorHospitals[] = $user->id;
            foreach ($doctors as $doctor) {
                $idDoctorHospitals[] = $doctor->id_doctor;
            }

            if ((!in_array($articlePrivate->id_user, $idDoctorHospitals))) {
                return $this->responseError(403, 'Bạn không có quyền thay đổi trạng thái hiển thị bài viết này !');
            }

            $articlePrivate = $this->articleRepository->updateArticle($articlePrivate, ['is_accept' => $request->is_accept]);

            return $this->responseOK(200, $articlePrivate, 'Thay đổi trạng thái của bài viết thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    // admin manage all article
    public function adminManage(Request $request)
    {
        try {
            $orderBy = $request->typesort ?? 'articles.id';
            switch ($orderBy) {
                case 'name':
                    $orderBy = 'articles.title';
                    break;

                case 'new':
                    $orderBy = 'articles.id';
                    break;

                case 'search_number': // sắp xếp theo bài viết nổi bật
                    $orderBy = 'articles.search_number';
                    break;

                default:
                    $orderBy = 'articles.id';
                    break;
            }

            $orderDirection = $request->sortlatest ?? 'true';
            switch ($orderDirection) {
                case 'true':
                    $orderDirection = 'DESC';
                    break;

                default:
                    $orderDirection = 'ASC';
                    break;
            }

            $filter = (object) [
                'search' => $request->search ?? '',
                'name_category' => $request->name_category ?? '',
                'orderBy' => $orderBy,
                'orderDirection' => $orderDirection,
                'is_accept' => $request->is_accept ?? 'both',
                'is_show' => $request->is_show ?? 'both',
                'role' => $request->role,
            ];

            if (!(empty($request->paginate))) {
                $articles = $this->articleRepository->searchAll($filter)->paginate($request->paginate);
            } else {
                $articles = $this->articleRepository->searchAll($filter)->get();
            }

            return $this->responseOK(200, $articles, 'Xem tất cả bài viết thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    // hospital
    public function articleOfHospital(Request $request)
    {
        try {
            $user = UserRepository::findUserById(auth('user_api')->user()->id);
            if (empty($user)) {
                return $this->responseError(400, 'Không tìm thấy người dùng !');
            }

            $doctors = InforDoctorRepository::getInforDoctor(['id_hospital' => $user->id])->get();
            $idDoctorHospitals = [];
            $idDoctorHospitals[] = $user->id;
            foreach ($doctors as $doctor) {
                $idDoctorHospitals[] = $doctor->id_doctor;
            }

            $orderBy = $request->typesort ?? 'articles.id';
            switch ($orderBy) {
                case 'name':
                    $orderBy = 'articles.title';
                    break;

                case 'new':
                    $orderBy = 'articles.id';
                    break;

                case 'search_number': // sắp xếp theo bài viết nổi bật
                    $orderBy = 'articles.search_number';
                    break;

                default:
                    $orderBy = 'articles.id';
                    break;
            }

            $orderDirection = $request->sortlatest ?? 'true';
            switch ($orderDirection) {
                case 'true':
                    $orderDirection = 'DESC';
                    break;

                default:
                    $orderDirection = 'ASC';
                    break;
            }

            $filter = (object) [
                'search' => $request->search ?? '',
                'name_category' => $request->name_category ?? '',
                'orderBy' => $orderBy,
                'orderDirection' => $orderDirection,
                'is_accept' => $request->is_accept ?? 'both',
                'is_show' => $request->is_show ?? 'both',
                'id_doctor_hospital' => $idDoctorHospitals,
                'role' => $request->role,
            ];

            if (!(empty($request->paginate))) {
                $articles = $this->articleRepository->searchAll($filter)->paginate($request->paginate);
            } else {
                $articles = $this->articleRepository->searchAll($filter)->get();
            }

            return $this->responseOK(200, $articles, 'Xem tất cả bài viết thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    // doctor
    public function articleOfDoctor(Request $request)
    {
        try {
            $user = UserRepository::findUserById(auth('user_api')->user()->id);
            if (empty($user)) {
                return $this->responseError(400, 'Không tìm thấy người dùng !');
            }

            $orderBy = $request->typesort ?? 'articles.id';
            switch ($orderBy) {
                case 'name':
                    $orderBy = 'articles.title';
                    break;

                case 'new':
                    $orderBy = 'articles.id';
                    break;

                case 'search_number': // sắp xếp theo bài viết nổi bật
                    $orderBy = 'articles.search_number';
                    break;

                default:
                    $orderBy = 'articles.id';
                    break;
            }

            $orderDirection = $request->sortlatest ?? 'true';
            switch ($orderDirection) {
                case 'true':
                    $orderDirection = 'DESC';
                    break;

                default:
                    $orderDirection = 'ASC';
                    break;
            }

            $filter = (object) [
                'search' => $request->search ?? '',
                'name_category' => $request->name_category ?? '',
                'orderBy' => $orderBy,
                'orderDirection' => $orderDirection,
                'is_accept' => $request->is_accept ?? 'both',
                'is_show' => $request->is_show ?? 'both',
                'id_user' => auth('user_api')->user()->id,
            ];

            if (!(empty($request->paginate))) {
                $articles = $this->articleRepository->searchAll($filter)->paginate($request->paginate);
            } else {
                $articles = $this->articleRepository->searchAll($filter)->get();
            }

            return $this->responseOK(200, $articles, 'Xem tất cả bài viết thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    // home
    public function articleHome(Request $request)
    {
        try {
            $orderBy = $request->typesort ?? 'articles.id';
            switch ($orderBy) {
                case 'name':
                    $orderBy = 'articles.title';
                    break;

                case 'new':
                    $orderBy = 'articles.id';
                    break;

                case 'search_number': // sắp xếp theo bài viết nổi bật
                    $orderBy = 'articles.search_number';
                    break;

                default:
                    $orderBy = 'articles.id';
                    break;
            }

            $orderDirection = $request->sortlatest ?? 'true';
            switch ($orderDirection) {
                case 'true':
                    $orderDirection = 'DESC';
                    break;

                default:
                    $orderDirection = 'ASC';
                    break;
            }

            $filter = (object) [
                'search' => $request->search ?? '',
                'name_category' => $request->name_category ?? '',
                'orderBy' => $orderBy,
                'orderDirection' => $orderDirection,
                'is_accept' => 1,
                'is_show' => 1,
            ];

            if (!(empty($request->paginate))) {
                $articles = $this->articleRepository->searchAll($filter)->paginate($request->paginate);
            } else {
                $articles = $this->articleRepository->searchAll($filter)->get();
            }

            return $this->responseOK(200, $articles, 'Xem tất cả bài viết thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function details(Request $request, $id)
    {
        try {
            $filter = (object) [
                'id' => $id,
                'is_accept' => 1,
                'is_show' => 1,
            ];
            $article = $this->articleRepository->searchAll($filter)->first();
            if ($article) {
                // search number
                $_article = ArticleRepository::findById($id);
                $search_number = $article->search_number_article + 1;
                ArticleRepository::updateArticle($_article, ['search_number' => $search_number]);
                $article->search_number_article = $search_number;
                // search number

                // search number category
                if ($article->id_category) {
                    $category = Category::find($article->id_category);
                    $category->update(['search_number' => $category->search_number + 1]);
                }
                // search number category

                return $this->responseOK(200, $article, 'Xem bài viết chi tiết thành công !');
            } else {
                return $this->responseError(400, 'Không tìm thấy bài viết !');
            }
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function detailPrivate(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $articlePrivate = $this->articleRepository->findById($id);
            if (empty($articlePrivate)) {
                return $this->responseError(400, 'Không tìm thấy bài viết !');
            }

            if ($articlePrivate->is_accept != 1 || $articlePrivate->is_show != 1) {
                if (($user->role == 'doctor') && ($user->id != $articlePrivate->id_user)) {
                    return $this->responseError(403, 'Bạn không có quyền xem nó !');
                }

                if ($user->role == 'hospital') {
                    $doctors = InforDoctorRepository::getInforDoctor(['id_hospital' => $user->id])->get();
                    $idDoctorHospitals = [];
                    $idDoctorHospitals[] = $user->id;
                    foreach ($doctors as $doctor) {
                        $idDoctorHospitals[] = $doctor->id_doctor;
                    }
                    if ((!in_array($articlePrivate->id_user, $idDoctorHospitals))) {
                        return $this->responseError(403, 'Bạn không có quyền xem nó !');
                    }
                }
            }

            $filter = (object) [
                'id' => $id,
            ];
            $article = $this->articleRepository->searchAll($filter)->first();

            return $this->responseOK(200, $article, 'Xem bài viết chi tiết thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }
}
