<?php

namespace App\Http\Controllers\api\V1;

use App\Models\AgendaDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreAgendaDetailRequest;
use App\Http\Requests\V1\UpdateAgendaDetailRequest;

class AgendaDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Models\AgendaDetail  $agendaDetail
     * @return \Illuminate\Http\Response
     */
    public function show(AgendaDetail $agendaDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AgendaDetail  $agendaDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AgendaDetail $agendaDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AgendaDetail  $agendaDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(AgendaDetail $agendaDetail)
    {
        //
    }
}
