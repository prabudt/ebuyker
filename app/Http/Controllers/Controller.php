<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $errorMessage = [
        200 => 'OK',
        204 => 'No Content',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        412 => 'Precondition Failed',
        415 => 'Unsupported Media Type',
        500 => 'Internal Server Error',
        501 => 'Not Implemented'
    ];

    /**
     * @param $token
     * @return JsonResponse
     */
    protected function respondWithToken($token)
    {
        return $this->sendSuccess([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ]);
    }

    /**
     * @param $response
     * @return JsonResponse
     */
    protected function sendSuccess($response, $message = 'OK', $statusCode = 200)
    {
        return response()->json(
            $this->frameResponse(false, 200,  $message, $this->sendResponse($response)),
            $statusCode);
    }
    /**
     * @param $response
     * @return JsonResponse
     */
    protected function sendSuccessWithToken($response, $message = 'OK', $token = '', $statusCode = 200)
    {
        return response()->json(
            $this->frameResponseWithToken(false, 200,  $message, $this->sendResponse($response), $token),
            $statusCode);
    }

    /**
     * @param bool $error
     * @param int $statusCode
     * @param string $statusMessage
     * @param array|object $data
     * @return array
     */
    protected function frameResponse(bool $error, int $statusCode, string $statusMessage, $data): array
    {
        return [
            'error' => $error,
            'statusCode' => $statusCode,
            'statusMessage' => $statusMessage,
            'data' => $data,
            'responseTime' => time()
        ];
    }

    /**
     * @param bool $error
     * @param int $statusCode
     * @param string $statusMessage
     * @param array|object $data
     * @return array
     */
    protected function frameResponseWithToken(bool $error, int $statusCode, string $statusMessage, $data, $token): array
    {
        return [
            'error' => $error,
            'statusCode' => $statusCode,
            'statusMessage' => $statusMessage,
            'token' => $token,
            'data' => $data,
            'responseTime' => time()
        ];
    }

    /**
     * @param $response
     * @return array|object
     */
    protected function sendResponse($response)
    {
         return (object)$response;
    }

    /**
     * @param $response
     * @param int $status
     * @return JsonResponse
     */
    protected function sendError($response, $status = 200)
    {
        return response()->json(
            $this->frameResponse(true, $status, $this->errorMessage[$status], $this->sendResponse($response)),
            200);
    }

    /**
     * @param $response
     * @return JsonResponse
     */
    protected function validationError($response, $status = 200)
    {
        return response()->json(
            $this->frameResponse(true, $status, $response, $this->sendResponse($response)), $status);
    }

    /**
     * @param $request
     * @return array
     */
    protected function getRequest($request, $exceptData = '')
    {
        return (array)$request->all($exceptData);
    }
}
