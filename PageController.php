<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Page::select('id', 'name', 'link')->orderBy('order')->get();
        return view('pages.index', ['pages' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.create');
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
            'name' => 'required|string|max:255|min:3|unique:pages,name',
            'link' => 'required|string|max:255|min:3',
            'order' => 'required|numeric|integer',
            'status' => 'required|in:on,off'

        ]);
        Page::create($request->except(['_token']));
        return redirect()->route('pages.index')->with('success', 'Page Has Been Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $row = Page::findorfail($id);
        // dd($id);
        // return redirect()->route('pages.index');
        return view('pages.show', ['page' => $row]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $row = Page::find($id);
        return view('Pages.edit', ['Page' => $row]);
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
        $row = Page::find($id);
        if ($row) {
            //validations
            $request->validate([
                'name' => 'required|string|max:255|min:3|unique:pages,name,'.$id,
                'link' => 'required|string|max:255|min:3',
                'order' => 'required|integer|numeric',
                'status' => 'required|in:on,off'

            ]);
            $row->update($request->except(['_token', '_method']));
            return redirect()->route('pages.index')->with('success', 'Page Has Been Updated Successfully');
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
        if ($row = Page::find($id)) {
            $row->delete();
            return redirect()->route('pages.index')->with('success', 'Page Has Been Deleted Successfully');
        }
        return abort('404');
    }
    
}
