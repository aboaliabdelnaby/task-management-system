<?php

namespace App\Domain\Services\Classes;



use App\Domain\Repostories\Interfaces\IUserRepository;
use App\Domain\Services\Interfaces\IAuthService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService implements IAuthService
{
    public function __construct(private readonly IUserRepository $userRepository) {}



    public function register(array $data): array
    {
        $user=$this->userRepository->create($data);
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    // LOGIN
    public function login(array $data):array
    {
        $user = $this->userRepository->first(['email'=>$data['email']]);
        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials']
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function logout($user):bool
    {
        return $user->tokens()->delete();
    }

}
