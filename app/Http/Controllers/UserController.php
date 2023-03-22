<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function register(Request $request)
{
    try {
        DB::connection()->getDatabaseName();
        echo "Kết nối thành công!";
    } catch (\Exception $e) {
        die("Lỗi kết nối: " . $e->getMessage());
    }

    $validator = Validator::make($request->all(), [
        'username' => 'required|string|max:255|unique:users',
        'password' => 'required|string|min:8',
        'email' => 'required|string|email|max:255|unique:users',
        'phone' => 'required|string|max:10|unique:users',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'error' => $validator->errors()
        ], 422);
    }

    // Create a new user
    $user = new User([
        'username' => $request->input('username'),
        'password' => bcrypt($request->input('password')),
        'email' => $request->input('email'),
        'phone' => $request->input('phone'),
    ]);
    var_dump($user);
    $user->save();
    // Send verification code via Firebase SMS
    // $phone = $request->input('phone');
    // $verificationCode = rand(100000, 999999);

    // $message = "Your verification code is: " . $verificationCode;

    // $firebase = Firebase::initialize([
    //     'database_url' => 'https://coinmarket-13f98-default-rtdb.asia-southeast1.firebasedatabase.app/',
    //     'phone_auth_verify' => true,
    // ]);

    // $firebase->getAuth()->sendVerificationCode($phone, $message);

    return response()->json([
        'message' => 'User registered successfully',
        'user' => $user,
        // 'verification_code' => $verificationCode,
    ], 201);
}
public function getListUser()
    {
        $users = DB::table('users')->select('id', 'username', 'password', 'email' , 'phone')->get();
        // $users = User::all();
        // foreach ($users as $user) {
        //     $user->password = encrypt($user->password);
        // }
        dd($users);
        if($users->isNotEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'List users retrieved successfully.',
                'data' => $users
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'No users found in database.',
                'data' => []
            ]);
        }
    }
    public function login(Request $request){
       $credentials = $request->only('username', 'password');
       
        // Kiểm tra thông tin đăng nhập
        if (Auth::attempt($credentials)) {
            try {
                DB::connection()->getDatabaseName();
                echo "Kết nối thành công!";
            } catch (\Exception $e) {
                die("Lỗi kết nối: " . $e->getMessage());
            }
            $user = Auth::user();
            if (!Hash::check($request->input('password'), $user->password)) {
                return response()->json(['error' => 'Invalid credentials']);
                }
            // Tạo payload cho JWT token
            $payload = [
                'sub' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'iat' => time(),
                'exp' => time() + (60 * 120) // Thời gian hết hạn của token: 1 giờ
            ];
            
            // Tạo JWT token từ payload
            $token = JWT::encode($payload, env('JWT_SECRET') , 'HS256');
            
            return response()->json(['token' => $token]);
        }
        
        return response()->json(['error' => 'Invalid credentials']);
    }
    }
