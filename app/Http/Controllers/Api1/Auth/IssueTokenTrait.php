<?php 

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use GuzzleHttp\Client;

trait IssueTokenTrait{

	public function issueToken(Request $request, $grantType, $scope = ""){
		$http = new Client;
 
		$response = $http->post(url('/').'/oauth/token', [
			'form_params' => [		
				'grant_type' => $grantType,
				'client_id' => $this->client->id,
				'client_secret' => $this->client->secret,    
				'username' => $request->email,
				'password' => $request->password,
				'scope' => $scope,
			],
		]);
		return json_decode((string) $response->getBody(), true);


		/*$params = [
    				
    		'grant_type' => $grantType,
			'client_id' => $this->client->id,
    		'client_secret' => $this->client->secret,    
			'username' => $request->email,
			'password' => $request->password,
			'scope' => $scope,
    	];
	
        // if($grantType !== 'social'){
        //     $params['email'] = $request->email ?: $request->email;
        // }

    	$request->request->add($params);
		//dd($params);
    	$proxy = Request::create('oauth/token', 'POST');

    	return Route::dispatch($proxy);*/

	}

}