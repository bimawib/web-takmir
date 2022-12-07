<?php 

namespace App\Filters;

use Illuminate\Http\Request;

class ApiFilter {
    protected $allowedParam = [];

    // protected $columnMap = [
    //     'agendaDate'=>'agenda_date'
    // ];

    protected $operatorMap = [];

    public function transform(Request $request){
        $eloQuery = [];

        foreach ($this->allowedParam as $param => $operators){
            $query = $request->query($param);

            if(!isset($query)){
                continue;
            }

            // $column = $this->columnMap[$param] ?? $param;
            $column = $param;

            foreach ($operators as $operator){
                if (isset($query[$operator])){
                    $eloQuery[] = [$column, $this->operatorMap[$operator], $query[$operator]];
                }
            }
        }

        return $eloQuery;
    }

}