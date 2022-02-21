<?php

namespace App\Http\Controllers;

use App\Models\About;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = About::select('id', 'title', 'description', 'image', 'year')->get();
        return view('abouts.index', ['abouts' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('abouts.create');
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
            'title' => 'required|string|max:255|min:3|unique:abouts,title',
            'description' => 'required|string',
            'status' => 'required|in:on,off',
            'year' => 'required|string',
           // 'link' => 'required|string|max:255|min:3|url',



        ]);
        if ($request->hasfile('image')) {
            $request->validate([
                'image' => 'image|mimes:png,jpg,svg,gif|max:2048'
            ]);
            $image = $request->file('image');
            $image_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move('images/about', $image_name);
           // $data['image'] = $image_name;
           // $data = $request->except(['_token', 'image']);
           About::create([
            "title" => $request->title,
            "status" => $request->status,
            "description" =>$request->description,
            'year' =>$request->year,
            "image" => $image_name

        ]);
     }
        return redirect()->route('abouts.index')->with('success', 'About Has Been Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\About  $about
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $row = About::findorfail($id);
        // dd($about);
        // return redirect()->route('abouts.index');
        return view('abouts.show', ['about' => $row]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\About  $about
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $row = About::find($id);
        return view('abouts.edit', ['about' => $row]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\About  $about
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($row = About::find($id)) {
            //validations
            $request->validate([
                'title' => 'required|string|max:255|min:3|unique:abouts,title,'. $id,
                'description' => 'required|string',
                'status' => 'required|in:on,off',
                'year' => 'required|string',


            ]);
            $data = $request->except(['_token', 'image']);
            if ($request->hasfile('image')) {
                $image = $request->file('image');
                $request->validate([
                    'image' => 'image|mimes:png,jpg,svg,gif|max:2048'
                ]);
                $image_name = rand() . '.' . $image->getClientOriginalExtension();
                $image->move('images/about', $image_name);
                $data['image'] = $image_name;
                if ($row->image) {
                    unlink('images/about/' . $row->image);
                }
            }
        }
        $row->update($data);
        return redirect()->route('abouts.index')->with('success', 'About Has Been Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\About  $about
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($row = About::find($id)) {
            if($row->image) {

                unlink('images/about/'.$row->image);
            }
            $row->delete();
            return redirect()->route('abouts.index')->with('success', 'About Has Been Deleted Successfully');
        }
        return abort('404');
    }
}
