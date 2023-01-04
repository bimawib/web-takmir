<?php 

namespace App\Filters\V1;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;

class AgendaFilter extends ApiFilter {
    protected $allowedParam = [
        'title'=>['eq','like'],
        'slug'=>['eq'],
    ];

    // protected $columnMap = [
    //     'agendaDate'=>'agenda_date'
    // ];
    // this is for a query that has different typing than the column name in DB

    protected $operatorMap = [
        'eq'=>'=',
        'lt'=>'<',
        'lte'=>'<=',
        'gt'=>'>',
        'gte'=>'>=',
        'like'=>'like'
    ];

    public function transform(Request $request){
        $eloQuery = [];

        foreach ($this->allowedParam as $param => $operators){
            $query = $request->query($param);

                // dd($request->query);

            if(!isset($query)){
                continue;
            }

            // $column = $this->columnMap[$param] ?? $param;
            $column = $param;

            

            foreach ($operators as $operator){
                if (isset($query[$operator])){
                    if($operator == 'like'){
                        $eloQuery[] = [$column, $this->operatorMap[$operator], '%'.$query[$operator].'%'];
                    } else {
                        $eloQuery[] = [$column, $this->operatorMap[$operator], $query[$operator]];
                    }
                }
            }
        }

        return $eloQuery;
    }

}