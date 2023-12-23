<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestStatistical;
use App\Services\StatisticalService;
use Illuminate\Http\Request;

class StatisticalController extends Controller
{
    protected StatisticalService $statisticalService;

    public function __construct(StatisticalService $statisticalService)
    {
        $this->statisticalService = $statisticalService;
    }

    public function user(RequestStatistical $request)
    {
        return $this->statisticalService->user($request);
    }

    public function article(RequestStatistical $request)
    {
        return $this->statisticalService->article($request);
    }

    public function top(RequestStatistical $request)
    {
        return $this->statisticalService->top($request);
    }

    public function overview(Request $request)
    {
        return $this->statisticalService->overview($request);
    }

    public function advise(RequestStatistical $request)
    {
        return $this->statisticalService->advise($request);
    }

    public function service(RequestStatistical $request)
    {
        return $this->statisticalService->service($request);
    }

    public function serviceTable(RequestStatistical $request)
    {
        return $this->statisticalService->serviceTable($request);
    }

    public function adviseTable(RequestStatistical $request)
    {
        return $this->statisticalService->adviseTable($request);
    }
}
