<?php

namespace App\Http\Requests\User;

use App\Library\ApiResponseLibrary;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'string|unique:users',
            'first_name' => 'string',
            'last_name' => 'string',
            'phone' => 'unique:users|string',
            'email' => 'string|email|unique:users',
            'password' => 'string|confirmed|min:6'
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @return void
     *
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $responseLib = new ApiResponseLibrary();
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json($responseLib->validationFailResponse($errors),
            Response::HTTP_UNPROCESSABLE_ENTITY));
    }

}
