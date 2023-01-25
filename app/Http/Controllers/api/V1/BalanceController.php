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
        $user = auth('sanctum')->user();
        if($user->is_owner == 0){
            return response()->json([
                'error'=>[
                    'status'=>403,
                    'message'=>'You dont have ability to see balance!'
                ]
            ],403);
        } // use another method instead, buat link api baru untuk yg umum

        $filter = new BalanceFilter();
        $queryItems = $filter->transform($request); // ['nama kolom', 'operator ex : like, <, =', 'value']

        $balance = Balance::where($queryItems);
        $sortBy = $request['sortBy'];

        $columnMap = [
            'spendBalance'=>'spend_balance',
            'incomingBalance'=>'incoming_balance',
            'totalBalance'=>'total_balance'
        ];

        if(isset($sortBy['asc'])){
            foreach($columnMap as $fromQuery => $toTable){
                if($sortBy['asc'] == $fromQuery){
                    return new BalanceCollection($balance->orderBy($toTable,'asc')->paginate()->appends($request->query()));
                }
            }
        } elseif(isset($sortBy['desc'])){
            foreach($columnMap as $fromQuery => $toTable){
                if($sortBy['desc'] == $fromQuery){
                    return new BalanceCollection($balance->orderBy($toTable,'desc')->paginate()->appends($request->query()));
                }
            }
        }

        return new BalanceCollection($balance->orderBy('date','asc')->paginate()->appends($request->query()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBalanceRequest $request)
    {
        $request['user_id'] = auth('sanctum')->user()->id;

        $user = auth('sanctum')->user();
        if($user->is_owner == 0){
            return response()->json([
                'error'=>[
                    'status'=>403,
                    'message'=>'You dont have ability to store balance!'
                ]
            ],403);
        }

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
        $user = auth('sanctum')->user();
        if($user->is_owner == 0){
            return response()->json([
                'error'=>[
                    'status'=>403,
                    'message'=>'You dont have ability to show balance!'
                ]
            ],403);
        }
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
        // hanya update title,date dan note saja, karna akan rusak struktur total balance atau update dengan cara hapus dan create baru
        // jadikan spend balance/ incoming balance 0 jika delete, pakai note untuk notice canceled-transaction
        // FINAL = NO UPDATE OR DESTROY FOR BALANCE
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Balance  $balance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Balance $balance)
    {
        // sebelum delete pastikan kurangi atau tambahkan totalBalance (delete this balance, and create new balance dengan title cancel input for ($balance->title))
        // might just wanna make 1 more table for total_balance
        // FINAL = NO UPDATE OR DESTROY FOR BALANCE
    }

    public function publicIndex(){
        $now = now();
        $explodeDate = explode("-",$now);
        $firstLimit = $explodeDate[1]; // month
        $secondLimit = ($firstLimit + 1);

        $firstYear = $explodeDate[0]; // year
        $secondYear = $firstYear;
        
        if($secondLimit < 10){
            $secondLimit = 0 . ($firstLimit + 1);
        }
        if($firstLimit == 12){
            $secondYear = $firstYear + 1;
            $secondLimit = "01";
        }

        $firstQueryLimit = $firstYear . "-" . $firstLimit . "-01 00:01:01";
        $secondQueryLimit = $secondYear . "-" . $secondLimit . "-01 00:01:01";
        
        $balance = Balance::where('date','>',$firstQueryLimit)->where('date','<',$secondQueryLimit)->get();

        $totalMonthlyIncome = 0;
        $totalMonthlySpend = 0;
        $totalBalance = 0;
        
        foreach($balance as $key => $value){
            $totalMonthlyIncome += $value['incoming_balance'];
            $totalMonthlySpend += $value['spend_balance'];
            $totalBalance = $value['total_balance'];
        }

        return response()->json([
            'data'=>[
                'month'=>$firstLimit,
                'year'=>$firstYear,
                'totalBalance'=>$totalBalance,
                'monthlyIncome'=>$totalMonthlyIncome,
                'monthlySpend'=>$totalMonthlySpend
            ]
        ],200);
    }

}
