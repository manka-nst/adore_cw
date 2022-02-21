<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Contact;
use App\Http\Resources\ContactResource;


class ApiContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::all();
        return ContactResource::collection($contacts);


    }
   
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'name'=>'required|string|max:255|min:3',
            'phone' => 'required|integer',
            'message' => 'required|string',
            'email' =>'required|string|email|max:255'
        ]);
        if ($validate->fails()) {
            return response()->json($validate->errors());
        }
        Contact::create($request->except(['_token']));
        return response()->json('Data Has Been Stored Successfully');

    }



}
