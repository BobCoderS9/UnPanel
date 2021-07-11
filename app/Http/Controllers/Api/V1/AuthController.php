<?php


namespace App\Http\Controllers\Api\V1;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'shop']]);
        auth()->shouldUse('api');
    }

    /**
     * @param Request $request
     * @return AuthController|\Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), ['email' => 'required|email', 'password' => 'required']);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        if ($token = auth('api')->attempt($validator->validated())) {
            return $this->sendJson([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60,
                'user' => auth('api')->user()->profile(),
            ]);
        }

        return $this->sendError('登录信息错误！');
    }
}
