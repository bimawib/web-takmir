<?php

namespace App\Http\Controllers\api\V1;

use App\Models\Lost;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLostRequest;
use App\Http\Requests\UpdateLostRequest;
use App\Http\Resources\V1\LostCollection;
use App\Http\Resources\V1\LostResource;

class LostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new LostCollection(Lost::paginate());
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
     * @param  \App\Models\Lost  $lost
     * @return \Illuminate\Http\Response
     */
    public function show(Lost $lost)
    {
        return new LostResource($lost);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lost  $lost
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lost $lost)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lost  $lost
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lost $lost)
    {
        //
    }
}
