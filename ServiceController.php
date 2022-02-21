<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Service::select('id', 'name', 'icon')->get();
        return view('services.index',['services'=> $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('services.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validations
        $request->validate([
            'name'=>'required|string|max:255|min:3|unique:services,name',
            'icon'=>'required|string|max:255|min:3',
             'description'=> 'required|string',
             'status'=> 'required|in:on,off'

        ]);
        Service::create($request->except(['_token']));
        return redirect()->route('services.index')->with('success', 'Service Has Been Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $row = Service::findorfail($id);
       // dd($id);
       // return redirect()->route('services.index');
       return view('services.show', ['service' => $row]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {


        $row = Service::find($id);
        return view('services.edit', ['service'=>$row]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       if ($row = Service::find($id)) {
         //  dd($id);
           //validations
        $request->validate([
            'name'=>'required|string|max:255|min:3|unique:services,name,'.$id,
            'icon'=>'required|string|max:255|min:3',
             'description'=> 'required|string',
             'status'=> 'required|in:on,off'

        ]);

        $row->update($request->except(['_token','_method']));
        return redirect()->route('services.index')->with('success', 'Service Has Been Updated Successfully');

       }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($row = Service::find($id)) {
            $row->delete();
            return redirect()->route('services.index')->with('success', 'Service Has Been Deleted Successfully');
        }
        return abort('404');
    }

}
