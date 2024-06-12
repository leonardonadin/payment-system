<?php

namespace App\Http\Controllers\Api;

abstract class ApiController extends \App\Http\Controllers\Controller
{
    public function jsonReponse($data, $status = 200)
    {
        if (isset($data['error'])) {
            $status = 400;
            $data = ['message' => $data['error']];
        }

        return response()->json($data, $status);
    }
}
