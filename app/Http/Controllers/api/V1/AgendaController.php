<?php

namespace App\Http\Controllers\api\V1;

use App\Models\Agenda;
use App\Models\AgendaDetail;
use Illuminate\Http\Request;
use App\Filters\V1\AgendaFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\AgendaResource;
use App\Http\Resources\V1\AgendaCollection;
use App\Http\Requests\V1\StoreAgendaRequest;
use App\Http\Requests\V1\UpdateAgendaRequest;

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

        $includeDetails = $request->query('includeDetails');
        $agenda = Agenda::where($queryItems);

        if(isset($includeDetails) && $includeDetails == 1){
            $agenda = $agenda->with('agenda_detail');
        }

        return new AgendaCollection($agenda->paginate()->appends($request->query()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAgendaRequest $request)
    {
        $request['user_id'] = 13; // auth('sanctum')->user()->id;
        $request['published_at'] = now();

        // return count(AgendaDetail::where('agenda_id',3)->get()); // untuk update うるかがすき terus dibuat for request count - detail count
        // validate all array from $request->agenda_detail[] later
        $create = Agenda::create($request->all());
        $with_detail = Agenda::where('slug',$request->slug)->with('agenda_detail')->first();

        return new AgendaResource($with_detail);
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
