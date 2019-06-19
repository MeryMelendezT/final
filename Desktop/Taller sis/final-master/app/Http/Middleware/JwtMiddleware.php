<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use function MongoDB\BSON\toJSON;

class JwtMiddleware
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
            $token=JWTAuth::parseToken();
            $token->getPayload()->get('sub');
            $token->getPayload()->get('name');
            $token->getPayload()->get('rol');
            //echo explode('.', (string)$translate) ;
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['status' => 'Token is Invalido']);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json(['status' => 'Token is Expiredo']);
            }else{
                return response()->json(['status' => 'Authorization Token not found']);
            }
        }
        return $next($request);
    }
}
