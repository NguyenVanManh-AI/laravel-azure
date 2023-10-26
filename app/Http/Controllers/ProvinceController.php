<?php

namespace App\Http\Controllers;

use App\Models\Province;

class ProvinceController extends Controller
{
    public function all()
    {
        return response()->json([
            'provinces' => Province::all(),
        ], 200);
    }
}
