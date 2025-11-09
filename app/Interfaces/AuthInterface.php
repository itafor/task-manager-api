<?php

namespace App\Interfaces;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface AuthInterface
{
    public function register(RegisterRequest $request): JsonResponse;

    public function login(LoginRequest $request): JsonResponse;

    public function logout(Request $request): JsonResponse;

    public function me(Request $request): JsonResponse;
}
