<?php

namespace App\Http\Controllers;

use App\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Client;
use App\User;
class order extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {  
        //getting client with barcode
        $client=Client::where('barcode',$request->barcode)->first();
        // checkking if user is logged in
            //authenticating if loggedin user is correct
            $emp=User::where('api_token',$request->token)->first();
            if($emp==NULL){
                //returning response if doesnot exist
                return response()->json(['status_message'=>'Unauthorized'],401);
            }
            else{
                // chcking if its first order
                // $user_product=product::where('client_id',$client->id)->latest()->first();
                    $rules=array(
                        'empty'=>['required'],
                        'filled'=>['required']
                    );
                    $validate=validator::make($request->all(),$rules);
                    // if validation fails
                    if($validate->fails()){
                        $data=$validate->errors();
                        $code=406;
                    }
                    // if validation passes
                    else{
                        $order= new product();
                        $order->client_id=$client->id;
                        $order->employee_id=$emp->employee_id;
                        $order->empty=$request->input('empty');
                        $order->filled=$request->input('filled');
                        $boo=$order->save();
                        // if data added successfully 
                        if($boo){
                            $message='mr\ms '.$client->name.' you have returned '.$order->empty.' and recieved '.$order->filled;
                            $message=urlencode($message);
                            // $c = new \Guzzle\Service\Client('http://api.github.com/users/');
                            // $c= new GuzzleHttp\Client();
                            // $response=http::post('https://api.whatsapp.com/send?phone=+923333733626&text=hello');
                            $response=http::get('https://wa.me/923333733626/?text=hello');
                            $response=json_decode($response);
                            $data=array(
                                'status_message'=>'order has been added '.$response
                            );
                            $code=200;

                        }
                        // if data does not added successfully
                        else{
                            $data=array(
                                'status_message'=>'data was not added'
                            );
                            $code=406;
                        }
                        return response()->json($data,$code);
                }
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(product $product)
    {
        //
    }
}
