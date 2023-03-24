<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

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
    // var_dump($user);
    
    // Send verification code via Firebase SMS
    $phone = $request->input('phone');
    $verificationCode = rand(100000, 999999);

    $message = "Your verification code is: " . $verificationCode;
    // if($this->checkFirebaseConnectionBeforePost()){
    //     $firebase = Firebase::initialize([
    //     'database_url' => 'https://coinmarket-13f98-default-rtdb.asia-southeast1.firebasedatabase.app',
    //     'phone_auth_verify' => true,
    //     ]); 
    //     echo("Kết nối thành công!");
    //     $user->save();
    //     dd($firebase);
    // $firebase->getAuth()->sendVerificationCode($phone, $message);
    // } else {
    //      return response()->json(['error' => 'Không thể kết nối với Firebase!'], 500);
    // }
    
    $firebase = Firebase::initialize([
        'database_url' => 'https://coinmarket-13f98-default-rtdb.asia-southeast1.firebasedatabase.app',
        'phone_auth_verify' => true,
        ]); 
        echo("Kết nối thành công!");
        $user->save();
        dd($firebase);
    $firebase->getAuth()->sendVerificationCode($phone, $message);
    // Return the user and the verification code
    return response()->json([
        'message' => 'User registered successfully',
        'user' => $user,
        'verification_code' => $verificationCode,
    ], 201);
}
public function getListUser()
    {
        $users = DB::table('users')->select('id', 'username', 'email' , 'phone')->get();
        // $users = User::all();
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
                // var_dump(!Hash::check($request->input('password'), $user->password));
            // Tạo payload cho JWT token
            $payload = [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'iat' => time(),
                'exp' => time() + (60 * 120) // Thời gian hết hạn của token: 2 giờ
            ];
            // $users = DB::table('users')->select('id', 'username', 'password', 'email' , 'phone')->get();
            // Tạo JWT token từ payload
            $key = env('JWT_SECRET');
            $alg = 'HS256';
            $token = JWT::encode($payload, $key, $alg);
            // $detoken = JWT::decode($token, $key, $alg); // Encode payload as a JWT Token
            // var_dump($detoken);
            
            return response()->json(['messages' => 'true' ,'token' => "$token" , 'user' => $user = DB::table('users')->select('id', 'username' , 'email' , 'phone')->where('username', $request->input('username'))->get()], 200 , ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
        }
        
        return response()->json(['error' => 'Invalid credentials'] ,500);
    }
    public function logout(Request $request)
    {
        $username = $request->input('username');
        var_dump($username);

        if (!$username) {
            return response()->json([
                'message' => 'Vui lòng cung cấp tên đăng nhập.'
            ], 400);
        }

        $user = User::where('username', $username)->first();
        // $password = User::where('password', $pass)->get();
        if (!$user) {
            return response()->json([
                'message' => 'Không tìm thấy người dùng có tên đăng nhập này.'
            ], 404);
        } 
        Auth::logout();
        return response()->json([
            'message' => 'Đăng xuất thành công.',
            'user' => $user
        ]);
    }
    public function checkFirebaseConnectionBeforePost() {
        // Set Firebase project ID
        $projectId = "coinmarket-13f98";
        
        // Set Firebase service account email and private key
        $email = "nguyenhoangnam31082000@gmail.com";
        $passwordforcheck = 'Nam31082000';
        $privateKey = "-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCcT9zJ1EJfAaXv\nxSMqAzdnIyRxfsyuAWsbR1rSbd2KzgWZRt22L7GgJnxz27es/5xePoSn4H5OzUPF\n88XoGsegmOebXo+q2HIQO1Ewq/skwTpOG51+BKuZUc3Sy4nib0T/jYgdbqcPuBWv\nF+LLdT7IhKncSCoARVdMoxZP4KH0XJLWfN4jZKwFpn2uX4GLJS/VNBsVnY+Z1g82\npCRnQGXg5wvMYT7UdA6O6Fd2K2dxJj/m6Efy16hz1rDvo6WCXyAzHUt7ygSXrP26\nk8d4aL0SIQouv7MvbyAmzqZoPcYNFf0/5PmRgXMmTI1wpD3+CrWlVKRPnEulu1vC\n3EYtAoCNAgMBAAECggEAHoOClM3ARR6jv3oq4qUtGA+mqhc4KpGxUGpuAt0aneGY\n+zJ3znxh/uL0cYOHSBi/9C/dIo5y6bwtSkPLswMjTCj9MXnUruPA5IVH1KoGBUdJ\nM/01EegkfXIYLVm5aYASJpcA5sn2h92GMh+GEPSq1Gb9Z7iYpmPi5l2B2gb+Zp6r\nR8NrLr9bU8tbm/GoLfzaiFfS9vXYXwPr0Th2nX1ON5h5M8/u1PMUA2+A9IBAknHK\nBPyo6R85pmV+paNeJQOA3s4mQ+mmtj/uTpuG9k1Dyhz84Xi9403wPdkT+I/MP0iV\nsz2Air0Bne7Rl8RK3xDlkcnTNg9NL81tCbRy4l0nIQKBgQDbOiGsB+MdRebx1Mgk\npbqM5kGtNRZXlKyysevIdbUn9iDVzPKAEBXSw+vnopp3oEIoOl1tHaN8UJq0xXph\nOS1yayvmU9gf0syhtZbwTzd2vyjCZJf8H1dq8tBX7ZmBVMy1Hgq1tBxR00BVbPC5\n5QBrsKhTcwfP8bF72qQUSvmh/QKBgQC2iBT7LR0+w+/IX+oFup/IdWDMkcgx+EoI\nBc9iVnTtKHY2310VVVfw7LPupAcipdoWjtLfmYAyEXZfsUyt14X75P9PkFbutMFf\nYWNNFY7XI9xBngpQHqIqzp2SMM8N0gM/HHSwGFBh7a1zPinIhoAbI2YhVv3ujK0k\nB3IVHLGV0QKBgBjUocG5dvj21OypPC4ic1nILsIulCRBW7o1us4OvwESuK9eskzd\nBYvE0zB+U16fUT77NV/Jjp3jB1LYVz8x2bru9p70+jLIjpL2XW1Em7SgfD5gZHKT\nHjSn4f9DkzJ552HQUEg6aUa/VbcXSsDTdlO8Q6SID+d2qbKslg8SWuoFAoGAfid5\nUMwybUHghL19se31JwexGlIbaiXvjLHvTkjw0URox64C2I6+k/4UsffeEp1MKNEX\nf4DI8FHPXX3dwDy4FouDxYq9+oclKvXVOt6OdbUMMrG28P7rF+jrULG5ORUQN1tE\nbryvOa+adI7fM/95pMgHez1zjZ7ev4sB1wOY3TECgYBt77+vBha8kcrAfKX4vFt4\nrYMvAzsdKrYya75a8TYe6sx7FKup07kijWNmUY/QtytlJQFcfC4+06xbvQ06fAC6\niD+JNvht2I4kiuFpnPuf/l1P+s9loCpLA/wigKq9k+jBFejCFFX4jU3D1Fp7DQhG\n2ZEYgijA2LD9zvaneRdY4g==\n-----END PRIVATE KEY-----\n";
        $key = env('FIREBASE_API_KEY');
        $url = "https://www.googleapis.com/identitytoolkit/v3/relyingparty/signupNewUser?key=" . $key;
        $payload = [
            "email" => $email,
            "password" => $passwordforcheck,
            "returnSecureToken" => true
        ];
        try{
            $response = Http::withHeaders([
                "Content-Type" => "application/json"
            ])->post($url, $payload);
            
            if ($response->successful()) {
                $data = $response->json();
                $user_id = $data['localId'];
                // Lưu thông tin người dùng vào CSDL của ứng dụng của bạn
                var_dump($data);
                var_dump($user_id);
            } else {
                $error = $response->json()['error']['message'];
                // Xử lý lỗi
                var_dump($error);
            }
        }catch(\Exception $ex){
            return var_dump('Error: ' . $ex->getMessage());
        }
        try {
            // Generate JWT token
            $nowSeconds = time();
            $payload = array(
                "iss" => $email,
                "sub" => $email,
                "aud" => "https://identitytoolkit.googleapis.com/google.identity.identitytoolkit.v1.IdentityToolkit",
                "iat" => $nowSeconds,
                "exp" => $nowSeconds + 3600,
                "uid" => "firebase-admin"
            );
            $jwt = JWT::encode($payload, $privateKey, "RS256", $projectId);
            
            // Verify JWT token with Firebase
            $client = new \GuzzleHttp\Client();
            
            $response = $client->post("https://www.googleapis.com/identitytoolkit/v3/relyingparty/verifyCustomToken?key=$key", [
                'json' => [
                    'token' => $jwt,
                    'returnSecureToken' => true
                ]
            ]);
            
            // Check if token is valid
            $responseData = json_decode($response->getBody(), true);
            return isset($responseData['idToken']);
        } catch (\Exception $ex) {
            return var_dump('Error: ' . $ex->getMessage());
        }
    }
    public function changeRole(Request $request)
{
    // Lấy dữ liệu gửi lên từ client
    $username = $request->input('username');

    // Tìm user với username tương ứng trong database
    $user = User::where('username', $username)->first();  
    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    // $role = $request->input('role');
    $role = $user->role;
    if (!$role) {
        $role = 'admin';   
    } else if ($role !== 'admin' && $role !== 'user') {
        return response()->json(['error' => 'Invalid role'], 400);
    }else{
        if ($role === 'admin') {
            $user->role = 'user';
        } elseif ($role === 'user') {
            $user->role = 'admin';
        } else {
            return response()->json(['error' => 'Invalid user role'], 400);
        }
    }
    var_dump($user->role);
    $user->save();

    return response()->json(['message' => 'User role updated successfully'], 200);
}
}
