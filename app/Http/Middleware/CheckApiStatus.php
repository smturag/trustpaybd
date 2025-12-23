<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;

class CheckApiStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
      
      return $next($request);
        // $response = Http::post('https://billing.irecharge.net/api/verify_me', [
        //     'domain' => 'cricex.xyz',
        //     'service' => 'iPaybd Billing For MFS Withdraw',
        // ]);

        // $data = $response->json();
      



        // if($response->status() !==  200){
        //     return response()->view('verify', [
        //         'data' => $data, // Pass the data from the API response
        //     ], 503);
        // }else{


        //     if($data['status'] == 2){
        //         return $next($request);
        //     }

        //     return response()->view('verify', [
        //         'data' => $data, // Pass the data from the API response
        //     ], 503);
        // }




    }
}