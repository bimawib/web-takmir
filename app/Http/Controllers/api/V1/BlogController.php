<?php

// namespace App\Http\Controllers;

// use App\Models\Blog;
// use Illuminate\Http\Request;
// use App\Http\Resources\V1\BlogResource;

namespace App\Http\Controllers\api\V1;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Filters\V1\BlogFilter;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\BlogResource;
use App\Http\Resources\V1\BlogCollection;
use App\Http\Requests\V1\StoreBlogRequest;
use App\Http\Requests\V1\UpdateBlogRequest;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = new BlogFilter();
        $queryItem = $filter->transform($request); // [['column','operator','value']], ex = ('title','like','puasa')

        if(count($queryItem)==0){
            return new BlogCollection(Blog::where('is_verified',1)->paginate());
        } else {
            $blogs = Blog::where($queryItem)->where('is_verified',1)->paginate(); //pake where where tapi kasih nilai default null;

            return new BlogCollection($blogs->appends($request->query()));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBlogRequest $request)
    {
        $user = auth('sanctum')->user();

        if($user->is_verified == 0){
            return response()->json([
                'error'=>[
                    'status'=>403,
                    'message'=>'You dont have ability to store blog!'
                ]
            ],403);
        }

        $request['user_id'] = auth('sanctum')->user()->id;

        $autoVerified = auth('sanctum')->user()->is_admin;
        $request['is_verified'] = $autoVerified;

        $request['slug'] = $this->slugCreate($request->title);

        $blog = Blog::create($request->all());

        $request['slug'] = $blog->slug . $blog->id;

        $blog->update($request->all());

        return new BlogResource($blog);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        return new BlogResource($blog);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        // make is_verified only to admin and owner (later)
        $user = auth('sanctum')->user();

        if($user->is_admin == 0){
            if($user->is_verified == 0 || $user->id != $blog->user_id){
                return response()->json([
                    'error'=>[
                        'status'=>403,
                        'message'=>'You dont have ability to update blog!'
                    ]
                ],403);
            }
        }

        if($user->is_admin == 0){
            $request['is_verified'] = $blog->is_verified;
        } elseif($user->is_admin == 1 || $user->is_owner == 1){
            $request['is_verified'] = $request['isVerified'];
        }

        if(isset($request->title) == 1){
            $request['slug'] = $this->slugCreate($request->title, $blog->title, $blog->id);
        } else {
            $request['slug'] = $blog->slug;
        }

        $blog->update($request->all());

        // return $blog;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        $user = auth('sanctum')->user();

        if($user->is_admin == 0){
            if($user->is_verified == 0 || $user->id != $blog->user_id){
                return response()->json([
                    'error'=>[
                        'status'=>403,
                        'message'=>'You dont have ability to update blog!'
                    ]
                ],403);
            }
        }
        Blog::destroy($blog->id);
    }

    public function publicIndex(Request $request){
        $filter = new BlogFilter();
        $queryItem = $filter->transform($request); // [['column','operator','value']], ex = ('title','like','puasa')
        
        $isVerified = 1;

        if(count($queryItem)==0){
            return new BlogCollection(Blog::where('is_verified',$isVerified)->paginate());
        } else {
            $blogs = Blog::where($queryItem)->where('is_verified',$isVerified)->paginate();

            return new BlogCollection($blogs->appends($request->query()));
        }
    }

    public function dashboardIndex(Request $request)
    {
        $filter = new BlogFilter();
        $queryItem = $filter->transform($request); // [['column','operator','value']], ex = ('title','like','puasa')

        // return $queryItem;
        $userIdField = 'user_id';
        $userId = auth('sanctum')->user()->id;

        $verifiedColumn = null;
        $verifiedStatus = null;

        $isAdmin = auth('sanctum')->user()->is_admin;
        if($isAdmin == 1 && isset($request['forValidation'])){
            
            $validatedVerified = $request->validate(['forValidation'=>Rule::in([0,1])]);

            $userIdField = null;
            $userId = null;

            $verifiedColumn = 'is_verified';
            $verifiedStatus = $validatedVerified['forValidation'];
        }
        // return $request->query();
        if(count($queryItem)==0){

            $blogs = Blog::where($userIdField,$userId)->where($verifiedColumn, $verifiedStatus)->paginate();

            return new BlogCollection($blogs->appends($request->query()));
        } else {

            $blogs = Blog::where($userIdField,$userId)->where($queryItem)->where($verifiedColumn, $verifiedStatus)->paginate();

            return new BlogCollection($blogs->appends($request->query()));
        }

    }
    
    public function withSlug($slug){
        $blog = Blog::where('slug',$slug)->first();
        return new BlogResource($blog);
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
