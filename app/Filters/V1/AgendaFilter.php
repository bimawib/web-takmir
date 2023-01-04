<?php 

namespace App\Filters\V1;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;

class AgendaFilter extends ApiFilter {
    protected $allowedParam = [
        'title'=>['eq','like'],
        'slug'=>['eq'],
    ];

    protected $columnMap = [
        'publishedAt'=>'published_at'
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