<?php

namespace App\Services;

use App\Http\Requests\RequestCreateRating;
use App\Http\Requests\RequestUpdateRating;
use App\Models\Rating;
use App\Models\WorkSchedule;
use App\Repositories\RatingInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class RatingService
{
    protected RatingInterface $ratingRepository;

    public function __construct(
        RatingInterface $ratingRepository
    ) {
        $this->ratingRepository = $ratingRepository;
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

    public function add(RequestCreateRating $request)
    {
        try {
            $id_work_schedule = $request->id_work_schedule;
            $user = auth()->guard('user_api')->user();
            $workSchedule = WorkSchedule::where('id_user', $user->id)->where('id', $id_work_schedule)->first();
            if (empty($workSchedule)) {
                return $this->responseError(400, 'Không tìm thấy dịch vụ hay tư vấn để đánh giá !');
            }

            if ($workSchedule->is_confirm != 1) {
                return $this->responseError(400, 'Không thể đánh giá khi lịch chưa được xác nhận !');
            }

            $rating = Rating::where('id_user', $user->id)->where('id_work_schedule', $id_work_schedule)->first();
            if ($rating) {
                return $this->responseError(400, 'Bạn đã đánh giá dịch vụ , tư vấn này rồi !');
            }

            $data = array_merge($request->all(), ['id_user' => $user->id]);
            $newRating = Rating::create($data);

            return $this->responseOK(201, $newRating, 'Đánh giá dịch vụ , tư vấn thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function edit(RequestUpdateRating $request, $id)
    {
        try {
            $rating = Rating::find($id);
            $user = auth()->guard('user_api')->user();
            if ($rating) {
                if ($user->id != $rating->id_user) {
                    return $this->responseError(403, 'Bạn không có quyền chỉnh sửa đánh giá này !');
                }
                $rating->update($request->all());

                return $this->responseOK(200, $rating, 'Chỉnh sửa đánh giá thành công !');
            } else {
                return $this->responseError(400, 'Không tìm thấy đánh giá !');
            }
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }

    public function hospitalManage(Request $request)
    {
        try {
            $hospital = Auth::user();
            $orderBy = $request->typesort ?? 'id';
            switch ($orderBy) {
                case 'number_rating':
                    $orderBy = 'ratings.number_rating';
                    break;

                case 'name':
                    $orderBy = 'ratings.detail_rating';
                    break;

                case 'new':
                    $orderBy = 'ratings.id';
                    break;

                default:
                    $orderBy = 'ratings.id';
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
                'department_name' => $request->department_name ?? '',
                'service_name' => $request->service_name ?? '',
                'hospital_id' => $hospital->id,
                'doctor_id' => $request->doctor_id ?? null,
                'is_service' => $request->is_service ?? '',
                'start_date' => $request->start_date ?? '',
                'end_date' => $request->end_date ?? '',
                'id_work_schedule' => $id ?? null,
                'orderBy' => $orderBy,
                'orderDirection' => $orderDirection,
            ];

            if (!(empty($request->paginate))) {
                $ratings = $this->ratingRepository->searchRating($filter)->paginate($request->paginate);
            } else {
                $ratings = $this->ratingRepository->searchRating($filter)->get();
            }

            foreach ($ratings as $rating) {
                $rating->work_schedule_time = json_decode($rating->work_schedule_time);
                $rating->hospital_infrastructure = json_decode($rating->hospital_infrastructure);
                $rating->service_infor = json_decode($rating->service_infor);
            }

            return $this->responseOK(200, $ratings, 'Xem tất cả đánh giá thành công !');
        } catch (Throwable $e) {
            return $this->responseError(400, $e->getMessage());
        }
    }
}
