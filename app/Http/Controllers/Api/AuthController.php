<?php

namespace App\Http\Controllers\Api;

use App\Domain\Responder\Interfaces\IApiHttpResponder;
use App\Domain\Services\Interfaces\IAuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class AuthController extends Controller
{

    public function __construct(private IAuthService $authService,private readonly IApiHttpResponder $apiHttpResponder){}

    // REGISTER
    public function register(RegisterRequest $request): JsonResponse
    {
        return $this->apiHttpResponder->response(
            data: $this->authService->register($request->validated()),
            message: 'User registered successfully',
            status: Response::HTTP_CREATED
        );
    }

    // LOGIN
    public function login(LoginRequest $request): JsonResponse
    {
        return $this->apiHttpResponder->response(
            data: $this->authService->login($request->validated()),
            message: 'Login successful',
        );
    }

    // LOGOUT
    public function logout(): JsonResponse
    {
        $this->authService->logout(auth()->user());
        return $this->apiHttpResponder->response(
            message:'Logged out successfully',
        );
    }
}
