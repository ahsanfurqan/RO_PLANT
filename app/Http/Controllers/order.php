<?php

namespace App\Http\Controllers;

use App\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Client;
use App\User;
use GuzzleHttp;
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
    public function message(Request $request)
    {
        $url = "https://messages-sandbox.nexmo.com/v0.1/messages";
        $params = ["to" => ["type" => "whatsapp", "number" => $request->input('number')],
        "from" => ["type" => "whatsapp", "number" => "14157386170"],
        "message" => [
            "content" => [
                "type" => "text",
                "text" => "Hello from Vonage and Laravel :) Please reply to this message with a number between 1 and 100"
            ]
        ]
    ];
    $headers = ["Authorization" => "Basic " . base64_encode(env('NEXMO_API_KEY') . ":" . env('NEXMO_API_SECRET'))];

    $client = new \GuzzleHttp\Client();
    $response = $client->request('POST', $url, ["headers" => $headers, "json" => $params]);
    $data = $response->getBody();
    return info($data);
    }
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
                            $message=$client->name.' you have returned '.$order->empty.' bottles and recieved '.$order->filled.' bottles';
                            $message=urlencode($message);
                            $c=new GuzzleHttp\Client();
                            $res=$c->request('GET','https://api.callmebot.com/whatsapp.php?phone=+923362394601&text='.$message.'&apikey=746371');
                            // $c = new \Guzzle\Service\Client('http://api.github.com/users/');
                            // $c= new GuzzleHttp\Client();
                            // $response=http::post('https://api.whatsapp.com/send?phone=+923333733626&text=hello');
                            // $response=http::get('https://wa.me/923333733626/?text=hello');
                            // $response=json_decode($response);
                            $data=array(
                                'status_message'=>'order has been added '.response()->json($res->getbody())
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
