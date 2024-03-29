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
use Illuminate\Support\Arr;
use App\Http\Requests\V1\BulkStoreFoundRequest;

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
    public function store(StoreFoundRequest $request)
    {
        $user = auth('sanctum')->user();
        if($user->is_admin == 0){
            return response()->json([
                'error'=>[
                    'status'=>403,
                    'message'=>'You dont have ability to store found item!'
                ]
            ],403);
        }
        $request['user_id'] = auth('sanctum')->user()->id;
        $request['is_returned']=0;

        // implement slugbabel here
        $request['slug'] = $this->slugCreate($request->title);

        $found = Found::create($request->all());
        $request['slug'] = $found->slug . $found->id;
        $found->update($request->all());

        return new FoundResource($found);
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
    public function update(UpdateFoundRequest $request, Found $found)
    {
        $user = auth('sanctum')->user();
        if($user->is_admin == 0){
            return response()->json([
                'error'=>[
                    'status'=>403,
                    'message'=>'You dont have ability to store found item!'
                ]
            ],403);
        }
        $request['slug'] = $this->slugCreate($request->title, $found->title, $found->id) ?? $found->slug;

        $found->update($request->all());

        // return $found;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Found  $found
     * @return \Illuminate\Http\Response
     */
    public function destroy(Found $found)
    {
        $user = auth('sanctum')->user();
        if($user->is_admin == 0){
            return response()->json([
                'error'=>[
                    'status'=>403,
                    'message'=>'You dont have ability to store found item!'
                ]
            ],403);
        }
        Found::destroy($found->id);
    }
    
    public function bulkStore(BulkStoreFoundRequest $request){
        
        $arrayRequest = $request->toArray();
        $arrayCount = count($arrayRequest);

        $other_input = [];
        foreach($request->toArray() as $req){
            $req['user_id'] = 13; // auth('sanctum')->user()->id;
            $req['created_at'] = now();
            $other_input[] = $req;
        }
        $request->merge($other_input);

        $bulk = collect($request->all())->map(function($arr, $key){
            return Arr::except($arr, ['createdAt']);
        });
        // return $bulk[3]['user_id'];

        Found::insert($bulk->toArray());
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

    public function dashboardIndex(Request $request){
        $filter = new FoundFilter();
        $queryItems = $filter->transform($request); // ['nama kolom', 'operator ex : like, <, =', 'value']

        $isReturned = $request->query('isReturned');
        $Found = Found::where($queryItems);

        // dd(isset($isReturned));
        $isAdmin = auth('sanctum')->user()->is_admin;
        if($isAdmin == 0){
            return response()->json([
                'error'=>[
                    'status'=>403,
                    'message'=>'You dont have ability to store found item!'
                ]
            ],403);
        }

        if(isset($isReturned) && $isReturned == 0){
            return new FoundCollection($Found->where('is_returned',0)->paginate()->appends($request->query()));
        } elseif(isset($isReturned) && $isReturned == 1){
            return new FoundCollection($Found->where('is_returned',1)->paginate()->appends($request->query()));
        } else {
            return new FoundCollection($Found->paginate()->appends($request->query()));
        }
    }

    // public function dashboardIndex(Request $request)
    // {
    //     $filter = new BlogFilter();
    //     $queryItem = $filter->transform($request); // [['column','operator','value']], ex = ('title','like','puasa')

    //     // return $queryItem;
    //     $userIdField = 'user_id';
    //     $userId = auth('sanctum')->user()->id;

    //     $verifiedColumn = null;
    //     $verifiedStatus = null;

    //     $isAdmin = auth('sanctum')->user()->is_admin;
    //     if($isAdmin == 1 && isset($request['forValidation'])){
            
    //         $validatedVerified = $request->validate(['forValidation'=>Rule::in([0,1])]);

    //         $userIdField = null;
    //         $userId = null;

    //         $verifiedColumn = 'is_verified';
    //         $verifiedStatus = $validatedVerified['forValidation'];
    //     }
    //     // return $request->query();
    //     if(count($queryItem)==0){

    //         $blogs = Blog::where($userIdField,$userId)->where($verifiedColumn, $verifiedStatus)->paginate();

    //         return new BlogCollection($blogs->appends($request->query()));
    //     } else {

    //         $blogs = Blog::where($userIdField,$userId)->where($queryItem)->where($verifiedColumn, $verifiedStatus)->paginate();

    //         return new BlogCollection($blogs->appends($request->query()));
    //     }

    // }
}
