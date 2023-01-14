<?php

// namespace App\Http\Controllers;

// use App\Models\Blog;
// use Illuminate\Http\Request;
// use App\Http\Resources\V1\BlogResource;

namespace App\Http\Controllers\api\V1;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreBlogRequest;
use App\Http\Resources\V1\BlogResource;
use App\Http\Requests\V1\UpdateBlogRequest;
use App\Http\Resources\V1\BlogCollection;
use App\Filters\V1\BlogFilter;

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
            $blogs = Blog::where($queryItem)->where('is_verified',1)->paginate();

            return new BlogCollection($blogs->appends($request->query()));
        }

        // gonna make 1 condition flow for admin and owner so they can see unverified blog

        // Blog::where()->with('user'); // kayanya setelah update terbaru with user ini bisa langsung dipake tanpa dipanggil wkwkkwawkoawkooawk
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBlogRequest $request)
    {
        $request['user_id'] = 14;
        // auth('sanctum')->user()->id;

        return new BlogResource(Blog::create($request->all()));
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
        // should use slug instead for public reading
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blog $blog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        //
    }
    
    public function slug($slug){
        $blog = Blog::where('slug',$slug)->first();
        return new BlogResource($blog);
    }
}
