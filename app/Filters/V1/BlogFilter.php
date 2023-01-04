<?php 

namespace App\Filters\V1;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;

class BlogFilter extends ApiFilter {
    protected $allowedParam = [
        'title'=>['eq','like'],
        'body'=>['eq','like'],
        'slug'=>['eq'],
    ];

    protected $columnMap = [
        // 'isVerified'=>'is_verified'
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