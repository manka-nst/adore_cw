<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Client::select('id', 'name')->get();
        return view('clients.index', ['clients'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255|min:3|unique:clients,name',

        ]);
        Client::create($request->except(['_token']));
        return redirect()->route('clients.index')->with('success', 'Client Has Been Created Successfully');


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $row = Client::findorfail($id);
        return view('clients.show',['client' => $row]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $row = Client::find($id);
        return view('clients.edit', ['client'=>$row]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($row = Client::find($id)) {
            $request->validate([
                'name'=>'required|string|max:255|min:3|unique:clients,name,'.$id

            ]);
            $row->update($request->except('_token'));
            return redirect()->route('clients.index')->with('success', 'Client Has Been Updated Successfully');



        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($row = Client::find($id)){
            $row->delete();
            return redirect()->route('clients.index')->with('success', 'Client Has Been Deleted Successfully');

        }
        return abort('404');
    }
}
