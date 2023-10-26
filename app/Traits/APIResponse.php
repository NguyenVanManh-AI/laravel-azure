<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HTTPStatus;

trait APIResponse
{
    /**
     * @param mixed $message
     */
    public function responseSuccess($message = 'Success', int $status = HTTPStatus::HTTP_OK): JsonResponse
    {
        return Response::json([
            'message' => $message,
            'status' => $status,
        ], $status);
    }

    /**
     * @param mixed $message
     * @param mixed $data
     */
    public function responseSuccessWithData($data, $message = 'Success', int $status = HTTPStatus::HTTP_OK): JsonResponse
    {
        return Response::json([
            'data' => $data,
            'message' => $message,
            'status' => $status,
        ], $status);
    }

    /**
     * @param mixed $message
     */
    public function responseError($message = [], int $status = HTTPStatus::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return Response::json([
            'message' => $message,
            'status' => $status,
        ], $status);
    }

    /**
     * @param string $message
     * @param mixed $errors
     */
    public function responseErrorWithData($errors, $message = 'Error', int $status = HTTPStatus::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return Response::json([
            'errors' => $errors,
            'message' => $message,
            'status' => $status,
        ], $status);
    }
}
