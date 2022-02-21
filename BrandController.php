<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Brand::select('id', 'image')->get();
        return view('brands.index', ['brands'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('brands.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            if ($request->hasfile('image')) {
                $request->validate([
                    'image' => 'image|mimes:png,jpg,svg,gif|max:2048'

                ]);

            $image = $request->file('image');
            $image_name = rand(). '.' .$image->getClientOriginalExtension();
            $image->move('images/brands', $image_name);

            Brand::create([
                "image" =>$image_name

            ]);
        }
        return redirect()->route('brands.index')->with('success', 'Brand Has Been Created Successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $row = Brand::findorfail($id);
        return view('brands.show', ['brand' =>$row]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $row = Brand::find($id);
        return view('brands.edit', ['brand' =>$row]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($row = Brand::find($id)) {
            $data = $request->except(['_token']);

            if($request->hasfile('image')) {
                $image = $request->file('image');
                $request->validate([
                    'image' => 'image|mimes:png,jpg,svg,gif|max:2048'

                ]);
                $image_name = rand(). '.' .$image->getClientOriginalExtension();
                $image->move('images/brands', $image_name);
                $data['image'] = $image_name;
                if($row->image) {
//                    unlink('images/brands/'.$row->image);
                }
            }
        }
           $row->update($data);
       return redirect()->route('brands.index')->with('success', 'Brand Has Been Updated Successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($row = Brand::find($id)) {
            if ($row->image) {

                unlink('images/brands/'.$row->image);
            }
            $row->delete();
            return redirect()->route('brands.index')->with('success', 'Brand Has Been Deleted Successfully');


        }
        return abort('404');
    }

}
