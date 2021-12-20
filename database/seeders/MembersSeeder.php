<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;
use App\Models\User;
use App\Utils\FuncUUID;
use DB;
use Exception;

class MembersSeeder extends Seeder
{
    use FuncUUID;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for($i=0;$i<=30;$i++){
            $members[$i] = [
                'id' => $this->generateID(),
                'nama' => 'Alvian',
                'email' => 'alvian.'.$i.'@gmail.com',
                'alamat' => 'Bantul',
                'avatar' => null,
                'created_at' => date("Y-m-d H:i:s"),
                'uuid' => $this->generateUUID()
            ];
        }

        $users = [
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('kadal'),
            'role' => 0,
            'created_at' => date("Y-m-d H:i:s"),
        ];

        DB::beginTransaction();
        try{
            User::insert($users);
            Member::insert($members);
            DB::commit();
        }catch(Exception $e){
            DB::rollback();
            dd($e->getMessage());
        }
    }
}
