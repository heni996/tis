<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Resources\BackOffice\UserResource;
use App\Http\Services\BackOffice\UserService;
use App\Models\User;
use Hash;
use Illuminate\Support\Facades\DB;
use Validator;


class AuthController extends Controller
{

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(private UserService $userService)
    {
        $this->middleware('auth:api', ['except' => ['login']]);
        $this->userService = $userService;
    }

    /**
     * Get a JWT token via given credentials.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $credentials = $request->only('email', 'password');
        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'first_name';
        $user = User::where($fieldType, $request->email)->first();

        if (!$token = Auth::attempt([
            $fieldType => $credentials['email'],
            'password' => $credentials['password']
        ])) {
            if (!$user) {
                return response()->json(['error' => 'user not found'], 401);
            } else {
                return response()->json(['error' => 'wrong password'], 401);
            }
        }
        // $user->update(['auth' => 1]);
        return $this->respondWithToken($token, $user);
    }

    // public function forgotPassword(Request $request)
    // {
    //     $request->validate(['email' => 'required|email|exists:users']);

    //     $email = $request->input('email');
    //     $user = User::whereEmail($email)->first();

    //     $reset = new Reset();
    //     $reset->user_id = $user->id;
    //     $reset->token = $this->generateRandomCode(4) . '-' . $this->generateRandomCode(3) . '-' . $this->generateRandomCode(4);
    //     $reset->save();
    //     Mail::send("mail", ['token' => $reset->token], function ($message) use ($user) {
    //         $message->to($user->email)->subject("Reset password");
    //     });

    //     return response()->json(['message' => 'Password reset email sent']);
    // }


    // public function resetPassword(Request $request)
    // {
    //     $this->validate($request, [
    //         'token' => 'required|exists:resets',
    //         'email' => 'required|exists:users',
    //         'password' => 'required|confirmed',
    //     ]);

    //     $reset = Reset::whereToken($request->post('token'))->first();
    //     $user = User::whereEmail($request->post('email'))->first();

    //     if ($reset->user_id == $user->id) {
    //         if ($reset->status && !($reset->created_at < Carbon::parse('-12 hours'))) {
    //             $user->password = Hash::make($request->post('password'));
    //             $user->save();
    //             $olders = Reset::where('user_id', $user->id)->get();
    //             foreach ($olders as $older) {
    //                 $older->status = false;
    //                 $older->save();
    //             }
    //             return response()->json([
    //                 'entity' => 'users',
    //                 'action' => 'reset password',
    //                 'result' => 'success'
    //             ], 200);
    //         } else {
    //             return response()->json([
    //                 "error" => [
    //                     "code" => 422,
    //                     "message" => "token has been expired "
    //                 ]
    //             ], 422);
    //         }
    //     } else {
    //         return response()->json([
    //             "error" => [
    //                 "code" => 422,
    //                 "message" => "token or email doesnt match"
    //             ]
    //         ], 422);
    //     }
    // }

    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required',
            'password' => 'required|confirmed',
        ]);
        $user = Auth::user();
        if (!Hash::check($request->post('old_password'), $user->password)) {
            return response()->json([
                'entity' => 'users',
                'action' => 'old password does not match',
                'result' => 'fail'
            ], 403);
        }
        $userId = $user->id;
        $newPassword = Hash::make($request->post('password'));
        \App\Models\User::where('id', $userId)->update(['password' => $newPassword]);
        return response()->json([
            'entity' => 'users',
            'action' => 'reset password',
            'result' => 'success'
        ], 200);
    }

    private function generateRandomCode($length = 6)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $rules = [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'hotel_id' => 'nullable|exists:hotels,id',
            'roles'=> 'required|exists:roles,id'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $userData = $this->userData($validator->validated());
        try {
            DB::beginTransaction();
            $user = User::create($userData);
            $user->syncRoles($userData['roles']);
            DB::commit();
            return response()->json([
                'message' => 'User successfully registered',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error during user registration',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json($this->guard()->user());
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $user)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60,
            'user' => new UserResource($user)
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }

    public function userData($data): array
    {
        return [
            'email' => isset($data['email'])?$data['email']: "",
            'password' => Hash::make($data['password']),
            'first_name' =>isset($data['first_name'])?$data['first_name'] : "",
            'last_name' =>isset($data['last_name'])?$data['last_name'] : "",
            'hotel_id' =>isset($data['hotel_id'])?$data['hotel_id'] : null,
            'roles'=> isset($data['roles']) ? $data['roles'] : ""
        ];
    }
}
