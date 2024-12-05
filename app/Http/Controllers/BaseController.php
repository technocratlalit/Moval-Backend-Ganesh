<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */

    public function sendResponse($result, $message, $code = 200)
    {
        $response = [
            'success' => true,
            'result'  => $result,
            'message' => $message,
        ];
        return response()->json($response, $code, [], JSON_UNESCAPED_SLASHES);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendLaravelFormatError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['errors'] = $errorMessages;
        }
        return response()->json($response, $code, [], JSON_UNESCAPED_SLASHES);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 422)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['result'] = $errorMessages;
        }
        return response()->json($response, $code, [], JSON_UNESCAPED_SLASHES);
    }
}
