<?php

namespace himekawa\Http\Controllers\Api;

use Illuminate\Http\Request;
use himekawa\Http\Controllers\Controller;

class BaseApiController extends Controller
{
    protected function ok()
    {
        return $this->json([
            'message' => 'ok',
        ]);
    }

    protected function notOk()
    {
        return $this->json([
            'message' => 'Something went wrong :(',
        ], 500);
    }

    protected function json($data = [], $status = 200)
    {
        $payload = array_merge($data, ['status' => $status]);

        return response()->json($payload, $status);
    }
}
