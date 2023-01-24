<?php

namespace App\Http\Controllers\api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userInfo = auth('sanctum')->user();

        if($userInfo->is_owner != 1){ 
            return response()->json([
                'error'=>[
                    'status'=>403,
                    'message'=>'You dont have ability to see this user information!'
                ]
            ],403);
        }

        $user = User::latest()->paginate();
        return $user;

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
     * @param  \App\Models\AgendaDetail  $agendaDetail
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $userInfo = auth('sanctum')->user();

        if($user->id != $userInfo->id){
            if($userInfo->is_owner != 1){ 
            return response()->json([
                'error'=>[
                    'status'=>403,
                    'message'=>'You dont have ability to see this user information!'
                ]
            ],403);
            }
        }

        return response()->json([
            'data'=>[
                'name'=>$user->name,
                'email'=>$user->email,
                // 'password'=>$user->password
            ]
        ],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // $cek = password_verify('edensuki',$user->password);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
    public function publicShow(User $user)
    {
        return response()->json([
            'data'=>[
                'name'=>$user->name,
                'email'=>$user->email
            ]
        ],200);
    }
}
