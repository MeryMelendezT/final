<?php

namespace App\Http\Middleware;

use App\Helpers\JwtAuth;
use App\Modelos\Responsable;
use Closure;

class VerificarJWT
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
        $token=$request->header('Authorization',null);
        //$id=$request->route('id');

        if (!isset($token) || is_null($token))
        {
            $data=array(
                'mensaje'=>'el token es null o no existe'
            );
            return response()->json($data);
        }else{
            //existe
            $jwt= new JwtAuth();
            $payload=$jwt->verificarToken($token);
            if(!$payload)
            {
                //si es invalido
                $data=array(
                    'mensaje'=>'el token es incorrecto'
                );
                return response()->json($data);
            }else{
                //token valido
                if ($payload->type=='verificacion')
                {
                    //verificacion
                    if($payload->exp >= time())
                    {
                        return $next($request);
                    }else{
                        $data=array(
                            'mensaje'=>'el token de verificacion ha expirado necesita el token de refresh'
                        );
                        return response()->json($data);
                    }

                }elseif ($payload->type=='refresh'){
                    //refresh
                    if($payload->exp >= time())
                    {
                        $responsable=Responsable::find($payload->sub);

                        $nuevosTokens=$jwt->signUp($responsable->correo,$responsable->contraseña);
                        return response()->json($nuevosTokens);
                    }else{
                        $data=array(
                            'mensaje'=>'el token de rerfrresh ha expirado necesita loguearse de nuevo'
                        );
                        return response()->json($data);
                    }

                }else{
                    $data=array(
                        'mensaje'=>'el token es incorrecto'
                    );
                    return response()->json($data);
                }
            }
        }

    }
}