<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class logout extends Controller
{
    public function userlogout(){
        if(session()->has('user')){
           $data= session()->pull('user');
        }
        return response()->json(['staus_message'=>$data.' logout']);
    }
}
