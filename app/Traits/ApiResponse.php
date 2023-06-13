<?php

namespace App\Traits;

use Illuminate\Http\Response;

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
}
