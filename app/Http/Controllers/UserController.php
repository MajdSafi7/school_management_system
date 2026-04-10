<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function Register(Request $request)
    {
        // $request->validate([
        //     'phone_number' => 'required|string|unique:users',
        //     'username' => 'required|string|max:255',
        //     'email' => 'required|string|unique:users|email',
        //     'password' => 'required|string|min:8|confirmed',
        //     'first_name' => 'required|string|max:255',
        //     'last_name' => 'required|string|max:255',
        //     'birthdate' => 'required'
        // ]);

          $validatedData = $request->validated();
        if ($request->hasFile('personal_photo')) {
            $path1 = $request->file('personal_photo')->store('user_photo', 'public');
            $validatedData['personal_photo'] = $path1;
        }
        if ($request->hasFile('id_photo')) {
            $path2 = $request->file('id_photo')->store('user_photo', 'public');
            $validatedData['id_photo'] = $path2;
        }



        $role='student';
        $user = User::create([
            'phone_number' => $request->phone_number,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role'=>$role,
        ]);
        $user->person()->create([
            'first_name'     => $request->first_name,
            'last_name'      => $request->last_name,
            'birthdate'      => $request->birthdate,
            'personal_photo' => $validatedData['personal_photo'] ,
            'id_photo'       => $validatedData['id_photo'] ,
        ]);

        // $token = $user->createToken('SchoolToken')->plainTextToken;
        return response()->json([
            'message' => 'User Registered Successfully',
            'user' => $user->load('person'),
            // 'token' => $token
        ], 201);
    }

    public function Login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'invalid email or password'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstorfail();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'User Login Successfully',
            'user' => $user,
            'token' => $token,
        ], 201);
    }
    public function Logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logout Successfully',
        ]);
    }
}
