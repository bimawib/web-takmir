<?php

namespace App\Http\Controllers\api\V1;

use App\Models\Lost;
use Illuminate\Http\Request;
use App\Filters\V1\LostFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreLostRequest;
use App\Http\Resources\V1\LostResource;
use App\Http\Requests\V1\UpdateLostRequest;
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

        if(isset($isReturned) && $isReturned == 0){
            return new LostCollection($Lost->where('is_returned',0)->paginate()->appends($request->query()));
        } elseif(isset($isReturned) && $isReturned == 1){
            return new LostCollection($Lost->where('is_returned',1)->paginate()->appends($request->query()));
        } else {
            return new LostCollection($Lost->paginate()->appends($request->query()));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLostRequest $request)
    {
        $request['user_id'] = 13; // auth('sanctum')->user()->id;
        $request['is_returned']=0;
        // implement slugbabel here

        return new LostResource(Lost::create($request->all()));
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
    public function update(UpdateLostRequest $request, Lost $lost)
    {
        $slug = $lost->slug;
        if(isset($request->slug) && $slug != $request->slug){
            $request->validate([
                'slug'=>'required|unique:losts|max:255'
            ]);
        }

        $lost->update($request->all());
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
