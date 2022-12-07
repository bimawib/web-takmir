<?php

namespace App\Http\Controllers\api\V1;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Resources\V1\BlogResource;
use App\Http\Requests\UpdateBlogRequest;
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
        $queryItem = $filter->transform($request); // [['column','operator','value']]

        if(count($queryItem)==0){
            return new BlogCollection(Blog::paginate(5));
        } else {
            return new BlogCollection(Blog::where($queryItem)->paginate(5));
        }

        // Blog::where()->with('user'); // kayanya setelah update terbaru with user ini bisa langsung dipake tanpa dipanggil wkwkkwawkoawkooawk
        
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
     * @param  \App\Http\Requests\StoreBlogRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBlogRequest $request)
    {
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBlogRequest  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBlogRequest $request, Blog $blog)
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
