<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Brand;
use App\Http\Resources\BrandResource;


class ApiBrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all();
        return BrandResource::collection($brands);
    }
    public function show($id)
    {
        $brand = Brand::findorfail($id);
        return new BrandResource($brand);
    }
    public function store(Request $request)
    {
       // dd($request->all());
        if ($request->hasfile('image')) {
        $validate = Validator::make($request->all(),[
            'image' => 'image|mimes:png,jpg,svg,gif|max:2048'
        ]);
        if ($validate->fails()) {
            return response()->json($validate->errors());
        }
        $image = $request->file('image');
        $image_name = rand(). '.' .$image->getClientOriginalExtension();
        $image->move('images/brands', $image_name);


        Brand::create([
            "image" =>$image_name

        ]);
    }
        return response()->json('Data Has Been Stored Successfully');

    }

    public function update(Request $request, $id)
    {
        if ($row = Brand::find($id)) {
            $data = $request->except(['_token']);

            if($request->hasfile('image')) {
                $image = $request->file('image');
                $validate = Validator::make($request->all(),[
                    'image' => 'image|mimes:png,jpg,svg,gif|max:2048'

                ]);
                if ($validate->fails()) {
                    return response()->json($validate->errors());

                }
                $image_name = rand(). '.' .$image->getClientOriginalExtension();
                $image->move('images/brands', $image_name);
                $data['image'] = $image_name;
                if($row->image) {
                    unlink('images/brands/'.$row->image);
                }
            }
        }
           $row->update($data);
           return response()->json('Data Has Been Updated Successfully');

    }
    public function destroy($id)
    {
        if ($row = Brand::find($id)) {
            if($row->image) {

                unlink('images/brands/'. $row->image);
            }
            $row->delete();
            return response()->json('Data Has Been Deleted Successfully');
        }
        return response()->json('Data Not Found');

    }

}
