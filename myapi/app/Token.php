<?php

namespace App;
use Illuminate\Support\Facades\DB;

class Token 
{
    public static function setToken($id, $token, $expired)  
    {
        $res = DB::table('token')
        ->insert([
            'code'=> $token,
            'user_id'=>$id,
            'expired_at'=>$expired
        ]);
    }
}
