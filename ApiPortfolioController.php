<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Portfolio;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PortfolioResource;

class ApiPortfolioController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::all();
        return PortfolioResource::collection($portfolios);
    }

    public function show($id)
    {
        $portfolio = Portfolio::findorfail($id);
        return new PortfolioResource($portfolio);

    }

    public function store(Request $request)
    {
       // dd($request->all());
        $validate = Validator::make($request->all(),[
            'name'=>'required|string|max:255|min:3|',
             'description'=> 'required|string|max:255|min:5',
             'status'=> 'required|in:on,off'
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
            $image->move('images/portfolio', $image_name);


            Portfolio::create([
                    "name" => $request->name,
                    "status" => $request->status,
                    "description" =>$request->description,
                    "client_id" =>$request->client_id,
                    "service_id" =>$request->service_id,
                    "image" => $image_name

            ]);
        }
            return response()->json('Data Has Been Stored Successfully');

    }

    public function update(Request $request, $id)
    {
       // dd($request);
       if($row = Portfolio::find($id)){
            //validations
            $validate = Validator::make($request->all(),[
            'name'=>'required|string|max:255|min:3',
             'description'=> 'required|string|max:3550|min:5',
             'status'=> 'required|in:on,off'

        ]);
        $data = $request->except(['_token']);
        if ($request->hasfile('image')) {
            $image = $request->file('image');
            $validate = Validator::make($request->all(),[
                'image' => 'image|mimes:png,jpg,svg,gif|max:2048'
            ]);
            if ($validate->fails()) {
                return response()->json($validate->errors());
            }
            $image_name = rand(). '.'.$image->getClientOriginalExtension();
            $image->move('images/portfolio', $image_name);
            $data['image'] = $image_name;
            if ($row->image) {
                unlink('images/portfolio/'.$row->image);
            }
         }

     }
        $row->update($data);
        return response()->json('Data Has Been Updated Successfully');
    }

    public function destroy($id)
    {
        if($row = Portfolio::find($id)) {
            if ($row->image) {

                unlink('images/portfolio/'.$row->image);
            }
            $row->delete();
            return response()->json('Data Has Been Deleted Successfully');
        }
        return response()->json('Data Has Not Been Found');
    }

}
