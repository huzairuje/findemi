<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Library\ApiResponseLibrary;
use Illuminate\Support\Facades\DB;
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
        DB::beginTransaction();
        $data = $this->model->findOrFail($request->user()->id);

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

            $data->username = $request->username;
            $data->first_name = $request->first_name;
            $data->last_name = $request->last_name;
            $data->gender = $request->gender;
            $data->email = $request->email;
            $data->phone = $request->phone;
            $data->password = bcrypt($request->password);

            $data->update();
            DB::commit();

            $return = $this->apiLib->singleData($data, []);
            return response($return, Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);

        }
    }

    public function getUserProfilePublic($id)
    {
        try {
            $data = $this->model->find($id);

            if (is_null($data)) {
                $response = $this->apiLib->notFoundResponse();
                return response($response, Response::HTTP_NOT_FOUND);

            } else {
                $response = $this->apiLib->singleData($data, []);
                return response($response, Response::HTTP_OK);

            }

        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);

        }
    }

}
