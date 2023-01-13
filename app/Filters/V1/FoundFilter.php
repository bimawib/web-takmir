<?php 

namespace App\Filters\V1;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;

class FoundFilter extends ApiFilter {
    protected $allowedParam = [
        'title'=>['eq','like'],
        'slug'=>['eq'],
    ];

    protected $columnMap = [
        'isReturned'=>'is_returned'
    ];

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