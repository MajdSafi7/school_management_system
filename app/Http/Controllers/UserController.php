<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function Register(RegisterRequest $request)
    {
        $validated = $request->validated();
        $role='student';
        $code =rand(10000,99999);
        $user = User::create([
            'phone_number' => $request->phone_number,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verification_code' => $code,
            'role'=>$role,
        ]);

        return response()->json([
            'message' => 'User Registered Successfully',
            'user' => $user->load('person'),

        ], 201);
    }

    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'verification_code' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || $user->email_verification_code !== $request->verification_code) {
            return response()->json([
                'message' => 'Invalid email or verification code'
            ], 400);
        }

        $user->email_verified_at = now();
        $user->email_verification_code = null;
        $user->save();

        return response()->json([
            'message' => 'Email verified successfully',
            'user' => $user
        ], 200);
    }

    public function completeProfile(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if(!$user->email_verified_at){
            return response()->json([
                'message' => 'Please verify your email before completing your profile'
            ], 403);
        }


        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'personal_photo' => 'required|image|mimes:png,jpg,jpeg|max:4096',
            'id_photo' => 'required|image|mimes:png,jpg,jpeg|max:4096'
        ]);


        if ($request->hasFile('personal_photo')) {
            $path1 = $request->file('personal_photo')->store('user_photo', 'public');
        }
        if ($request->hasFile('id_photo')) {
            $path2 = $request->file('id_photo')->store('user_photo', 'public');
        }

        $user->person()->create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birthdate' => $request->birthdate,
            'personal_photo' => $path1,
            'id_photo' => $path2
        ]);

        return response()->json([
            'message' => 'Profile completed successfully',
            'user' => $user->load('person')
        ], 200);
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
        if (!$user->email_verified_at) {
            return response()->json([
                'message' => 'Please verify your email before logging in'
            ], 403);
        }

        if($user->status !='accepted'){
            return response()->json([
                'message'=>'please wait until the admin accept you'
            ], 200);
        }

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
