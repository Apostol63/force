<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Servieces\UserFindService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Api\LoginRequest;
use App\Servieces\PersonalAccessTokenService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LoginUserController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private UserFindService $userFindService,
        private PersonalAccessTokenService $personalAccessToken
    )
    {}

    public function store(LoginRequest $request): JsonResponse
    {
        $user = $this->userFindService->findByEmail($request->email);

        if ($user) {
            $hasToken = $this->personalAccessToken->checkUserId($user->id);

            if ($hasToken) {
                return response()->json([
                    'message' => 'Пользователь уже авторизован.'
                ], 200);
            }
        }

        $request->authenticate();

        $user = $request->user();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
        ], 200);
    }
}
