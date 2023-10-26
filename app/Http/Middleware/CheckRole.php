<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        // 'role' => \App\Http\Middleware\CheckRole::class,
        // Tại vì admins và users đều có cột role nên dùng chung luôn , không cần tách ra nữa
        // Dùng file này để dùng chung cho middleware role cho cả user và admin
        // $admin = Auth::guard('admin_api')->user();
        // $user = Auth::guard('user_api')->user();
        $user = Auth::user(); // ta sẽ dùng Auth::user() , thay vì tách ra admin_api hay user_api
        if (!$user) {
            return response('Unauthorized', 401);

            return response()->json(['status' => 'Unauthorized'], 401);
        }
        // Kiểm tra xem người dùng có vai trò nằm trong danh sách $roles không
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        return response()->json(['status' => 'Forbidden'], 403);
    }
}
