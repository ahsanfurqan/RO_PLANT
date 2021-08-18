<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterClient extends Controller
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
    for storing the data you need five input fiels
    name, address, phone_number, price, company_id
    which will be validate for required unique and max min numbers
    and then genreate response based on error
    and then it will finally add the data on the database
    */

    public function upload(Request $request)
    {
        // rules for validating
        $rules=array(
            'name'=>['required','max:50'],
            'address'=>['required','max:50'],
            'phone_number'=>['required','max:11','min:11','Unique:clients'],
            'price'=>['required'],
            'company_id'=>['required'],
        );
        
        // validator
        $validate=validator::make($request->all(),$rules);

        // checking and returning response of validation
        if($validate->fails())
        {
            return response()->json([$validate->errors()],406);

        }


        else
        {   //taking the input and storing in database
            // $d=new DNS1D();
            $customer=new Client();
            $customer->name=$request->input('name');
            $customer->address=$request->input('address');
            $customer->phone_number=$request->input('phone_number');
            $customer->barcode=$customer->phone_number;
            $customer->price=$request->input('price');
            $customer->company_id=$request->input('company_id');
            $boo=$customer->save();
            if($boo){
                return response()->json([
                    'Client_id'=>$customer->id,
                    'status_message'=>"Customer has been registered successfully"
                ],200);
            }
            
            else{
                return response()->json([
                    'status_message'=>'Customer register unsuccessfull'
                ],500);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        //
    }
}
