<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function register(Request $request){

        //Recoger post
    	$json = $request->input('json', null);
    	$params = json_decode($json);

        //$role_id = (!is_null($json) && isset($params->role_id)) ? $params->role_id : null;
        $email = (!is_null($json) && isset($params->email)) ? $params->email : null;
        $name = (!is_null($json) && isset($params->name)) ? $params->name : null;
        $surname = (!is_null($json) && isset($params->surname)) ? $params->surname : null;
        $role = (!is_null($json) && isset($params->role)) ? $params->surname : null;
        $password = (!is_null($json) && isset($params->password)) ? $params->password : null;
        //$address = (!is_null($json) && isset($params->address)) ? $params->address : null;

    	if(!is_null($email) && !is_null($password) && !is_null($name)&& !is_null($surname)&& !is_null($role)){

    		// Crear el usuario
    		$user = new User();
    		$user->email = $email;
    		$user->name = $name;
    		$user->surname = $surname;
    		$user->role = '1';

    		$pwd = hash('sha256', $password);
    		$user->password = $pwd;
            //$user->address= $address;

    		// Comprobar usuario duplicado
    		$isset_user = User::where('email', '=', $email)->count();

    		if($isset_user == 0){
    			// Guardar el usuario
    			$user->save();

    			$data = array(
	    			'status' => 'success',
	    			'code' => 200,
	    			'message' => 'Usuario registrado correctamente!!'
	    		);
                Log::info($data);

    		}else{
    			// No guardarlo porque ya existe
    			$data = array(
	    			'status' => 'error',
	    			'code' => 400,
	    			'message' => 'Usuario duplicado, no puede registrarse'
	    		);
                Log::warning($data);
    		}


    	}else{
    		$data = array(
    			'status' => 'error',
    			'code' => 400,
    			'message' => 'Usuario no creado'
    		);

            Log::alert($data);

    	}

    	return response()->json($data, 200);
    }

    public function login(Request $request){
    	$jwtAuth = new JwtAuth();

        //Recibir POST
        $json = $request->input('json', null);
        $params = json_decode($json);

        $email = (!is_null($json) && isset($params->email)) ? $params->email : null;
        $password = (!is_null($json) && isset($params->password)) ? $params->password : null;
        $getToken = (!is_null($json) && isset($params->gettoken)) ? $params->gettoken : null;
    
        //Cifrar la password
        $pwd = hash('sha256', $password);

        if(!is_null($email) && !is_null($password) && ($getToken == null || $getToken == 'false')){
            $signup = $jwtAuth->signup($email, $pwd);

        }elseif($getToken != null){
            //var_dump($getToken); die();
            $signup = $jwtAuth->signup($email, $pwd, $getToken);

        }else{
            $signup = array(
                    'status' => 'error',
                    'message' => 'Envia tus datos por post'
                );
            Log::alert($signup);

        }

        Log::info("Usuario correctamente loggeado");
        return response()->json($signup, 200);

    }

}
