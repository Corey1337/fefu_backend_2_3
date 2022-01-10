<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiAuthController extends Controller
{
    public function sign_up(Request $request) : JsonResponse
    {
        $request['login'] = strtolower($request['login']);
        $validator = Validator::make($request->all(), [
            'login' => 'unique:users|required|between: 5, 30|regex: /^[a-z0-9\-._]+$/i',
            'password' => 'required|between: 10, 30|regex: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&].{10,}$/',
        ]);

        if($validator->fails())
        {
            $validate_err = $validator->errors()->all();
            return response()->json(['message' => $validate_err], 422);
        }

        $validated = $validator->validated();
        $user = new User();
        $user->login = $validated['login'];
        $user->password = Hash::make($validated['password']);
        $user->save();

        $token = $user->createToken('token')->plainTextToken;

        $response = [
            'token' => $token,
            'user' => new UserResource($user),
        ];

        return response()->json($response, 201);
    }

    public function sign_in(Request $request) : JsonResponse
    {
        $request['login'] = strtolower($request['login']);
        $validator = Validator::make($request->all(), [
            'login' => 'required|between: 5, 30',
            'password' => 'required|between: 10, 30',
        ]);

        if($validator->fails())
        {
            $validate_err = $validator->errors()->all();
            return response()->json(['message' => $validate_err], 422);
        }

        $validated = $validator->validated();

        if (!Auth::attempt(['login' => $validated['login'], 'password' => $validated['password']]))
            return response()->json(['message' => 'Invalid login or password'], 422);

        $user = User::query()->where('login', $validated['login'])->first();

        $token = $user->createToken('token')->plainTextToken;

        $response = [
            'token' => $token,
            'user' => new UserResource($user),
        ];

        return response()->json($response, 201);
    }

    public function profile(Request $request) : JsonResponse
    {
        $user = Auth::user();
        return response()->json([new UserResource($user)]);
    }

    public function logout(Request $request) : JsonResponse
    {
        Auth::user()->tokens->delete();
        return response()->json(['message' => 'exited']);
    }
}
