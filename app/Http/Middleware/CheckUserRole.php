<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        // $user = Auth::user(); // chỗ này đúng ra là phải guard 'user_api' nữa
        // mà do có middle user_api rồi nên cx không cần , chỉ cần role là được
        $user = Auth::guard('user_api')->user();
        // => bên Admin cũng nên thế

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
