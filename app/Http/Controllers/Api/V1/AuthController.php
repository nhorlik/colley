<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nev' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:180', 'unique:felhasznalo,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'cel' => ['required', 'string', 'max:20'],
            'gdpr_hozzajarulas' => ['required', 'accepted'],
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('A megadott adatok érvénytelenek.', 422, $validator->errors()->toArray());
        }

        $user = User::create([
            'nev' => $request->string('nev')->toString(),
            'email' => mb_strtolower($request->string('email')->toString()),
            'jelszo_hash' => Hash::make($request->input('password')),
            'cel' => $request->string('cel')->toString(),
            'gdpr_hozzajarulas' => now(),
        ]);

        $token = $user->createToken('colley-api')->plainTextToken;

        return ApiResponse::success([
            'user' => [
                'id' => $user->felhasznalo_id,
                'nev' => $user->nev,
                'email' => $user->email,
                'cel' => $user->cel,
            ],
            'token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return ApiResponse::error('A megadott adatok érvénytelenek.', 422, $validator->errors()->toArray());
        }

        $user = User::where('email', mb_strtolower($request->input('email')))->first();

        if (!$user || !Hash::check($request->input('password'), $user->jelszo_hash)) {
            return ApiResponse::error('Hibás email cím vagy jelszó.', 401);
        }

        $token = $user->createToken('colley-api')->plainTextToken;

        return ApiResponse::success([
            'user' => [
                'id' => $user->felhasznalo_id,
                'nev' => $user->nev,
                'email' => $user->email,
            ],
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();

        return ApiResponse::success(['message' => 'Sikeres kijelentkezés.']);
    }
}
