<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    private $authSrvc, $user = [];

    public function __construct(AuthService $authService)
    {
        $this->authSrvc = $authService;
        $this->middleware('throttle:10,1')->only('login');
    }
    /**
     *--------------------------------------------------------------------------
     * Auth For Admin users
     *--------------------------------------------------------------------------
     * For login in SPA
     * @param AuthRequest
     * @return JsonResponse
     */

    public function login(AuthRequest $request): JsonResponse
    {
        $result = $this->authSrvc->performAuth($request);
        return $result['bool'] ? self::success($result['result'], 'Authorized: Welcome!') : self::error($result['message']);
    }

    /**
     *--------------------------------------------------------------------------
     * User register
     *--------------------------------------------------------------------------
     *
     * mainly used for register user
     * @param AuthRequest
     * @return JsonResponse
     *
     */

    public function register(RegisterUserRequest $request): JsonResponse
    {
        $result = $this->authSrvc->registerUser($request);
        return $result['bool'] ? self::success($result['result'], $result['message']) : self::error($result['message']);
    }

    /**
     *--------------------------------------------------------------------------
     * Auth User
     *--------------------------------------------------------------------------
     *
     * Returns current logged in user details
     * @return JsonResponse
     *
     */

    public function authUser(): JsonResponse
    {
        return auth()->guard('api')->user()?->id ?
			self::success(auth()->guard('api')->user())
			:
			self::error("No User login");
    }
}
