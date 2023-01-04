<?php

namespace App\Http\Controllers\api\V1;

use App\Models\Agenda;
use Illuminate\Http\Request;
use App\Filters\V1\AgendaFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAgendaRequest;
use App\Http\Resources\V1\AgendaResource;
use App\Http\Requests\UpdateAgendaRequest;
use App\Http\Resources\V1\AgendaCollection;

class AgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = new AgendaFilter();
        $queryItems = $filter->transform($request); // ['nama kolom', 'operator ex : like, <, =', 'value']

        if(count($queryItems) == 0){
            return new AgendaCollection(Agenda::paginate(5));
        } else {
            $agenda = Agenda::where($queryItems)->paginate(5);

            return new AgendaCollection($agenda->appends($request->query()));
        }
        
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
     * @param  \App\Models\Agenda  $agenda
     * @return \Illuminate\Http\Response
     */
    public function show(Agenda $agenda)
    {
        return new AgendaResource($agenda);
        // should use slug instead for public reading
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Agenda  $agenda
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Agenda $agenda)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Agenda  $agenda
     * @return \Illuminate\Http\Response
     */
    public function destroy(Agenda $agenda)
    {
        //
    }
}
