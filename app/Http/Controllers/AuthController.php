<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
  protected $userService;

  public function __construct()
  {
    $this->userService = new UserService();
  }
  public function login(LoginRequest $request) {
    return $this->userService->loginUser($request);
  }

  public function register(CreateUserRequest $request)
  {
    return $this->userService->registerUser($request);
  }

  public function profile(Request $request) {
    return $this->userService->userProfile($request);
  }

  public function logout(Request $request) {
    return $this->userService->logout($request);
  }
}
