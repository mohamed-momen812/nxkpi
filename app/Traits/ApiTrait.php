<?php

namespace App\Traits;

trait ApiTrait
{
    public function responseJson($data, $message = "Successfully Done" , $status = 200)
    {
        return response()->json([
            "success" => true,
            "status" => $status,
            "message" => $message,
            "data" => $data
        ], $status);
    }

    public function responseJsonWithoutData($message = "Successfully Done", $status = 200 )
    {
        return response()->json([
            "success" => true,
            "status" => $status,
            "message" => $message,
        ], $status);
    }

    public function responseJsonFailed($message = "Fail, try again", $status = 400 )
    {
        return response()->json([
            "success" => false,
            "status" => $status,
            "message" => $message,
        ], $status);
    }

    public function responseJsonFailedValidate($data, $status = 422 )
    {
        return response()->json([
            "success" => false,
            "status" => $status,
            "message" => 'validation errors',
            "data" => $data
        ], $status);
    }

}
