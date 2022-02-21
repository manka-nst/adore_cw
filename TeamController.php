<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Team::select('id', 'name', 'link', 'job', 'image')->get();
        return view('teams.index', ['teams' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('teams.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validations
        $request->validate([
            'name' => 'required|string|max:255|unique:teams,name',
            'link' => 'required|string|max:255|url',
            'job' => 'required|string|max:255',
            'description' => 'required|string',
            'facebook_icon' => 'required|string|max:255',
            'twitter_icon' => 'required|string|max:255',
            'linkedIn' => 'required|string|max:255',
            'status' => 'required|in:on,off'

        ]);
        if ($request->hasfile('image')) {
            $request->validate([
                'image' => 'image|mimes:png,jpg,svg,gif|max:2048'

            ]);

            $image = $request->file('image');
            $image_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move('images/team', $image_name);

            Team::create([
                "name" => $request->name,
                "job" => $request->job,
                "link" => $request->link,
                "status" => $request->status,
                "description" => $request->description,
                'facebook_icon' => $request->facebook_icon,
                'twitter_icon' => $request->twitter_icon,
                'linkedIn' => $request->linkedIn,
                "image" => $image_name

            ]);
        }
        // Team::create($request->except(['_token', 'image']));
        return redirect()->route('teams.index')->with('success', 'team Has Been Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $row = Team::findorfail($id);
        // dd($id);
        // return redirect()->route('teams.index');
        return view('teams.show', ['team' => $row]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $row = Team::find($id);
        return view('teams.edit', ['team' => $row]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($row = Team::find($id)) {
            //validations
            $request->validate([
                'name' => 'required|string|max:255|unique:teams,name,' . $id,
                'link' => 'required|string|max:255|url',
                'job' => 'required|string|max:255',
                'description' => 'required|string',
                'facebook_icon' => 'nullable|string|max:255',
                'twitter_icon' => 'nullable|string|max:255',
                'linkedIn' => 'nullable|string|max:255',
                'status' => 'required|in:on,off'

            ]);
            $data = $request->except(['_token']);
            if ($request->hasfile('image')) {
                $image = $request->file('image');
                $request->validate([
                    'image' => 'image|mimes:png,jpg,svg,gif|max:2048'

                ]);
                $image_name = rand() . '.' . $image->getClientOriginalExtension();
                $image->move('images/team', $image_name);
                $data['image'] = $image_name;
                if ($row->image) {
                    unlink('images/team/' . $row->image);
                }
            }
        }
        $row->update($data);
        // $row->update($request->except(['_token','_method']));
        return redirect()->route('teams.index')->with('success', 'team Has Been Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($row = Team::find($id)) {
            if ($row->image) {

                unlink('images/team/' . $row->image);
            }
            $row->delete();
            return redirect()->route('teams.index')->with('success', 'team Has Been Deleted Successfully');


        }
        return abort('404');


    }
}
