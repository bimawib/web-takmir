<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserValidation;

class UserValidationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($code)
    {
        $now = now();
        $validation = UserValidation::where('validation_code',$code)->first();

        if(isset($validation) != 1){
            $status = "VALIDATION CODE NOT VALID. FAILED TO VERIFY YOUR ACCOUNT!";
        } else {
            if($validation->expiration_date > $now){
                $status = "CONGRATULATIONS, YOUR ACCOUNT HAS BEEN VERIFIED";
                $userUpdate['is_verified'] = 1;
                User::where('id',$validation->user_id)->update($userUpdate);
                UserValidation::where('user_id',$validation->user_id)->delete();
            } else {
                $status = "VALIDATION CODE NOT VALID. FAILED TO VERIFY YOUR ACCOUNT!";
                UserValidation::where('user_id',$validation->user_id)->delete();
            }
        }

        return view('validation',[
            "title" => "Account Verification",
            "status"=> $status
        ]);
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
     * @param  \App\Http\Requests\StoreUserValidationRequest  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserValidation  $userValidation
     * @return \Illuminate\Http\Response
     */
    public function show(UserValidation $userValidation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserValidation  $userValidation
     * @return \Illuminate\Http\Response
     */
    public function edit(UserValidation $userValidation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserValidationRequest  $request
     * @param  \App\Models\UserValidation  $userValidation
     * @return \Illuminate\Http\Response
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserValidation  $userValidation
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserValidation $userValidation)
    {
        //
    }
}
