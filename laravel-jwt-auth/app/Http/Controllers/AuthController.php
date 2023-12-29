<?php
namespace App\Http\Controllers;

use App\Http\Resources\AuthResource;
use App\Models\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api' , ['except' => ['pharmacy_login' , 'pharmacy_register']]);
    }
    
    public function pharmacy_login(Request $request){
    $validator = Validator::make($request->all() , [
        'phone' => 'required|string|digits:10',
        'password' => 'required|string|min:8|max:20'
    ]);
    if ($validator->fails()) {
        return response()->json($validator->errors() , 422);
    }
    if (! $token = auth()->attempt($validator->validated())) {
        return response()->json(['error' => 'unauthorized' , 401]);
    }
    return $this->createNewToken($token);
    }
    
    public function warehouse_login(Request $request){
    $validator = Validator::make($request->all() , [
        'phone' => 'required|digits:10|starts_with:09',
        'password' => 'required|string|between:8,20',
    ]);
    if ($validator->fails()) {
        return response()->json($validator->errors() , 422);
    }
    if (! $token = auth()->attempt($validator->validated())) {
        return response()->json(['error' => 'unauthorized' , 401]);
    }
    return response()->json([
        'status' => '1',
        'data' => [
        'name' => auth()->user()->name, 
        'phone' => auth()->user()->phone,
        'access token' => $token,
        ],
        'message' => 'user logged in successfully!',
    ]);
    }
    
    public function pharmacy_register(Request $request){
        $validator  = Validator::make($request->all() , [
        'name' => 'required|string|between:3,100',
        'phone' => 'required|digits:10|unique:users|starts_with:09',
        'password' => 'required|confirmed|min:8|string',
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
    'expired_in' => Auth::factory()->getTTL()*60,
    'user' => auth()->user(),
    ]);
    }
    public function index(){
    return response('home page');
    }
    
    public function showUserInformation(){
        return response()->json([
        'name' => auth()->user()->name,
        'phone' => auth()->user()->phone,
        ]);
    }


    // $user->my_orders;
}