<?php

/**
 * Api response helper file
 */

/**
 * Return success response
 *
 * @param int    $status  The HTTP status code for the response.
 * @param string $message The message to be included in the response.
 * @param mixed  $data    The data to be included in the response. This can be an array or null.
 *
 * @return \Illuminate\Http\JsonResponse The JSON response with the provided status, message, and data.
 */
if (!function_exists('ok')) {
    function ok($message = null, $data = null, $status = 200)
    {
        $response = [
            'status'    =>  $status,
            'message'   =>  $message ?? 'Process is successfully completed',
            'data'      =>  $data
        ];

        return response()->json($response, $status);
    }
}
/**
 * Return all type of error response with different status code
 *
 * @param string $message The message to be included in the response.
 * @param mixed  $data    The data to be included in the response. This can be an array or null.
 * @param string $type    The type of error response. This can be one of the following:
 *                         - 'loginCase'
 *                         - 'validation'
 *                         - 'unauthenticated'
 *                         - 'notfound'
 *                         - 'forbidden'
 *                         - 'processError'
 *                         If not provided, the default status code will be 500.
 *
 * @return \Illuminate\Http\JsonResponse The JSON response with the provided status, message, and data.
 */
if (!function_exists('error')) {
    function error($message = null, $data = null, $type = null)
    {
        $status = 500;

        switch ($type) {
            case 'loginCase':
                $status  = 401;
                $message ?? 'Credentials did not match, please try again';
                break;

            case 'validation':
                $status  = 422;
                $message ?? 'Validation Failed please check the request attributes and try again';
                break;

            case 'unauthenticated':
                $status  = 401;
                $message ?? 'User token has been expired';
                break;

            case 'notfound':
                $status  = 404;
                $message ?? 'Sorry no results query for your request';
                break;

            case 'forbidden':
                $status  = 403;
                $message ??  'You don\'t have permission to access this content';
                break;

            case 'processError':
                $status  = 400;
                $message ??  'the server cannot or will not process the request due to something that is perceived to be a client error';
                break;

            default:
                $status = 500;
                $message ?? $message = 'Server error, please try again later';
                break;
        }

        $response = [
            'status'    =>  $status,
            'message'   =>  $message,
            'data'      =>  $data
        ];

        return response()->json($response, $status);
    }
}