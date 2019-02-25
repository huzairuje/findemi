<?php

namespace App\Http\Requests\Activity;

use Illuminate\Foundation\Http\FormRequest;
use App\Library\ApiResponseLibrary;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class UpdateActivityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'max:255',
            'description' => 'max:255',
            'address' => 'max:255',
            'tag' => 'max:25',
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
            'is_one_trip' => 'boolean',
            'start_date' => 'date',
            'end_date' => 'date',
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
            Response::HTTP_BAD_REQUEST));
    }

}
