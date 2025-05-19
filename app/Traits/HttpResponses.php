<?php

namespace App\Traits;

trait HttpResponses
{
    /**
     * Return a success response.
     *
     * @param  string  $message
     * @param  array|null  $data
     * @param  int  $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($message = '', $data = null, $statusCode = 200)
    {
        $response = [
            'status' => 'success',
            'message' => $message,
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return an error response.
     *
     * @param  string  $message
     * @param  array|null  $errors
     * @param  int  $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse($message, $errors = null, $statusCode = 400)
    {
        $response = [
            'status' => 'error',
            'message' => $message,
        ];

        if (!is_null($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }
}