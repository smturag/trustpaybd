<?php

namespace App\Http\Controllers\Api\IBot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class CheckingController extends Controller
{
    public function dataSubmit(Request $request)
    {
        $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWUsImlhdCI6MTUxNjIzOTAyMn0.KMUFsIDTnFmyG3nMiGM6H9FNFUROf3wh7SmqJp-QV30";

        // Token check
        if ($token !== $request->token) {
            return response()->json(['message' => 'Invalid token', 'status' => false], 401);
        }

        // Insert data into the table
        DB::table('temp_table')->insert([
            'body' => $request->body,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Data submitted', 'status' => true], 200);
    }

    public function checkResponse(Request $request){

        // Log::info('hit from webhook start');

        // Log::info($request);

        // Log::info('hit from webhook end');

         return response()->json(['message' => 'Data submitted', 'status' => true], 200);

    }
}
