<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TeamResource;


class ApiTeamController extends Controller
{
    public function index()
    {
        $teams = Team::all();
       // return response()->json($pages);
       //or use resource to return data
       return TeamResource::collection($teams);
    }

    public function show($id)
    {
        $team = Team::findorfail($id);
        return new TeamResource($team);
    }

    public function store(Request $request)
    {
        //validations
        $validate = Validator::make($request->all(), [
            'name'=>'required|string|max:255|min:3|unique:teams,name',
            'link'=>'required|string|max:255|min:3|url',
             'job'=> 'required|string|max:255|min:3',
             'description'=> 'required|string',
             'facebook_icon'=>'required|string|max:255|min:3',
             'twitter_icon'=>'required|string|max:255|min:3',
             'linkedIn'=>'required|string|max:255|min:3',
             'status'=> 'required|in:on,off'

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
        $image->move('images/team', $image_name);

        Team::create([
            "name" => $request->name,
            "job" => $request->job,
            "link" => $request->link,
            "status" => $request->status,
            "description" =>$request->description,
            "image" => $image_name

        ]);
    }
       // Team::create($request->except(['_token', 'image']));
       return response()->json('Data Has Been Stored Successfully');
    }
    public function update(Request $request, $id)
    {
        if($row = Team::find($id)) {
              //validations
              $validate = Validator::make($request->all(), [
            'name'=>'required|string|max:255|min:3|unique:teams,name,'.$id,
            'link'=>'required|string|max:255|min:3|url',
             'job'=> 'required|string|max:255|min:3',
             'description'=> 'required|string',
             'facebook_icon'=>'required|string|max:255|min:3',
             'twitter_icon'=>'required|string|max:255|min:3',
             'linkedIn'=>'required|string|max:255|min:3',
             'status'=> 'required|in:on,off'

        ]);
        $data = $request->except(['image', '_token']);
        if($request->hasfile('image')) {
            $image = $request->file('image');
            $validate = Validator::make($request->all(), [
                'image' => 'image|mimes:png,jpg,svg,gif|max:2048'

            ]);
            if ($validate->fails()) {
                return response()->json($validate->errors());
            }
            $image_name = rand(). '.' .$image->getClientOriginalExtension();
            $image->move('images/team', $image_name);
            $data['image'] = $image_name;
            if($row->image) {
                unlink('images/team/'.$row->image);
            }
        }
    }
       $row->update($data);
       // $row->update($request->except(['_token','_method']));
       return response()->json('Data Has Been updated Successfully');
    }

    public function destroy($id)
    {
        if($row = Team::find($id)) {
            if ($row->image) {

                unlink('images/team/'.$row->image);
            }
            $row->delete();
            return response()->json('Data Has Been Deleted Successfully');
        }
        return response()->json('Data Has Not Been Found');
    }
}
