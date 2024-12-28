<?php

namespace App\Services;

use App\Http\Requests\CreateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{

  public function loginUser($request)
  {
    $user = User::where('email', $request->email)->first();
    if (!$user || !Hash::check($request->password, $user->password)) {
      return response()->json([
        'message' => 'Invalid Credentials'
      ], 401);
    }
    $token =  $user->createToken('auth_token')->plainTextToken;
    return response()->json([
      'user' => new UserResource($user),
      'access_token' => $token,
      'token_type' => 'Bearer'
    ]);
  }
  public function registerUser($request)
  {
    try {
      DB::beginTransaction();
      $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => $request->password,
      ]);
      $token = $user->createToken('auth_token')->plainTextToken;
      DB::commit();

      return response()->json([
        'user' => new UserResource($user),
        'access_token' => $token,
        'token_type' => 'Bearer'
      ]);
    } catch (Exception $e) {
      DB::rollBack();
      Log::error('Error :' . $e->getMessage());
    }
  }
  public function logout($request) {
    $request->user()->tokens()->delete();
    return response()->json(['message' => 'Successfully Logged Out']);
  }

  public function userProfile($request) {
    return new UserResource($request->user());
  }
}
