<?php

namespace App\Http\Controllers\api\V1;

use App\Models\Found;
use Illuminate\Http\Request;
use App\Filters\V1\FoundFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreFoundRequest;
use App\Http\Resources\V1\FoundResource;
use App\Http\Requests\V1\UpdateFoundRequest;
use App\Http\Resources\V1\FoundCollection;

class FoundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = new FoundFilter();
        $queryItems = $filter->transform($request); // ['nama kolom', 'operator ex : like, <, =', 'value']

        $isReturned = $request->query('isReturned');
        $Found = Found::where($queryItems);

        // dd(isset($isReturned));

        if(isset($isReturned) && $isReturned == 0){
            return new FoundCollection($Found->where('is_returned',0)->paginate()->appends($request->query()));
        } elseif(isset($isReturned) && $isReturned == 1){
            return new FoundCollection($Found->where('is_returned',1)->paginate()->appends($request->query()));
        } else {
            return new FoundCollection($Found->paginate()->appends($request->query()));
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
