<?php

namespace App\Http\Controllers\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

class RefreshTokenRequest extends FormRequest
{
  

    public function rules()
    {
        return [
            'refresh_token' => 'required'
        ];
    }
    public function failedValidation(Validator $validator)
    {
        //dd($validator->errors());
        throw new HttpResponseException(
             response([
                'error'   => $validator->errors()->first()
            ], Response::HTTP_BAD_REQUEST)
           
    );

    
    }
 
}