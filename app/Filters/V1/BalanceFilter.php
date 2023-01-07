<?php 

namespace App\Filters\V1;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;

class BalanceFilter extends ApiFilter {
    protected $allowedParam = [
        'title'=>['eq','like'],
        'isSpend'=>['eq'],
        'spendBalance'=>['lt','lte','gt','gte'],
        'incomingBalance'=>['lt','lte','gt','gte'],
        'totalBalance'=>['lt','lte','gt','gte']
    ];

    protected $columnMap = [
        'isSpend'=>'is_spend',
        'spendBalance'=>'spend_balance',
        'incomingBalance'=>'incoming_balance',
        'totalBalance'=>'total_balance'
    ];
    // this is for a query that has different typing than the column name in DB

    protected $operatorMap = [
        'eq'=>'=',
        'lt'=>'<',
        'lte'=>'<=',
        'gt'=>'>',
        'gte'=>'>=',
        'ne'=>'!=',
        'like'=>'like'
    ];

}