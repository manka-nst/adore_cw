<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;


class ApiUserController extends Controller
{
    public function register(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'name' => 'required|string|min:3|max:250',
            'email' =>'required|string|email|max:255|unique:users,email',
            'password' =>'required|min:8|',
            'role_id' => 'required|integer'

        ]);
        if ($validate->fails()) {
            return response()->json($validate->errors());
        }
       $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role_id' =>  Role::select('id')->where('name', 'user')->first()->id

        ]);
        $token = $user->createToken('auth');
        return response()->json(['token' => $token->plainTextToken]);


    }

}
