<?php

namespace App\Domain\Services\Interfaces;

interface IAuthService
{
    public function register(array $data): array;
    public function login(array $data):array;
    public function logout($user):bool;
}
