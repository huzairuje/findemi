<?php

namespace App\Http\Controllers\Mobile;

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

    public function __construct()
    {
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
        DB::beginTransaction();
        try {
            $validator = $this->userValidator->validateUpdateProfile($request);

            if ($validator->fails()) {
                $response = $this->apiLib->validationFailResponse($validator->errors());
                return response($response, Response::HTTP_BAD_REQUEST);
            }

            $data = $this->updateUserService->update($request);
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
            $data = $this->findUserService->findUserById($id);

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
