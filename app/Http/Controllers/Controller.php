<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function ok($data, $message = "")
    {
        return response()->json([
            "status" => true,
            "message" => $message,
            "data" => $data,
        ], 200);
    }

    public function ERROR($msg, $status = 500)
    {
      return response()->json([
        "status" => false,
        "message" => $msg,
      ], $status);
    }

}
