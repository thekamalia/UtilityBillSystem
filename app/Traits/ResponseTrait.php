<?php

namespace App\Traits;

trait ResponseTrait
{
    public function responseSuccess($message = null, $data = [])
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ]); 
    } 
    
    public function responseError($message = null, $error = [], $data = [])
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'error' => $error,
            'data' => $data
        ]);
    }
}
