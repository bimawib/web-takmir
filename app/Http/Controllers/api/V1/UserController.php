<?php

namespace App\Http\Controllers\api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UpdateUserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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

        $emailField = null;
        $emailQuery = null;
        if(isset($request['email'])){
            $emailField = 'email';
            $emailQuery = $request['email'];
        }

        $user = User::where($emailField,$emailQuery)->latest()->paginate();
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
    public function update(UpdateUserRequest $request, User $user)
    {

        // $ceking = $request['name'] ?? $user->name;
        // return $ceking;

        $userInfo = auth('sanctum')->user();
        if($user->id != $userInfo->id){
            if($userInfo->is_owner != 1){ 
                return response()->json([
                    'error'=>[
                        'status'=>403,
                        'message'=>'You dont have ability to update this user information!'
                    ]
                ],403);
            }
        }

        if($userInfo->is_owner == 1 && $user->id != $userInfo->id){
            $adminUpdate['is_verified'] = $request['isVerified'] ?? $user->is_verified;
            $adminUpdate['is_admin'] = $request['isAdmin'] ?? $user->is_admin;

            User::where('id',$user->id)->update($adminUpdate);
        }
        
        // if old or new password isset -> validate both of them with required,
        // $cek = password_verify($request['oldPassword'],$user->password);
        $userUpdate['password'] = $user->password;

        if(isset($request['oldPassword']) || isset($request['newPassword'])){
            $userValidation = $request->validate([
                'oldPassword'=>'required|string',
                'newPassword'=>'required|string|min:8'
            ]);
            if(password_verify($request['oldPassword'],$user->password) != 1){
                return response()->json([
                    'error'=>[
                        'status'=>406,
                        'message'=>'The old password does not match!'
                    ]
                ],406);
            } else {
                $userUpdate['password'] = bcrypt($request['newPassword']);
            }
        }

        if($user->id == $userInfo->id){
            $userUpdate['name'] = $request['name'] ?? $user->name;
            User::where('id',$user->id)->update($userUpdate);
        }

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
