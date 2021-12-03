<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiTokenController extends Controller
{
    public function register(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'name' => 'required',
            'password' => 'required',

        ]);

        $exists = User::where('email', $request->email)->exists();

        if($exists){
            return response()->json(['errors' => "Utilisateur déjà inscrit"], 409);
        }


        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken($request->email)->plainTextToken;

        return response()->json([
            'token' => $token
        ], 201);

    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json(['errors' => "Identifiants inconnus ou erronés"], 401);
        }

        $user->tokens()->where('tokenable_id', $user->id)->delete();

        $token = $user->createToken($request->email)->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' =>$user
        ], 200);
    }
}
