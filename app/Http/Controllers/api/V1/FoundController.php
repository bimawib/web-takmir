<?php

namespace App\Http\Controllers\api\V1;

use App\Models\Found;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFoundRequest;
use App\Http\Resources\V1\FoundResource;
use App\Http\Requests\UpdateFoundRequest;
use App\Http\Resources\V1\FoundCollection;

class FoundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new FoundCollection(Found::paginate(5));
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
     * @param  \App\Models\Found  $found
     * @return \Illuminate\Http\Response
     */
    public function show(Found $found)
    {
        return new FoundResource($found);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Found  $found
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Found $found)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Found  $found
     * @return \Illuminate\Http\Response
     */
    public function destroy(Found $found)
    {
        //
    }
}
