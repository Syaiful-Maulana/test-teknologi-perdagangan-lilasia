<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Traits\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    use ResponseFormatter;

    public function register_admin(Request $request)
    {
        DB::beginTransaction();
        try {
            $rules = [
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required'
            ];

            $messages = [
                'name.required' => 'Name is required',
                'name.string' => 'Name must be a string',
                'email.required' => 'Email is required',
                'email.unique' => 'Email already exist',
                'password.required' => 'Password is required'
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 400);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'admin'
            ]);

            if (!$user) {
                throw new \Exception("Failed to create user", 500);
            }

            DB::commit();
            $response = self::arrayResponse(200, 'success', null);
            return response()->json($response, $response['code']);
        } catch (\Throwable $e) {
            DB::rollBack();
            $response =  $this->arrayResponse($e->getCode(), $e->getMessage());
            return response()->json($response, $response['code']);
        }
    }
    public function register_user(Request $request)
    {
        DB::beginTransaction();
        try {
            $rules = [
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required'
            ];

            $messages = [
                'name.required' => 'Name is required',
                'name.string' => 'Name must be a string',
                'email.required' => 'Email is required',
                'email.unique' => 'Email already exist',
                'password.required' => 'Password is required'
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 400);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user'
            ]);

            if (!$user) {
                throw new \Exception("Failed to create user", 500);
            }

            DB::commit();
            $response = self::arrayResponse(200, 'success', null);
            return response()->json($response, $response['code']);
        } catch (\Throwable $e) {
            DB::rollBack();
            $response =  $this->arrayResponse($e->getCode(), $e->getMessage());
            return response()->json($response, $response['code']);
        }
    }


    public function login(Request $request)
    {
        try {
            $rules = [
                'email' => 'required|email',
                'password' => 'required'
            ];

            $messages = [
                'email.required' => 'Email is required',
                'email.email' => 'Email must be a email',
                'password.required' => 'Password is required'
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first(), 400);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            $token = $user->createToken('auth-token')->plainTextToken;


            $response = self::arrayResponseLogin(200, 'success', $token);
            return response()->json($response, $response['code']);
        } catch (\Throwable $e) {
            $response =  $this->arrayResponseLogin($e->getCode(), $e->getMessage());
            return response()->json($response, $response['code']);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out'], 200);
    }
}
