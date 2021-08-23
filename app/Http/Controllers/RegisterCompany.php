<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class RegisterCompany extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company=Company::all();
        if($company->isEmpty())
        {
            $data=array(
                'status_message'=>'No data found'
            );
            $code=404;
        }
        else{
            $data=array(
                $company
            );
            $code=200;
        }
        return response()->json($data,$code);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /*
    for storing the data you need three input fiels
    name, phone_number, total_bottles
    which will be validate for required unique and max min numbers
    and then genreate response based on error
    and then it will finally add the data on the database
    */
    public function store(Request $request)
    {
        // rules for validating
        $rules=array(
            'name'=>['required','max:50','unique:companies'],
            'phone_number'=>['required','max:11','min:11','unique:companies'],
            'total_bottles'=>['required']
        );
        // validating the data 
        $validate=Validator::make($request->all(),$rules);
        
        
        // checking the validtion fails and return response of error 

        if($validate->fails()){
            return response()->json($validate->errors(),406);
        }
        // creating a new company after passing validation
        else
        {
            $company= new Company();
            $company->name=$request->input('name');// taking input name field
            $company->phone_number=$request->input('phone_number');//taking input phone number 
            $company->total_bottles=$request->input('total_bottles');// taking number of bottles 
            $boo=$company->save();// saving it in database
            

            // returning responses

            if($boo){
                return response()->json([
                    'company_id'=>$company->company_id,
                    'status_message'=>"Company has been registered successfully"
                ],200);
            }
            
            else{
                return response()->json([
                    'status_message'=>'Company register unsuccessfull'
                ],500);
            }
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company,$name)
    {
        $company=Company::where('name',$name)->get();
       if($company->isEmpty())
       {
            return response()->json(['error'=>'No user found'],404);
           
       }
       else{
            return response($company,200);
       }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update($id,Request $request, Company $company)
    {
        // query for getting the first user
        $company=Company::where('company_id',$id)->first();
        // if there is no record
        if(!$company)
        {
            $data=array(
                'status_message'=>'No record found'
            );
            $code=404;
        }
        // if there is record then update
        else{
            $company->update($request->all());
            $data=array(
                'status_message'=>'Record updated '.$id
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
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company,$id)
    {
         // query for finnding user
         $company=Company::where('company_id',$id)->delete();
         // if there is no record
         if(!$company)
         {
             return response()->json(['status_message'=>'there is no record'],404);
             
         }
         // if the record is deleted
         else
         {
             return response()->json(['status_message'=>'record has been deleted'],200);
         }
    }
}
