<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAdminRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        // $admin = Auth::user(); // chỗ này đúng ra là phải guard 'admin_api' nữa
        // mà do có middle admin_api rồi nên cx không cần , chỉ cần role là được
        $admin = Auth::guard('admin_api')->user();
        // => bên User cũng nên thế

        if (!$admin) {
            return response('Unauthorized', 401);

            return response()->json(['status' => 'Unauthorized'], 401);
        }

        // Kiểm tra xem người dùng có vai trò nằm trong danh sách $roles không
        if (in_array($admin->role, $roles)) {
            return $next($request);
        }

        return response()->json(['status' => 'Forbidden'], 403);
    }
}
