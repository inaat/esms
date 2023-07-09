<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Client;
use Laravel\Passport\Client as OClient; 
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    use IssueTokenTrait;

	private $client;

	public function __construct(){
		$this->client  = OClient::where('password_client', 1)->first();

	}
 /**
        * @OA\Post(
        * path="/api/login",
        * operationId="authLogin",
        * tags={"Login"},
        * summary="User Login",
        * description="Login User Here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"email", "password"},
        *               @OA\Property(property="email", type="email"),
        *               @OA\Property(property="password", type="password")
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Login Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Login Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */



    
    public function login(Request $request){
       
		$validator = Validator::make($request->all(), [
			'email' => 'required',
    		'password' => 'required'		   
		]);
	
	    if ($validator->fails()) {
			$responseArr['error'] = $validator->errors()->first();
			return response()->json($responseArr, Response::HTTP_BAD_REQUEST);
		}
    	
		if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return $this->issueToken($request, 'password');
		}else {
       
			return response([
				'error' => 'Invalid Credentials!',
			], Response::HTTP_BAD_REQUEST);
        }

    }

	/**
        * @OA\Post(
        * path="/api/refresh-token",
        * operationId="authRefreshToken",
        * tags={"Login"},
        * summary="User Refresh Token",
        * description="Refresh Token Here",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"refresh_token"},
        *               @OA\Property(property="refresh_token", type="text"),
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Refresh Token Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Refresh Token Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */
    public function refresh(Request $request){
		
		$validator = Validator::make($request->all(), [
            'refresh_token' => 'required'
		   
		]);
	
	    if ($validator->fails()) {
			$responseArr['error'] = $validator->errors()->first();
			return response()->json($responseArr, Response::HTTP_BAD_REQUEST);
		}
    	return $this->issueToken($request, 'refresh_token');

    }
/**
     * @OA\Post(
     *   path="/api/logout",
	 *   security={{"bearerAuth":{}}},
     *   tags={"Login"},
     *   @OA\Response(response="200",
     *     description="Logout Successfully",
     *   )
     * )
     */
    public function logout(Request $request){
        
    	$accessToken = Auth::user()->token();

    	DB::table('oauth_refresh_tokens')
    		->where('access_token_id', $accessToken->id)
    		->update(['revoked' => true]);

    	$accessToken->revoke();
		$response = ['message' => 'You have been successfully logged out!'];
        //dd( $response);
        return response($response, 200);

    }
}
