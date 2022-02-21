<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\About;
use App\Http\Resources\AboutResource;
use Illuminate\Support\Facades\Validator;


class ApiAboutController extends Controller
{
    public function index()
    {
        $abouts = About::all();
       // return response()->json($abouts);
       return AboutResource::collection($abouts);
    }
    public function show($id)
    {
        $about = About::findorfail($id);
        return response()->json($about);

       // return new AboutResource($about);
    }
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'title' => 'required|string|max:255|min:3|unique:abouts,title',
            'description' => 'required|string',
            'status' => 'required|in:on,off',
            'year' => 'required|string',
        ]);

         if ($request->hasfile('image')) {
        $validate = Validator::make($request->all(),[
            'image' => 'image|mimes:png,jpg,svg,gif|max:2048'
        ]);
        if ($validate->fails()) {
            return response()->json($validate->errors());
        }
        $image = $request->file('image');
        $image_name = rand(). '.' .$image->getClientOriginalExtension();
        $image->move('images/about', $image_name);


        About::create([
                "title" => $request->title,
                "status" => $request->status,
                "description" =>$request->description,
                'year' =>$request->year,
                "image" => $image_name

        ]);
    }
        return response()->json('Data Has Been Stored Successfully');

    }
    public function update(Request $request, $id)
    {
        if ($row = About::find($id)) {
            //validations
            $validate = Validator::make($request->all(),[
                'title' => 'required|string|max:255|min:3|unique:abouts,title,'. $id,
                'description' => 'required|string',
                'status' => 'required|in:on,off',
                'year' => 'required|string',
            ]);

            $data = $request->except(['_token', 'image']);
            if ($request->hasfile('image')) {
                $image = $request->file('image');
                $validate = Validator::make($request->all(),[
                    'image' => 'image|mimes:png,jpg,svg,gif|max:2048'
                ]);
                if ($validate->fails()) {
                    return response()->json($validate->errors());

                }

                $image_name = rand() . '.' . $image->getClientOriginalExtension();
                $image->move('images/about', $image_name);
                $data['image'] = $image_name;
                if ($row->image) {
                    unlink('images/about/' . $row->image);
                }
            }
        }
        $row->update($data);
        return response()->json('Data Has Been Updated Successfully');

    }
    public function destroy($id)
    {
        if ($row = About::find($id)) {
            if($row->image) {

                unlink('images/about/'. $row->image);
            }
            $row->delete();
            return response()->json('Data Has Been Deleted Successfully');
        }
        return response()->json('Data Not Found');

    }

}
