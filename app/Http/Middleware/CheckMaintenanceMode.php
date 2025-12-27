<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SystemSetting;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Check if maintenance mode is enabled
            if (SystemSetting::isMaintenanceMode()) {
                // Allow admin panel access
                if ($request->is('admin*')) {
                    return $next($request);
                }

                // Block API and other requests
                if ($request->is('api*') || $request->is('merchant*') || $request->is('customer*')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'System is under maintenance. Please try again later.',
                        'maintenance_mode' => true,
                    ], 503);
                }

                // For web requests, show maintenance page
                return response()->view('errors.maintenance', [], 503);
            }
        } catch (\Exception $e) {
            // If system_settings table doesn't exist, allow request to pass
            // This prevents blocking during initial setup/migration
        }

        return $next($request);
    }
}
