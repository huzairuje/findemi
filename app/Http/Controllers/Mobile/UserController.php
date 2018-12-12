<?php

namespace App\Http\Controllers\Mobile;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Library\ApiResponseLibrary;
use Illuminate\Support\Facades\DB;
use App\Validators\UserValidator;
use App\Services\User\UpdateUserService;
use App\Services\User\FindUserService;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    protected $apiLib;
    protected $userValidator;
    protected $updateUserService;
    protected $findUserService;
    protected $model;

    public function __construct()
    {
        $this->model = new User();
        $this->apiLib = new ApiResponseLibrary;
        $this->userValidator = new UserValidator();
        $this->updateUserService = new UpdateUserService();
        $this->findUserService = new FindUserService();
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
            $validator = $this->userValidator->validateUpdateProfile($request);
            if ($validator->fails()) {
                $response = $this->apiLib->validationFailResponse($validator->errors());
                return response($response, Response::HTTP_BAD_REQUEST);
            }
            $data = $this->updateUserService->update($request);
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
            $data = $this->findUserService->findUserById($id);
            if (is_null($data)) {
                $response = $this->apiLib->notFoundResponse();
                return response($response, Response::HTTP_NOT_FOUND);
            }
            $response = $this->apiLib->singleData($data, []);
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);
        }
    }

    /**
     * get all user with response on ApiResponseLibrary, using list paginate
     * because it's on bulk data, don't confuse with query get->all. method listPaginate
     * get list item and get collection item from query.
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getAllUser()
    {
        try {
            $data = $this->findUserService->getAllUser();
            if (is_null($data)) {
                $response = $this->apiLib->notFoundResponse();
                return response($response, Response::HTTP_NOT_FOUND);
            }
            $response = $this->apiLib->listPaginate($data);
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);
        }
    }
}
