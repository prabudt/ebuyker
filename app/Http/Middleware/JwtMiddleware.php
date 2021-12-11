<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {       
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            $statusCode = 409;
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                $message = 'Token is Invalid';
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                $message = 'Token is Expired';
            } else {
                $message = 'Authorization Token not found';
                $statusCode = 200;
            }
            return $this->sendSuccess($message, $message, $statusCode);
        }
        return $next($request);
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
     * @return array|object
     */
    protected function sendResponse($response)
    {
         return (object)$response;
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
}