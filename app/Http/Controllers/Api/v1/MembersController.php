<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Utils\FuncUUID;
use Exception;
use Validator;
use File;

class MembersController extends Controller
{
    use FuncUUID;

    const LIMIT = 'limit';
    const OFFSET = 'offset';
    const NAMA = 'nama';
    const EMAIL = 'email';
    const ALAMAT = 'alamat';
    const AVATAR = 'avatar';
    const UUID = 'uuid';
    const ID = 'id';
    const _METHOD = '_method';

    public function list(Request $request){
        try{

            $total = Member::all()->count();
            $members = Member::orderBy('created_at','desc')
                            ->paginate(4);
            
            $count = $members->count();

            return response()->json([
                'status' => 'success',
                'total' => $total,
                'count' => $count,
                'data' => $members
            ],200);

        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error.'
            ],500);
        }
    }

    public function create(Request $request){

        $data = $request->all();

        $data[self::ID] = $this->generateID();
        $data[self::UUID] = $this->generateUUID();

        $validator = Validator::make($data,[
            self::NAMA => 'required|string|max:255',
            self::EMAIL => 'required|email|unique:members',
            self::ALAMAT => 'required',
            self::AVATAR => 'required|mimes:jpg,png,jpeg|image'
        ],[
            'required' => ':attribute wajib diisi.',
            'unique' => ':attribute sudah terpakai.',
            'email' => ':attribute harus berformat email.',
            'image' => ':attribute harus berupa file image.',
            'mimes' => ':attribute format harus jpg,png,jpeg.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ],400);
        }

       try{

        $fileName = time().'.'.$request->avatar->extension();  
     
        $request->avatar->move(public_path(self::AVATAR), $fileName);

        $data[self::AVATAR] = $fileName;

        $result = Member::firstOrCreate($data);

        return response()->json([
            'status' => 'success',
            'data' => $result
        ],201);

       }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ],500);
       }
          

    }

    public function show($uuid){
        try{

            $member = Member::where(self::UUID,$uuid)->first();

            return response()->json([
                'status' => 'success',
                'data' => $member
            ],200);

        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error.'
            ],500);
       }
    }

    public function update(Request $request,$uuid){


        $member = Member::where(self::UUID,$uuid)->first();
        $isUniqueEmail = '';

        if($member->email != $request->email){
            $isUniqueEmail = '|unique:members';
        }

        $data = $request->all();

        // untuk cek di postman
       if($request->has(self::_METHOD)){
        unset($data[self::_METHOD]);
       }

        $validator = Validator::make($data,[
            self::NAMA => 'required|string|max:255',
            self::EMAIL => 'required|email'.$isUniqueEmail,
            self::ALAMAT => 'required',
            self::AVATAR => 'mimes:jpg,png,jpeg|image'
        ],[
            'required' => ':attribute wajib diisi.',
            'unique' => ':attribute sudah terpakai.',
            'email' => ':attribute harus berformat email.',
            'image' => ':attribute harus berupa file image.',
            'mimes' => ':attribute format harus jpg,png,jpeg.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->messages()
            ],400);
        }

       try{

        
        if($request->hasFile(self::AVATAR)){
            if(File::exists(public_path(self::AVATAR."/".$member->avatar))){
                File::delete(public_path(self::AVATAR."/".$member->avatar));
            }

            

            $fileName = time().'.'.$request->avatar->extension();  
     
            $request->avatar->move(public_path(self::AVATAR), $fileName);
    
            $data[self::AVATAR] = $fileName;
        }

        $result = Member::where(self::UUID,$uuid)
                        ->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data member berhasil diubah.'
        ],200);

       }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error.'
            ],500);
       }   

    }

    public function delete($uuid){


       $member = Member::where(self::UUID,$uuid)->first();

       try{


        
        if(File::exists(public_path(self::AVATAR."/".$member->avatar))){
            File::delete(public_path(self::AVATAR."/".$member->avatar));
        }

        $result = Member::where(self::UUID,$uuid)
                        ->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data member berhasil dihapus.'
        ],200);

       }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error.'
            ],500);
       }   

    }
}
