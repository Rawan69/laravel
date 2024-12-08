<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\User;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Validator;

// use Illuminate\Support\Facades\Log; 
// class UserController extends Controller
// {
//     // Register a new user
//     public function register(Request $request)
//     {
//       $validator= validator::make($request->all(), [
//             'name' => 'required|string|max:255',
//             'email' => 'required|string|email|max:255|unique:users',
//             'password' => 'required|string|min:6',
//             'status'=> 'required|string|in:admin,user'
//         ]);
//         //fail vali
//         if ($validator->fails()) {
//             return response()->json([
//                 'status' => 'error',
//                 'message' => 'Validation failed',
//                 'errors' => $validator->errors(),
//             ], 422);
//         }

//         $user = User::create([
//             'name' => $request->name,
//             'email' => $request->email,
//             'password' => Hash::make($request->password),
//             'status'=>$request->status,
//         ]);

//         $token = JWTAuth::fromUser ($user);

//         return response()->json([
//             'status' => 'success',
//             'message' => 'User registered successfully',
//             'user' => $user,
//             'token' => $token,
//         ],201);
//     }

//     // Login user
//     public function login(Request $request)
// {
   
//     $credentials = $request->only('email', 'password');

   
//     if (!$token = JWTAuth::attempt($credentials)) {
//         return response()->json([
//             'status' => 'error',
//             'message' => 'Invalid email or password',
//         ], 401);  
//      }

  
//     return response()->json([
//         'status' => 'success',
//         'message' => 'User logged in successfully',
//         // 'user' => Auth::user(),
//         'token' => $token,
//     ]);
// }


// public function logout(Request $request)
// {
  
//     $token = $request->bearerToken();
//     if (!$token) {
//         return response()->json(['error' => 'No token provided'], 400);
//     }

//     try {
     
//         JWTAuth::parseToken()->authenticate();

//         JWTAuth::invalidate($token);
//         return response()->json(['message' => 'User successfully logged out!'], 200);

//     } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
      
//         return response()->json(['error' => 'Failed to log out, invalid token'], 400);
//     }
// }



 
//     // Show a specific user by email
//     public function show($email)
//     {
//         $user = User::where('email', $email)->first();

//         if (!$user) {
//             return response()->json(['error' => 'User  not found'], 404);
//         }

//         return response()->json($user);
//     }

//     // Update a specific user by email
//     public function update(Request $request, $email)
//     {
//         $user = User::where('email', $email)->first();

//         if (!$user) {
//             return response()->json(['error' => 'User  not found'], 404);
//         }

//         $request->validate([
//             'name' => 'sometimes|required|string|max:255',
//             'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
//             'password' => 'sometimes|required|string|min:6',
//         ]);

//         if ($request->has('name')) {
//             $user->name = $request->name;
//         }

//         if ($request->has('email')) {
//             $user->email = $request->email;
//         }

//         if ($request->has('password')) {
//             $user->password = Hash::make($request->password);
//         }

//         $user->save();

//         return response()->json($user);
//     }

//     // Delete a specific user by email
//     public function destroy($email)
//     {
//         $user = User::where('email', $email)->first();

//         if (!$user) {
//             return response()->json(['error' => 'User  not found'], 404);
//         }

//         $user->delete();

//         return response()->json(['message' => 'User  deleted successfully']);
//     }

   

// }

namespace App\Http\Controllers;
  
use App\Http\Controllers\Controller;
use App\Models\User;
// use Validator;
  use Illuminate\Http\Request;
  use Tymon\JWTAuth\Facades\JWTAuth;
  use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Contracts\JWTSubject;

use Illuminate\Support\Facades\Log; 
  
class UserController extends Controller
{
 
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register() {
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
            'status'=> 'required|string|in:admin,user'
           
        ]);
  
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
  
        $user = new User;
        $user->name = request()->name;
        $user->email = request()->email;
        $user->password = bcrypt(request()->password);
        $user-> status=request()->status;
        $user->save();
  
        return response()->json($user, 201);
    }
  
  
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
  
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
  
        return $this->respondWithToken($token);
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
    // public function logout()
    // {
    //     auth()->logout();
  
    //     return response()->json(['message' => 'Successfully logged out']);
    // }
    public function logout()
{
    Log::info('Token:', [auth()->user()]);
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
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
    public function getUserByToken(Request $request)
{
    try {
        $user = JWTAuth::parseToken()->authenticate();
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(['user' => $user], 200);
    } catch (JWTException $e) {
        return response()->json(['error' => 'Token is invalid'], 400);
    }
}}