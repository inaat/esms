<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Models\Student;
use Auth;
use Exception;
use GuzzleHttp\Client;
use Laravel\Passport\Client as OClient; 
class AuthController extends Controller
{

    public $successStatus = 200;
    public function getTokenRefreshToken() { 
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) { 
            $oClient = OClient::where('password_client', 1)->first();
            return $this->getTokenAndRefreshToken($oClient, request('email'), request('password'));
        } 
        else { 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }
    
    public function getTokenAndRefreshToken(OClient $oClient, $email, $password) { 
        $oClient = OClient::where('password_client', 1)->first();
        $http = new Client;
        $response = $http->request('POST', 'http://localhost/esms/public/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $oClient->id,
                'client_secret' => $oClient->secret,
                'username' => $email,
                'password' => $password,
                'scope' => '*',
            ],
        ]);
        $result = json_decode($response->getBody());
        return response()->json($result, $this->successStatus);
    }
    
    public function login(Request $request)
    {
        $validData = $request->validate([
            'email' => 'required|email|string',
            'password' => 'required|string'
        ]);

        if(!Auth::attempt($validData)){
            return response([
                'message' => 'Invalid credentials!',
                'success' => 0
            ]);
        }
       // $oClient = OClient::where('password_client', 1)->first();
       $accessToken = Auth::user()->createToken(Auth::user()->first_name)->accessToken;
        //$accessToken = $this->getTokenAndRefreshToken($oClient, request('email'), request('password'));
        $hook_data=[];
        if(Auth::user()->user_type == 'admin'){
            https://gyandaacademy.illuminatetech.net/api/login
        }
        elseif(Auth::user()->user_type == 'student'){
            
            $hook_data=Student::with(['campuses','current_class','current_class_section'])->findOrFail(Auth::user()->hook_id);
        } 
        
        return  response([
                    'user' => Auth::user(), 
                    'hook_data' => $hook_data,
                    'access_token' => $accessToken,
                    'success' => 1
                ]);
    }

// // CreateToken creates a new token for a specific username and duration
// func (maker *JWTMaker) CreateToken(UID uuid.UUID, duration time.Duration) (string, *Payload, error) {
// 	payload, err := NewPayload(UID, duration)
// 	if err != nil {
// 		return "", payload, err
// 	}

// 	jwtToken := jwt.NewWithClaims(jwt.SigningMethodHS256, payload)
// 	token, err := jwtToken.SignedString([]byte(maker.secretKey))
// 	return token, payload, err
// }



    public function profile(Request $request)
    {
        $user = Auth::user();
        $roles = $user->getRoleNames();
        $permission = $user->getAllPermissions();
        return response([
                    'user' => $user,
                    'success' => 1
                ]);
    }


    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string',
            'password' => 'required|string|confirmed'
        ]);

        // match old password
        if (Hash::check($request->old_password, Auth::user()->password)){

            User::find(auth()->user()->id)
            ->update([
                'password'=> Hash::make($request->password)
            ]);

            return response([
                        'message' => 'Password has been changed',
                        'status'  => 1
                    ]);
            
        }
            return response([
                        'message' => 'Password not matched!',
                        'status'  => 0
                    ]);
    }


    public function updateProfile(Request $request)
    {
        $validData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email'
        ]);

        $user = Auth::user();
        // check unique email except this user
        if(isset($request->email)){
            $check = User::where('email', $request->email)
                     ->where('id', '!=', $user->id)
                     ->count();
            if($check > 0){
                return response([
                    'message' => 'The email address is already used!',
                    'success' => 0
                ]);
            }
        }

        $user->update($validData);

        
        return response([
                    'message' => 'Profile updated successfully!',
                    'status'  => 1
                ]);
    }


    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }
}
