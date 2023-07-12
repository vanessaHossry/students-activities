<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;



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

    public function generateImageURL($request){
        if($request->image != null){
        // $imageName = time().'.'.$request->image->extension();  
        // $request->image->move(public_path('images'), $imageName);
        // $src = url('images/'.$imageName);
        // return $src; 

       // this is not working be talli3 a not accessible url. Even when i put the same path
        $file = $request->file('image');
        $path = Storage::disk('public')->putFile('images', $file);  
        $imageUrl = Storage::disk('public')->url($path);
        return $imageUrl;
        }
        return "";
    }
}
