<?php

namespace App\Http\Controllers\api\V1;

use App\Models\Lost;
use Illuminate\Http\Request;
use App\Filters\V1\LostFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLostRequest;
use App\Http\Resources\V1\LostResource;
use App\Http\Requests\UpdateLostRequest;
use App\Http\Resources\V1\LostCollection;

class LostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = new LostFilter();
        $queryItems = $filter->transform($request); // ['nama kolom', 'operator ex : like, <, =', 'value']

        $isReturned = $request->query('isReturned');
        $Lost = Lost::where($queryItems);

        // dd(isset($isReturned));
        
        return new LostCollection($Lost->paginate()->appends($request->query()));
        
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
