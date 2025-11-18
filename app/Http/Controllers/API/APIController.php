<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class APIController extends Controller
{
    protected function respondWithSuccess($data, $message = 'Success', $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }   

    protected function respondWithError($message, $statusCode = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $statusCode);
    }   
}
