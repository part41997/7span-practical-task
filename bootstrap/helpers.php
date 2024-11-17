<?php

/**Json return response */
if (!function_exists('jsonResponse')) {
    function jsonResponse($statusCode = 400, $data = null, $message = 'Error')
    {
        $res = [
            'message' => $message,
            'data' => $data,
            'status' => $statusCode,
        ];

        return response()->json($res, $statusCode);
    }
}
