<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Requests\User\FindUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Library\UserResponseLibrary;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Library\ApiResponseLibrary;
use Illuminate\Support\Facades\DB;
use App\Services\User\UpdateUserService;
use App\Services\User\FindUserService;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    protected $userResponseLibrary;
    protected $updateUserService;
    protected $findUserService;

    public function __construct(UserResponseLibrary $userResponseLibrary,
                                UpdateUserService $updateUserService,
                                FindUserService $findUserService)
    {
        $this->userResponseLibrary = $userResponseLibrary;
        $this->updateUserService = $updateUserService;
        $this->findUserService = $findUserService;
    }

    /**
     * Get the authenticated User
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getAuthenticatedUser(Request $request)
    {
        $response = $this->userResponseLibrary->singleData($request->user(), []);
        return response($response, Response::HTTP_OK);
    }

    /**
     * Update Bio Profile User. some method in update profile user is different, like update interest,
     * it's on InterestController@updateUserInterest
     * @param UpdateUserRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function updateProfile(UpdateUserRequest $request)
    {
        try {
            $data = $this->updateUserService->update($request);
            $return = $this->userResponseLibrary->singleData($data, []);
            return response($return, Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->userResponseLibrary->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get Detail User Public when user searching for another user.
     * @param $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getUserProfilePublic(FindUserRequest $request)
    {
        try {
            $data = $this->findUserService->findUserById($request->input('user_id'));
            if ($data === null) {
                $response = $this->userResponseLibrary->notFoundResponse();
                return response($response, Response::HTTP_NOT_FOUND);
            }
            $response = $this->userResponseLibrary->singleData($data, []);
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            $response = $this->userResponseLibrary->errorResponse($e);
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
            if ($data === null) {
                $response = $this->userResponseLibrary->notFoundResponse();
                return response($response, Response::HTTP_NOT_FOUND);
            }
            $response = $this->userResponseLibrary->listPaginate($data, 10);
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            $response = $this->userResponseLibrary->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
