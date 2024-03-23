<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api' , ['except' => ['login' , 'register']]);
    }
    
    public function login(Request $request){
    $validator = Validator::make($request->all() , [
        'phone' => 'required|string|digits:10',
        'password' => 'required|string|between:8,20'
    ]);
    if ($validator->fails()) {
        return response()->json($validator->errors() , 422);
    }
    if (!$token = auth()->attempt($validator->validated())) {
        return response()->json(['error' => 'unauthorized' , 401]);
    }
    return $this->createNewToken($token);
    }
    
    public function register(Request $request){
        $validator  = Validator::make($request->all() , [
        'name' => 'required|string|between:3,50',
        'phone' => 'required|digits:10|unique:users|starts_with:09',
        'password' => 'required|confirmed|between:8,20|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson() , 400);
        }
        $user = User::query()->create(array_merge(
        $validator->validated(),
        ['password' => bcrypt($request->password)]
        ));

        return response() -> json([
        'user' => $user,
        'message' => 'user has been registered successfully!',
        ] , 201);
        
    }
    
    public function logout(){
    auth()->logout();
    return response()->json(['message' => 'user logged out successfully!']);
    }
    
    public function refresh(){
    return $this->createNewToken(Auth::refresh());
    }

    
    protected function createNewToken($token){
    return response()->json([
    'access _token' => $token,
    'token_type' => 'bearer',
    'expired_in' => Auth::factory()->getTTL()*600000000,
    'user' => auth()->user(),
    ]);
    }
    
    public function showUserInformation(){
        return response()->json([
        'name' => auth()->user()->name,
        'phone' => auth()->user()->phone,
        ]);
    }

}