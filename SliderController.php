<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Slider::select('id', 'title','description','image')->get();
        return view('sliders.index', ['sliders' => $data]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sliders.create');
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
            'title' => 'required|string|max:255|min:3|unique:sliders,title',
            'description' => 'required|string'
        ]);
        if ($request->hasfile('image')) {
            $request->validate([

                'image' => 'image|mimes:png,jpg,svg,gif|max:2048'

            ]);
            $image = $request->file('image');
            $image_name = rand(). '.' .$image->getClientOriginalExtension();
            $image->move('images/slider', $image_name);
            Slider::create([
                'title'=> $request->title,
                'description' => $request->description,
                'image' => $image_name
            ]);
        }
        return redirect()->route('sliders.index')->with('success', 'slider Has Been Created Successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $row = Slider::findorfail($id);
        return view('sliders.show', ['slider' => $row]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $row = Slider::find($id);
        return view('sliders.edit', ['slider' => $row]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($row = Slider::find($id)) {
            $request->validate([
                'title' => 'required|string|max:255|min:3|unique:sliders,title,'.$id,
                'description' => 'required|string'
            ]);
            $data = $request->except('_token', 'image');
            if ($request->hasfile('image')) {
                $image = $request->file('image');
                $request->validate([

                    'image' => 'image|mimes:png,jpg,svg,gif|max:2048'

                ]);
                $image_name = rand(). '.' .$image->getClientOriginalExtension();
                $image->move('images/slider', $image_name);
                $data['image'] = $image_name;
                if ($row->image) {
                    unlink('images/slider/'.$row->image);
                }
            }

        }
        $row->update($data);
        return redirect()->route('sliders.index')->with('success', 'Slider Has Been Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($row = Slider::find($id)) {
            if ($row->image) {
                unlink ('images/slider/'. $row->image);
            }
            $row->delete();
            return redirect()->route('sliders.index')->with('success', 'slider Has Been Deleted Successfully');

        }
        return abort('404');
    }
}
