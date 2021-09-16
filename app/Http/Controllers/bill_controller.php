<?php

namespace App\Http\Controllers;

use App\Bill;
use App\Client;

use Illuminate\Http\Request;

class bill_controller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $client=Client::where('id',$id)->first();
        $bill=Bill::where('client_id',$id)->first();
        $data=array(
            'client_name'=>$client->name,
            'bill_id'=>$bill->bill_id,
            'bottles'=>$bill->used_bottles,
            'amount'=>$bill->amount
        );
        return response()->json($data,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function show(Bill $bill)
    {
        $bill=Bill::select('*')->with('Client')->get();
        if($bill->isEmpty())
        {
            $data=array(
                'status_message'=>'No data found'
            );
            $code=404;
            return response()->json($data,$code);
        }
        else{
            
            $code=200;
            return response()->json($bill,$code);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bill $bill)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bill $bill,$id)
    {
        // query for finnding user
        $bill=Bill::where('bill_id',$id)->delete();
        // if there is no record
        if(!$bill)
        {
            return response()->json(['status_message'=>'there is no record'],200);
            
        }
        // if the record is deleted
        else
        {
            return response()->json(['status_message'=>'Record has been deleted'],200);
        }
    }
}
