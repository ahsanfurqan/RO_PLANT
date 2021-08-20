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
        $client=Client::all();
        if($client->isEmpty())
        {
            $data=array(
                'status_message'=>'No data found'
            );
            $code=404;
        }
        else{
            $data=array(
                $client
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
                // assigning barcode with id + phone_number
                $client=Client::where('id',$customer->id)->first();
                $client->barcode=$customer->barcode.$customer->id;
                $foo=$client->save();
                if($foo){
                return response()->json([
                    'Client_id'=>$customer->id,
                    'status_message'=>"Customer has been registered successfully ".$client->barcode
                ],200);
            }
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
    public function show(Client $client,$name)
    {
        $user=Client::where('name',$name)->get();
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
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client,$id)
    {
        // query for getting the first user
        $client=Client::where('id',$id)->first();
        // if there is no record
        if(!$client)
        {
            $data=array(
                'status_message'=>'No record found'
            );
            $code=404;
        }
        // if there is record then update
        else{
            $client->update($request->all());
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
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,Client $client)
    {
        // query for finnding user
        $client=Client::where('id',$id)->delete();
        // if there is no record
        if(!$client)
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
