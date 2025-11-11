<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Resources\User\UserResource;
use App\Interfaces\AuthInterface;
use App\Services\AuthService;
use App\Traits\RespondsWithHttpStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller implements AuthInterface
{
    use RespondsWithHttpStatus;

    public function __construct(protected AuthService $service) {}

    public function register(RegisterRequest $request): JsonResponse
    {

        $user = $this->service->register($request->validated());

        return $this->success(message: 'Registration Successful', data: new UserResource($user));

        // return self::returnDataWithToken($user, 'Registration Successful');
    }

    /**
     * @throws CustomException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $data = $this->service->login($request->validated());

        if (array_key_exists('error', $data)) {
            return $this->failure(message: $data['error']);
        }

        return self::returnDataWithToken($data['user'], 'Login Successful');
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->tokens()->delete();
        // $user->currentAccessToken()->delete();

        return $this->success('Logout successful');
    }

    public function me(Request $request): JsonResponse
    {
        return $this->success(data: new UserResource($request->user()));
    }

    protected function returnDataWithToken($user, string $message): JsonResponse
    {
        $token = $user->createToken(request('token_name') ?? 'app')->plainTextToken;

        return $this->success($message, ['token' => $token, 'user' => new UserResource($user)]);
    }
}
