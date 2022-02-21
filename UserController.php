<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {

        // //     //lazy loading
        $users = User::get();
        //  dd($users);
        return view('users.index', ['users' => $users]);


        //     $users = User::all();
        //   // dd($users);
        //     foreach($users as $user) {
        //         dd($user->profile);
        //     }


        // //           //eagerloading
        // $users = User::with('profile')->get();
        // dd($users->profile);


    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        //validations
        $request->validate([
            'name' => 'required|string|max:255|min:3|unique:users,name',
            'email' => 'required|string|max:255|unique:users,email',
            'password' => 'required|min:8|',

        ]);

        $data = $request->except(['_token']);
        $role = Role::where('name', $request->role)->first()->id;
        $request->role_id = $role;

        $data['role_id'] = $role;
        $data['password'] = Hash::make($data['password']);

        User::create($data);
        return redirect()->route('users.index')->with('success', 'User Has Been Created Successfully');
    }

    public function destroy($id)
    {
        if ($row = User::find($id)) {
            if ($row->image) {

                unlink('images/user/' . $row->image);
            }
            $row->delete();
            return redirect()->route('users.index')->with('success', 'User Has Been Deleted Successfully');


        }
        return abort('404');


    }
}
