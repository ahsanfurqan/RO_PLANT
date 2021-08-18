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
        //
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
                    'company_id'=>$company->id,
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
    public function show(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        //
    }
}
