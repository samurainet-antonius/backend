<?php
namespace App\Utils;
use DB;
trait FuncUUID
{
    function generateID(){
        $uuid_short = DB::select("SELECT UUID_SHORT() as uuidShort")[0]->uuidShort;
        return $uuid_short;
    }

    function generateUUID(){
        $uuid = DB::select("SELECT UUID() as uuid")[0]->uuid;
        return $uuid;
    }
}