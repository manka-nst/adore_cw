<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;


class PortfolioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data = Portfolio::select('id', 'name', 'description','image', 'service_id','client_id')->get();
        // foreach ($data as $item) {
        //     dd($item->service);
        // }
        return view('portfolios.index',['portfolios'=> $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('portfolios.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      // dd($request);
        //validations
        $request->validate([
            'name'=>'required|string|max:255|min:3',
             'description'=> 'required|string|max:3000|min:5',
             'status'=> 'required|in:on,off',
             'service_id' =>'required|integer',
             'client_id' => 'required|integer'

        ]);
        if ($request->hasfile('image')) {
            $request->validate([
                'image' => 'image|mimes:png,jpg,svg,gif|max:2048'

            ]);
            $image = $request->file('image');
            $image_name = rand(). '.'.$image->getClientOriginalExtension();
            $image->move('images/portfolio', $image_name);
            Portfolio::create([
                'name'=>$request->name,
                'description'=>$request->description,
                'service_id'=>$request->service_id,
                'client_id'=>$request->client_id,
                'image'=>$image_name
            ]);

        }
       // Portfolio::create($request->except(['_token']));
        return redirect()->route('portfolios.index')->with('success', 'Portfolio Has Been Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $row = Portfolio::findorfail($id);
       // dd($id);
       // return redirect()->route('portfolios.index');
       return view('portfolios.show', ['portfolio' => $row]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {


        $row = Portfolio::find($id);
        return view('portfolios.edit', ['portfolio'=>$row]);
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
       // dd($request);
       if($row = Portfolio::find($id)){
            //validations
        $request->validate([
            'name'=>'required|string|max:255|min:3|',
             'description'=> 'required|string|max:3550|min:5',
             'status'=> 'required|in:on,off',
             'service_id' =>'required|integer',
             'client_id' => 'required|integer'

        ]);
        $data = $request->except(['_token']);
        if ($request->hasfile('image')) {
            $image = $request->file('image');
            $request->validate([
                'image' => 'image|mimes:png,jpg,svg,gif|max:2048'
            ]);
            $image_name = rand(). '.'.$image->getClientOriginalExtension();
            $image->move('images/portfolio', $image_name);
            $data['image'] = $image_name;
            if ($row->image) {
                unlink('images/portfolio/'.$row->image);
            }
         }

     }
        $row->update($data);
        return redirect()->route('portfolios.index')->with('success', 'Portfolio Has Been Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($row = Portfolio::find($id)) {
            if ($row->image) {

                unlink('images/portfolio/'.$row->image);
            }
            $row->delete();
            return redirect()->route('portfolios.index')->with('success', 'Portfolio Has Been Deleted Successfully');
        }
        return abort('404');
    }
    // public function getService($id)
    // {
    //     if ($portfolio = Portfolio::find($id)) {
    //         return view ('portfolios.service', ['service' => $portfolio->service]);
    //     }
    // }
}
