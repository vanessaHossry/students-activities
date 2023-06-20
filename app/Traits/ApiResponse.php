<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

trait ApiResponse
{
    // --- success response
    public function successResponse($data = [], $code = Response::HTTP_OK)
    {
        return response()->json([
            "data" => $data
        ], $code);
    }

    // --- error response
    public function errorResponse($error = null, $code = Response::HTTP_OK)
    {
        return response()->json([
            "error" => $error,
            "error_code" => $code
        ], $code);
    }

    public function generateToken($table){
        $res=true;
        while($res){
            $token = Str::random(64);
            $res = DB::select("select * from $table where token = ?", [$token]);
        }
        return $token;
       
    }
}
