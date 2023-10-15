<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'email_verified_at' => now(),
            'picture' => "https://ui-avatars.com/api/?name=$request->name&background=random"
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function login(Request $request)
    {
        if (! Auth::attempt($request->only('email', 'password'), true)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login success',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);


        // request()->validate([
        //     'email' => ['required', 'string', 'email'],
        //     'password' => ['required'],
        // ]);

        // /**
        //  * We are authenticating a request from our frontend.
        //  */
        // if (EnsureFrontendRequestsAreStateful::fromFrontend(request())) {
        //     if (! Auth::guard('web')
        //         ->attempt(
        //             request()->only('email', 'password')
        //         )) {
        //         throw ValidationException::withMessages([
        //             'email' => __('auth.failed'),
        //         ]);
        //     }
        // }
        // /**
        //  * We are authenticating a request from a 3rd party.
        //  */
        // else {
        //     // Use token authentication.
        // }
        
        // if (EnsureFrontendRequestsAreStateful::fromFrontend(request())) {
        //     if (!Auth::guard('web')->attempt($request->only('email', 'password'))) {
        //         // return response()->json([
        //         //     'message' => 'Invalid login details'
        //         // ], 401);
        //         throw new AuthenticationException();
        //     }
        //     $request->session()->regenerate();
        //     return response()->json(['message' => 'success']);
        // }
    }

    public function logout(Request $request)
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'logout success'
        ]);
        // Auth::logout();
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();
    }
    
    public function me(Request $request)
    {
        return response()->json([
            'data' => $request->user(),
          ]);
    }
}