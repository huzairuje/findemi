<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Requests\User\UpdateUserRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Library\ApiResponseLibrary;
use Illuminate\Support\Facades\DB;
use App\Services\User\UpdateUserService;
use App\Services\User\FindUserService;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    protected $apiLib;
    protected $updateUserService;
    protected $findUserService;

    public function __construct()
    {
        $this->apiLib = new ApiResponseLibrary;
        $this->updateUserService = new UpdateUserService();
        $this->findUserService = new FindUserService();
    }

    /**
     * Get the authenticated User
     * @param Request $request
     *
     * @return response
     */
    public function getAuthenticatedUser(Request $request)
    {
        $response = $this->apiLib->singleData($request->user(), []);
        return response($response, Response::HTTP_OK);
    }

    public function updateProfile(UpdateUserRequest $request)
    {
        try {
            $data = $this->updateUserService->update($request);
            $return = $this->apiLib->singleData($data, []);
            return response($return, Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
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
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
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
            $response = $this->apiLib->listPaginate($data, 10);
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
