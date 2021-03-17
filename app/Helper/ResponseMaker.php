<?php

namespace App\Helper;

trait ResponseMaker
{
    public function responseMaker($data = null, $http_code = 200, $message = null)
    {
        if (empty($message)) {
            switch ($http_code) {
                case 200: 
                    $message = 'OK';
                    break;
                case 400: 
                    $message = 'Bad Request';
                    break;
                case 401: 
                    $message = 'Unauthorized';
                    break;
                case 403: 
                    $message = 'Forbidden';
                    break;
                case 405: 
                    $message = 'Method Not Allowed';
                    break;
                case 409: 
                    $message = 'Conflict';
                    break;
                case 415: 
                    $message = 'Unsupported Media Type';
                    break;
                case 429: 
                    $message = 'Too Many Requests';
                    break;
                case 500: 
                    $message = 'Internal Server Error';
                    break;
                case 999: 
                    $message = 'Error';
                    break;
            }
        }
        $timediff = microtime(true) - LARAVEL_START;
        return response()->json([
            'message' => $message,
            'data'    => $data,
            'duration' => $timediff
        ], $http_code)->header('Content-Type', 'application/json');
    }
}
