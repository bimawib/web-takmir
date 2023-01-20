<?php

namespace App\Http\Controllers\api\V1;

use App\Models\Balance;
use Illuminate\Http\Request;
use App\Filters\V1\BalanceFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreBalanceRequest;
use App\Http\Resources\V1\BalanceResource;
use App\Http\Requests\V1\UpdateBalanceRequest;
use App\Http\Resources\V1\BalanceCollection;

class BalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = new BalanceFilter();
        $queryItems = $filter->transform($request); // ['nama kolom', 'operator ex : like, <, =', 'value']

        $Balance = Balance::where($queryItems);
        $sortBy = $request['sortBy'];

        $columnMap = [
            'spendBalance'=>'spend_balance',
            'incomingBalance'=>'incoming_balance',
            'totalBalance'=>'total_balance'
        ];

        if(isset($sortBy['asc'])){
            foreach($columnMap as $fromQuery => $toTable){
                if($sortBy['asc'] == $fromQuery){
                    return new BalanceCollection($Balance->orderBy($toTable,'asc')->paginate()->appends($request->query()));
                }
            }
        } elseif(isset($sortBy['desc'])){
            foreach($columnMap as $fromQuery => $toTable){
                if($sortBy['desc'] == $fromQuery){
                    return new BalanceCollection($Balance->orderBy($toTable,'desc')->paginate()->appends($request->query()));
                }
            }
        }

        return new BalanceCollection($Balance->paginate()->appends($request->query()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBalanceRequest $request)
    {
        $request['user_id'] = 13; // auth('sanctum')->user()->id;

        $request['spend_balance'] = 0;
        $request['incoming_balance'] = 0;

        $last_added_balance = Balance::orderBy('id','desc')->first();

        // return isset($last_added_balance);

        if($request->isSpend == 1){
            $request['spend_balance'] = $request->amountBalance;

            if(!isset($last_added_balance) == 1){
                return response()->json([
                    'error'=>[
                        'status'=>405,
                        'error'=>"Method Not Allowed",
                        'message'=>'Cannot reduce unexistent balance'
                    ]
                ],405);
            }

            $request['total_balance'] = $last_added_balance->total_balance - $request->amountBalance;

        } elseif($request->isSpend == 0){
            $request['incoming_balance'] = $request->amountBalance;

            $total_balance_before = $last_added_balance->total_balance ?? 0;

            $request['total_balance'] = $total_balance_before + $request->amountBalance;
        }

        $balance = Balance::create($request->all());
        // return $last_added_balance->total_balance;
        return new BalanceResource($balance);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Balance  $balance
     * @return \Illuminate\Http\Response
     */
    public function show(Balance $balance)
    {
        return new BalanceResource($balance);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Balance  $balance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Balance $balance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Balance  $balance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Balance $balance)
    {
        //
    }

    public function latestBalance(){

    } // this is for public consumption
}
