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
        $user = auth('sanctum')->user();

        if($user->is_verified == 0){
            return response()->json([
                'error'=>[
                    'status'=>403,
                    'message'=>'You dont have ability to store lost item!'
                ]
            ],403);
        }

        $request['user_id'] = auth('sanctum')->user()->id;
        $request['is_returned']=0;

        // implement slugbabel here
        $request['slug'] = $this->slugCreate($request->title);

        $lost = Lost::create($request->all());
        $request['slug'] = $lost->slug . $lost->id;
        $lost->update($request->all());

        return new LostResource($lost);
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
        $user = auth('sanctum')->user();

        if($user->is_admin == 0){
            if($user->is_verified == 0 || $user->id != $lost->user_id){
                return response()->json([
                    'error'=>[
                        'status'=>403,
                        'message'=>'You dont have ability to update blog!'
                    ]
                ],403);
            }
        }

        $request['slug'] = $this->slugCreate($request->title, $lost->title, $lost->id) ?? $lost->slug;

        $lost->update($request->all());

        // return $lost;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lost  $lost
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lost $lost)
    {
        Lost::destroy($lost->id);
    }

    public function slugCreate($name, $name_before = '', $model_id = ''){
        $splitter = explode(" ", $name);
        $attacher = implode("-", array_splice($splitter,0,3));

        $splitter_2 = explode(" ", $name_before);
        $attacher_2 = implode("-", array_splice($splitter_2,0,3));

        if($name_before != '' && strtolower($attacher) == strtolower($attacher_2)){
            return null;
        }

        $randomizer = rand(100,999);
        $existing_id = $model_id ?? null;

        $slug = $attacher."-".$randomizer.$model_id;
        return strtolower($slug);
    }
}
