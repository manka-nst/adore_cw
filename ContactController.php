<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Contact::select('id','name', 'email', 'phone', 'message')->get();
        return view ('contacts.index', ['contacts' => $data]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255|min:3',
            'phone' => 'required|integer',
            'message' => 'required|string',
            'email' =>'required|string|max:255'
        ]);
        Contact::create($request->except(['_token']));
        return redirect()->route('welcome')->with('success', 'Message Has been submitted Successfully');
    }


}
