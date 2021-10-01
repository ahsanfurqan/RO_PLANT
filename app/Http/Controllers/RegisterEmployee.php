<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class RegisterEmployee extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $emp=User::all();
        if($emp->isEmpty())
        {
            $data=array(
                'status_message'=>'No data found'
            );
            $code=404;
            return response()->json($data,$code);
        }
        else{
            
            $code=200;
            return response()->json($emp,$code);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /*
    this func will store the data in db
    fields name will be name, address, email,
    phone_number,salary,date_of_joining,password,confirm_password
    */

    public function store(Request $request)
    {
        //rules for validation
        $rules=array(
            'name'=>['required','max:50'],
            'email'=>['required','max:50','email','unique:users'],
            'phone_number'=>['required','max:12','min:12','unique:users','regex:/(03)[0-9]{2}[-][0-9]{7}/'],
            'salary'=>['required'],
            'password'=>['required','min:11'],
            'confirm_password'=>['required','same:password'],
            'date_of_joining'=>['required']
        );
        // validate
        $validate=validator::make($request->all(),$rules);

        // checking validation returning error
        if($validate->fails())
        {
            return response()->json(["status_message"=>$validate->errors()],406);
        }


        // storing data in database 
        else{
            $employee= new User();
            $employee->name=$request->input('name');
            $employee->address=$request->input('address');
            $employee->phone_number=$request->input('phone_number');
            $employee->salary=$request->input('salary');
            $employee->email=$request->input('email');
            $employee->password=Hash::make($request->input('password'));
            $employee->date_of_joining=$request->input('date_of_joining');
            $employee->api_token=Str::random(60);
            $boo=$employee->save();
            if($boo)
            {
                return response()->json([
                    'Employeeid'=>$employee->employee_id,
                    'status_message'=>'Employee has been registered '
                ],200);
            }
            else{
                return response()->json([
                    'Error'=>'Employe couldnt be register'
                ],500);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
       $user=User::where('name',$name)->get();
       if($user->isEmpty())
       {
            return response()->json(['error'=>'No user found'],404);
           
       }
       else{
            return response($user,200);
       }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */

     /*
        to update the record we need id
        and all the fields to update the 
        data
    */
    public function update(Request $request,$id)
    {
        // query for getting the first user
        $emp=User::where('employee_id',$id)->first();
        if($emp->phone_number!=$request->phone_number){
            $rules=array(
            'name'=>['required','max:50'],
            'phone_number'=>['required','max:12','min:12','unique:users','regex:/(03)[0-9]{2}[-][0-9]{7}/'],
            'salary'=>['required'],
            'date_of_joining'=>['required']
            );    
        }
        else{
            $rules=array(
                'name'=>['required','max:50'],
                'phone_number'=>['required','max:12','min:12','regex:/(03)[0-9]{2}[-][0-9]{7}/'],
                'salary'=>['required'],
                'date_of_joining'=>['required']
                );   
        }
        // validate
        $validate=validator::make($request->all(),$rules);

        // checking validation returning error
        if($validate->fails())
        {
            return response()->json(["status_message"=>$validate->errors()],406);
        }
        // if there is no record
        if(!$emp)
        {
            $data=array(
                'status_message'=>'No record found'
            );
            $code=404;
        }
        // if there is record then update
        else{
            $emp->update($request->all());
            $data=array(
                'status_message'=>'Record updated '
            );   
            $code=200;
        }
        // returning the response respectively
        return response()->json($data,$code);
        // return $emp;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */



    /*
    to delete employee data we need to 
    give id to delete that record
    */

    public function destroy($id,Request $request)
    {   
        // query for finnding user
        $emp=User::where('employee_id',$id)->delete();
        // if there is no record
        if(!$emp)
        {
            return response()->json(['status_message'=>'there is no record'],404);
            
        }
        // if the record is deleted
        else
        {
            // $emp->delete();
            return response()->json(['status_message'=>'record has been deleted'],200);
        }
    }
}
