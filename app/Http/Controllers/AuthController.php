<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Contracts\Service\Attribute\Required;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }


    public function login(Request $request)
    {

        $validated = $request->validate([
            'user_id' => 'required',
            'password' => 'required|min:6',
        ]);

        $user = User::where('user_id', $request->user_id)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token_validity = (24 * 60);

                $this->guard()->factory()->setTTL($token_validity);

                if (!$token = $this->guard()->attempt($validated)) {
                    return response()->json(['error' => 'Unauthorized'], 401);
                }

                $response = [
                    'permission_menu' => $user->role,
                    'access_token' => $token,
                ];

                return $this->OK($response, 'login success');
            } else {
                return $this->ERROR("Oops! Look like your password is incorrect");
            }
        } else {
            return $this->ERROR("Oops! The user does not exist");
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user()
    {

        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $user = auth()->user();
        if ($user == null)
            return response()->json(['status' => false], 401);

        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

    protected function guard()
    {
        return Auth::guard();
    }
}
