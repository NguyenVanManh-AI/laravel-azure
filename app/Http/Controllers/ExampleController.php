<?php

namespace App\Http\Controllers;

use App\Services\ExampleService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExampleController extends Controller
{
    protected ExampleService $exampleService;

    /**
     * __construct
     *
     * @param ExampleService $exampleService
     */
    public function __construct(ExampleService $exampleService)
    {
        $this->exampleService = $exampleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        $filter = (object) [
            'name' => $request->name ?? '',
            'email' => $request->email ?? '',
            'start_at' => $request->start_at ?? '',
            'end_at' => $request->end_at ?? '',
            'page' => $request->page ?? 1,
            'per_page' => $request->per_page ?? 10,
        ];

        return $this->exampleService->getList($filter)->paginate($filter->per_page);
    }

    /**
     *  Display the specified resource.
     *
     * @return View|JsonResponse
     */
    public function show($id)
    {
        $example = $this->exampleService->findExampleById($id);

        return $example;

        // if (request()->wantsJson()) {
        //
        //     return response()->json([
        //         'data' => $example,
        //     ]);
        // }
        //
        // return view('examples.show', compact('example'));
    }
}
