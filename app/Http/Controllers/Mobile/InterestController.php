<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Library\ApiResponseLibrary;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

use App\Services\Interest\CreateInterestService;
use App\Services\Interest\UpdateInterestService;
use App\Services\Interest\FindInterestService;

use App\Services\Interest\CreateUserInterestService;
use App\Services\Interest\CreateActivityInterestService;
use App\Services\Interest\CreateCommunityInterestService;
use App\Services\Interest\CreateEventInterestService;

use App\Validators\InterestValidator;

class InterestController extends Controller
{
    protected $apiLib;
    protected $createInterestService;
    protected $updateInterestService;
    protected $findInterestService;
    protected $interestValidator;

    protected $createUserInterestService;
    protected $createActivityInterestService;
    protected $createCommunityService;
    protected $createEventInterestService;

    public function __construct()
    {
        $this->apiLib = new ApiResponseLibrary;
        $this->createInterestService = new CreateInterestService();
        $this->updateInterestService = new UpdateInterestService();
        $this->findInterestService = new FindInterestService();
        $this->interestValidator = new InterestValidator();

        $this->createUserInterestService = new CreateUserInterestService();
        $this->createActivityInterestService = new CreateActivityInterestService();
        $this->createCommunityService = new CreateCommunityInterestService();
        $this->createEventInterestService = new CreateEventInterestService();

    }

    public function index()
    {
        try {
            $data = $this->findInterestService->getAllInterest();
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

    public function getInterestPublic($id)
    {
        try {
            $data = $this->findInterestService->findInterestById($id);
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

    public function store(Request $request)
    {
        try {
            $validator = $this->interestValidator->validateCreate($request);
            if ($validator->fails()) {
                $response = $this->apiLib->validationFailResponse($validator->errors());
                return response($response, Response::HTTP_BAD_REQUEST);
            }
            $data = $this->createInterestService->createInterest($request);
            $response = $this->apiLib->singleData($data, []);
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);
        }

    }

    public function createUserInterest(Request $request)
    {
        try {
            $data = $this->createUserInterestService->createUserInterest($request);
            $response = $this->apiLib->singleData($data, []);
            return response($response, Response::HTTP_OK);
        }catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);
        }
    }

    public function createActivityInterest(Request $request)
    {
        try {
            $data = $this->createActivityInterestService->createActivityInterest($request);
            $response = $this->apiLib->singleData($data, []);
            return response($response, Response::HTTP_OK);
        }catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);
        }
    }

    public function createCommunityInterest(Request $request)
    {
        try {
            $data = $this->createCommunityService->createCommunityInterest($request);
            $response = $this->apiLib->singleData($data, []);
            return response($response, Response::HTTP_OK);
        }catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);
        }
    }

    public function createEventInterest(Request $request)
    {
        try {
            $data = $this->createEventInterestService->createUserInterest($request);
            $response = $this->apiLib->singleData($data, []);
            return response($response, Response::HTTP_OK);
        }catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);
        }
    }

}