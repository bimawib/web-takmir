<?php

namespace App\Http\Controllers\api\V1;

use App\Models\AgendaDetail;
use App\Http\Requests\StoreAgendaDetailRequest;
use App\Http\Requests\UpdateAgendaDetailRequest;
use App\Http\Controllers\Controller;

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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAgendaDetailRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAgendaDetailRequest $request)
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AgendaDetail  $agendaDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(AgendaDetail $agendaDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAgendaDetailRequest  $request
     * @param  \App\Models\AgendaDetail  $agendaDetail
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAgendaDetailRequest $request, AgendaDetail $agendaDetail)
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
