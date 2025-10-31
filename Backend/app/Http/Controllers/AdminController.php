<?php

namespace App\Http\Controllers;

use App\Models\Administrator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    function getAdmins(Request $request) {
        $user = $request->user();

        try {
            $admin = Administrator::query()
                ->where("id", $user->id)
                ->where("username", $user->username)
                ->firstOrFail();
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "forbidden",
                "message" => "you are not administrator!"
            ]);
        }

        if($admin) {
            $admins = Administrator::query()->get();
            return response()->json([
                "totalElements" => count($admins),
                "content" => $admins
            ], 200);
        }
    }

    function getUsers(Request $request) {
        $user = $request->user();

        try {
            $admin = Administrator::query()
                ->where("id", $user->id)
                ->where("username", $user->username)
                ->firstOrFail();
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "forbidden",
                "message" => "you are not administrator!"
            ]);
        }

        if($admin) {
            $users = User::query()->get();
            return response()->json([
                "totalElements" => count($users),
                "content" => $users
            ]);
        }
    }

    function getUser(Request $request, $username) {
        $user = $request->user();

        try {
            $admin = Administrator::query()
                ->where("id", $user->id)
                ->where("username", $user->username)
                ->firstOrFail();
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "forbidden",
                "message" => "you are not administrator!"
            ]);
        }
        
        if($admin) {
            try {
                $user = User::query()->where("username", $username)->firstOrFail();
                return response()->json($user, 200);
            } catch (\Exception $th) {
                return response()->json([
                    "status" => "not found",
                    "message" => "user not found"
                ], 403);
            }
            
        }
    }

    function updateUser(Request $request, $id) {
        $validated = $request->validate([
            "username" => ["unique:users", "min:4", "max:60"],
            "password" => ["min:5", "max:20"],
            "role" => ["in:dev,user"]
        ]);

        $user = $request->user();

        try {
            $admin = Administrator::query()
                ->where("id", $user->id)
                ->where("username", $user->username)
                ->firstOrFail();
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "forbidden",
                "message" => "you are not administrator!"
            ]);
        }

        if($admin) {
            try {
                $user = User::query()->where("id", $id)->firstOrFail();
                $user->update($request->all());
                return response()->json([
                    "status" => "ok",
                    "message" => "succesfully updated"
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    "status" => "failed",
                    "message" => "user not found"
                ]);
            }
        }
    }
    
    function deleteUser(Request $request, $id) {
        $user = $request->user();

        try {
            $admin = Administrator::query()
                ->where("id", $user->id)
                ->where("username", $user->username)
                ->firstOrFail();
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "forbidden",
                "message" => "you are not administrator!"
            ]);
        }

        if($admin) {
            try {
                $user = User::query()->where("id", $id)->firstOrFail();
                $user->delete();

                return response()->json([
                    "status" => "ok",
                    "message" => "succesfully deleted"
                ]);                
            } catch (\Exception $e) {
                return response()->json([
                    "status" => "failed",
                    "message" => "user not found"
                ]);            
            }
        }
    }
}
