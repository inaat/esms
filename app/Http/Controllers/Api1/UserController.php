<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Auth;
use App\Http\Controllers\Api\Resources\UserResource;
use App\Http\Controllers\Api\Resources\StudentResource;

class UserController extends Controller
{

    /**
     * @OA\Get(
     *   path="/api/profile",
    *   security={{"bearerAuth":{}}},
     *   tags={"Student"},
     *   @OA\Response(response="200",
     *     description="Get profile",
     *   )
     * )
     */
    public function profile()
    {      
         try {
        $user = User::find(Auth::user()->id);
        
        if($user->user_type==='student'){

        return response(new StudentResource($user));

        }
        // $roles = $user->getRoleNames();
        // $permission = $user->getAllPermissions();
        return response(new UserResource($user));
    } catch (\Exception $e) {
        $response = array(
            'error' => true,
            'message' => trans('error_occurred'),
            'code' => 103
        );
        return response()->json($response, 200);
    }
}

   
}
