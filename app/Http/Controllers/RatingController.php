<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestCreateRating;
use App\Http\Requests\RequestUpdateRating;
use App\Services\RatingService;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    protected RatingService $ratingService;

    public function __construct(RatingService $ratingService)
    {
        $this->ratingService = $ratingService;
    }

    public function add(RequestCreateRating $request)
    {
        return $this->ratingService->add($request);
    }

    public function edit(RequestUpdateRating $request, $id)
    {
        return $this->ratingService->edit($request, $id);
    }

    public function hospitalManage(Request $request)
    {
        return $this->ratingService->hospitalManage($request);
    }
}
