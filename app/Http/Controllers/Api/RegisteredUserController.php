<?php

namespace App\Http\Controllers\Api;

use App\Servieces\UserAuthService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Servieces\UserRegisterService;
use App\Http\Requests\Auth\Api\RegisterRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

class RegisteredUserController extends Controller
{

    public function __construct(
        private UserRegisterService $registerService,
        private UserAuthService $userAuthService,
    )
    {}

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request): JsonResponse
    {
        $data = $this->prepareUserData($request);

        $user = $this->registerService->saveUser($data);
        $token = $this->userAuthService->auth($user);

        return response()->json(['token' => $token]);
    }

    private function prepareUserData(RegisterRequest $request): array
    {
        return [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->string('password'))
        ];
    }
}
