<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Utils\FuncUUID;
use Validator;
use Exception;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    use FuncUUID;

    const NAME = 'name';
    const EMAIL = 'email';
    const PASSWORD = 'password';
    const CONFIRM_PASSWORD = 'confirm_password';
    const ROLE = 'role';
    const UUID = 'uuid';
    const ID = 'id';
    const TOKEN = 'token';
    const AUTH = 'Authorization';

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($credentials, [
            self::EMAIL => 'required|email',
            self::PASSWORD => 'required',
        ],[
            'required' => ':attribute wajib diisi.',
            'email' => ':attribute harus berformat email.',
        ]);
   
        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ],400);       
        }

        try{
            if($token = JWTAuth::attempt($credentials)){ 
                $data[self::TOKEN] =  $token;
                return response()->json([
                    'status' => 'success',
                    'data' => $data,
                ],200);
            } 
            
            return response()->json([
                'status' => 'error',
                'message' => 'Login gagal dilakukan.'
            ],400);

        }catch(JWTException $e){
            return response()->json([
                'status' => 'error',
                'message' => 'tidak bisa membuat token'
            ],500);
        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ],500);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            self::NAME => 'required',
            self::EMAIL => 'required|email|unique:users',
            self::PASSWORD => 'required',
            self::CONFIRM_PASSWORD => 'required|same:password',
        ],[
            'required' => ':attribute wajib diisi.',
            'unique' => ':attribute sudah terpakai.',
            'email' => ':attribute harus berformat email.',
        ]);
   
        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ],400);       
        }
   
        try{
            $input = $request->all();
            $input[self::ID] = $this->generateID();
            $input[self::UUID] = $this->generateUUID();
            $input[self::PASSWORD] = bcrypt($input[self::PASSWORD]);
            $input[self::ROLE] = 1;
            $user = User::create($input);
            
            return response()->json([
                'status' => 'success',
                'data' => $user,
            ],201);
        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ],500);
        }
    }

    public function refresh() {
        $credentials = $request->only('email', 'password');
        try{

            if($token = JWTAuth::attempt($credentials)){ 
                $token = Auth::refresh() ;
                $data[self::TOKEN] =  $token; 
                
                return response()->json([
                    'status' => 'success',
                    'data' => $data,
                ],200);
            } 
            
            return response()->json([
                'status' => 'error',
                'message' => 'Login gagal dilakukan.'
            ],400);

        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ],500);
        }
    }

    public function getUser(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            self::AUTH => 'required',
        ],[
            'required' => ':attribute wajib diisi.',
        ]);
  
        $user = JWTAuth::authenticate($request->header(self::AUTH));
  
        return response()->json([
            'status' => 'success',
            'data' => $user,
        ],200);
    }
}
