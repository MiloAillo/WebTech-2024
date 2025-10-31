<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use App\Models\User;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseFormatSame;

class AccessController extends Controller
{
    function signup(Request $request) {
        $validated = $request->validate([
            "username" => ["required", "unique:users", "min:4", "max:60"],
            "password" => ["required", "min:5", "max:20"],
            "role" => ["required", "in:dev,user"]
        ]);

        $data = [
            "username" => $validated["username"],
            "password" => Hash::make($validated["password"]),
            "role" => $validated["role"],
            "last_login_at" => now(),
            "created_at" => now()
        ];
        
        try {
            $id = DB::table("users")->insertGetId($data);
            $user = User::findOrFail($id);
            $token = $user->createToken("auth-token")->plainTextToken;
            
            return response()->json([
                "status" => "success",
                "token" => $token
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    function login(Request $request) {
        $validated = $request->validate([
            "username" => ["required", "min:4", "max:60"],
            "password" => ["required", "min:5", "max:20"],
        ]);

        $user = User::query()->where("username", $validated["username"])->first();
        
        if(!$user || !Hash::check($validated["password"], $user->password)) {
            return response()->json([
                "status" => "invalid",
                "message" => "wrong username or password"
            ], 401);
        }
        
        try {
            
            $token = $user->createToken("auth-token")->plainTextToken;

            return response()->json([
                "status" => "success",
                "token" => $token
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                "status" => "error",
                "message"=> $e->getMessage()
            ]);
        }
    }

    function adminSignup(Request $request) {
        $validated = $request->validate([
            "username" => ["required", "unique:users", "min:4", "max:60"],
            "password" => ["required", "min:5", "max:20"],
        ]);

        $data = [
            "username" => $validated["username"],
            "password" => Hash::make($validated["password"]),
            "last_login_at" => now(),
            "created_at" => now()
        ];
        
        $id = DB::table("administrators")->insertGetId($data);
        
        try {
            $user = Administrator::findOrFail($id);
            $token = $user->createToken("auth-token")->plainTextToken;
            
            return response()->json([
                "status" => "success",
                "token" => $token
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    function adminLogin(Request $request) {
        $validated = $request->validate([
            "username" => ["required", "min:4", "max:60"],
            "password" => ["required", "min:5", "max:20"],
        ]);

        $user = Administrator::query()->where("username", $validated["username"])->first();
        if(!$user || !Hash::check($validated["password"], $user->password)) {
            return response()->json([
                "status" => "invalid",
                "message" => "wrong username or password"
            ], 401);
        }
        
        try {
            
            $token = $user->createToken("auth-token")->plainTextToken;

            return response()->json([
                "status" => "success",
                "token" => $token
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                "status" => "error",
                "message"=> $e->getMessage()
            ]);
        }
    }

    function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            "status" => "success"
        ], 200);
    }
}
