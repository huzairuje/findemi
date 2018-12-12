<?php

namespace App\Http\Requests\Activity;

use App\Library\ApiResponseLibrary;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class CreateActivityRequest extends FormRequest
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
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'start_date' => 'required',
            'end_date' => 'required',
            'address' => 'required|max:255',
            'tag' => 'required|max:255',
            'lat' => 'required',
            'lon' => 'required',
            'address_from_map' => 'required',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator
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