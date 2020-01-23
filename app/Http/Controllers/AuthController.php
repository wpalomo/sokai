<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Usera; 
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
     /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','registro','me','logout','refresh','verusu']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        if(auth()->user()->id_rol == 1){
            $roles = DB::select("SELECT * FROM `roles` WHERE tipo = 1");
        }else{
            $roles = DB::select("SELECT * FROM `roles` WHERE id_user = ".auth(  )->user()->id);
        }
        return $this->respondWithToken($token,$roles);
    }

    public function registro()
    {
        Usera::create([
            'password' => Hash::make(request('password')),
            'email' => request('email'),
            'nombres' => request('nombres'),
            'apellidos' => request('apellidos'),
            'estado' => request('estado'),
            'id_rol' =>request('rol'),
            'id_empresa' =>request('empresa'),
            'id_establecimiento' =>request('establecimeinto'),
            'id_punto_emision' =>request('punto_emision'),
        ]);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function verusu()
    {
        return auth()->user()->id;
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token,$roles)
    {
        return response()->json([
            'accessToken' => $token,
            'token_type' => 'bearer',
            'expires_in' => 60,
            'roles' => $roles,
            'userData' => auth()->user()
        ]);
    }
}
