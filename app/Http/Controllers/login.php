<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
class login extends Controller
{
    public function userlogin(Request $request){
        // $email=$request->email;
        // $password=$request->password;
        
        
        $data=$request->only(['email','password']);
        $bool=Auth::attempt($data);
        return response()->json(['status_message'=>$bool]);
        
    }
}
