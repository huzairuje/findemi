<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Library\ApiResponseLibrary;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    protected $apiLib;
    protected $model;

    public function __construct()
    {
        $this->apiLib = new ApiResponseLibrary;
        $this->model = new User();
//        $this->middleware('auth', ['only' => ['getAuthenticatedUser', 'updateProfile', 'safeDeleteAccount']]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function getAuthenticatedUser(Request $request)
    {
        $response = $this->apiLib->singleData($request->user(), []);
        return response($response, Response::HTTP_OK);
    }

    public function updateProfile(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'username' => 'string|unique:users',
                'first_name' => 'string',
                'last_name' => 'string',
                'phone' => 'unique:users|string',
                'gender' => 'boolean',
                'email' => 'string|email|unique:users',
                'password' => 'string|confirmed|min:6'
            ]);

            if ($validator->fails()) {
                $response = $this->apiLib->validationFailResponse($validator->errors());
                return response($response, Response::HTTP_BAD_REQUEST);
            }

        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);
        }
    }

    public function getUserProfilePublic($id)
    {
        //TODO
    }

}
