<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Validator,Hash,Auth,Session;

class UserController extends Controller
{
   

    public function index()
    {
        $users = User::with('countries','states','cities')->get();
        $data = Country::get(["name", "id"]);
        return view('user-form', compact('users','data'));
    }

    public function states(Request $request) 
    { 
        $data['states'] = State::where("country_id", $request->country_id)->get(["name", "id"]); 
        return response()->json($data); 
    }

    public function cities(Request $request) 
    { 
        $data['cities'] = City::where("state_id", $request->state_id)->get(["name", "id"]); 
        return response()->json($data); 
    }

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'required', 
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $user = new User([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'country' => $request->country,
        'state' => $request->state,
        'city' => $request->city,
    ]);

    if ($request->hasFile('profile_image')) {
        $image = $request->file('profile_image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images'), $imageName);
        $user->profile_image = $imageName;
    }

    $user->save();

    return response()->json(['success' => true, 'message' => 'User data saved successfully']);
}


}

