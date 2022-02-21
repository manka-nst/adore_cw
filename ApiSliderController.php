<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\SliderResource;
use App\Models\Slider;
use Illuminate\Support\Facades\Validator;



class ApiSliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::all();
       // return response()->json($pages);
       //or use resource to return data
       return SliderResource::collection($sliders);
    }

    public function show($id)
    {
        $slider = Slider::findorfail($id);
        return new SliderResource($slider);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required|string|max:255|min:3|unique:sliders,title',
            'description' => 'required|string'
        ]);
        if ($request->hasfile('image')) {
            $validate = Validator::make($request->all(), [

                'image' => 'image|mimes:png,jpg,svg,gif|max:2048'

            ]);
            if ($validate->fails()) {
                return response()->json($validate->errors());
            }
            $image = $request->file('image');
            $image_name = rand(). '.' .$image->getClientOriginalExtension();
            $image->move('images/slider', $image_name);
            Slider::create([
                'title'=> $request->title,
                'description' => $request->description,
                'image' => $image_name
            ]);
        }
        return response()->json('Data Has Been Stored Successfully');

    }

    public function update(Request $request, $id)
    {
        if ($row = Slider::find($id)) {
            $validate = Validator::make($request->all(), [
                'title' => 'required|string|max:255|min:3|unique:sliders,title,'.$id,
                'description' => 'required|string'
            ]);
            $data = $request->except('_token', 'image');
            if ($request->hasfile('image')) {
                $image = $request->file('image');
                $validate = Validator::make($request->all(), [

                    'image' => 'image|mimes:png,jpg,svg,gif|max:2048'

                ]);
                if ($validate->fails()) {
                    return response()->json($validate->errors());
                }
                $image_name = rand(). '.' .$image->getClientOriginalExtension();
                $image->move('images/slider', $image_name);
                $data['image'] = $image_name;
                if ($row->image) {
                    unlink('images/slider/'.$row->image);
                }
            }

        }
        $row->update($data);
        return response()->json('Data Has Been Updated Successfully');
    }
    public function destroy($id)
    {
        if($row = Slider::find($id)) {
            if ($row->image) {

                unlink('images/slider/'.$row->image);
            }
            $row->delete();
            return response()->json('Data Has Been Deleted Successfully');
        }
        return response()->json('Data Has Not Been Found');
    }

}
