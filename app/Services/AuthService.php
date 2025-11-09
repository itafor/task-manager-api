<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Traits\RespondsWithHttpStatus;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AuthService
{
    use RespondsWithHttpStatus;

    /** 
     * AuthService constructor.
     */
    public function __construct(
        protected UserRepository $userRepository,
    ) {}

    /**
     * Register a new user.
     * @throws Exception
     */
    public function register(array $data): User
    {
        try {
            DB::beginTransaction();
            $data['password'] = Hash::make($data['password']);
            $user = $this->userRepository->create($data);

            DB::commit();
        } catch (Throwable $th) {
            $error = $th->getMessage();
            logger('Registration aborted because ' . $error);
            DB::rollBack();
            throw new Exception("Something went wrong: $error");
        }

        return $user;
    }

    /**
     * Login user and create token.
     * @throws CustomException
     */
    public function login(array $data): array|null
    {
        try {
            $user = $this->userRepository->findByColumn('email', $data['email']);

            if (!$user) {
                throw new Exception('This email does not exist');
            }

            $checkPassword = Hash::check($data['password'], $user->password);
            if (!$checkPassword) {
                throw new Exception('Incorrect password');
            }
            return ['user' => $user];
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            throw new Exception("Something went wrong: $error");
        }
    }
}
