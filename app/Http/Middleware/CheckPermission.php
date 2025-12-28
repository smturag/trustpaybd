<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permission
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            return redirect()->route('adminlogin')->with('error', 'Please login to continue.');
        }

        // Super admin bypass (optional - adjust based on your needs)
        if ($admin->role_id == 1) {
            return $next($request);
        }

        if (!$admin->hasPermission($permission)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized. You do not have permission to access this resource.'], 403);
            }
            
            abort(403, 'Unauthorized. You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
