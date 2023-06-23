<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

trait utilities
{
    // --- generate token
    public function generateToken($table){
        $res=true;
        while($res){
            $token = Str::random(64);
            $res = DB::select("select * from $table where token = ?", [$token]);
        }
        return $token;
       
    }
}
