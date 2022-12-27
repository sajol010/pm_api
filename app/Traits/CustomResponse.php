<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait CustomResponse
{
    public function success($data=[], $msg="", $status=200): JsonResponse{
        $responseData = [
            "success"=>true
        ];
        if (!empty($msg))
            $responseData['message'] = $msg;

        if (!empty($data))
            $responseData['data'] = $data;

        return response()->json($responseData, $status);
    }

    public function fail($msg="", $errors=[], $status=400): JsonResponse{
        $responseData = [
            "success"=>false
        ];

        if (!empty($msg))
            $responseData['message'] = $msg;

        if (!empty($errors))
            $responseData['errors'] = $errors;

        return response()->json($responseData, $status);
    }

}
