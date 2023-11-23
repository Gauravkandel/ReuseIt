<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Notifications\WelcomeMessageNotification;
use Illuminate\Support\Facades\Notification;
use PDO;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }
    public function register(UserRequest $request)
    {
        $userValidation = $request->validated();
        if ($request->has('Profile_image')) {
            $profile = $request->file('Profile_image');
            $profile_name = $profile->getClientOriginalName();
            $profile->move(public_path('images'), $profile_name);
            $userValidation['Profile_image'] = time() . $profile_name;
        }
        $userdata = User::create($userValidation);
        Notification::send($userdata, new WelcomeMessageNotification);
        return response()->json(['message' => "successfully registered"], 200);
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
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
        $user = auth()->user();
        $notifications = $user->unreadNotifications->count();
        $user_details = [
            'user' => auth()->user(),
        ];
        $cookie = cookie('jwt', $token, auth()->factory()->getTTL(30), null, null, false, true);

        return response()->json($user_details)->cookie($cookie);
    }
}

// public function generateToken() {
//     $token = auth()->user()->createToken('auth-token')->plainTextToken;

//     return response()
//         ->json(['token' => $token])
//         ->cookie('auth-token', $token, 60); // 60 minutes, adjust as needed
// }