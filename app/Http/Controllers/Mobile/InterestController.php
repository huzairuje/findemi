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

            } else {
                $response = $this->apiLib->singleData($data, []);
                return response($response, Response::HTTP_OK);

            }

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

            } else {
                $response = $this->apiLib->singleData($data, []);
                return response($response, Response::HTTP_OK);

            }

        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);

        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = $this->interestValidator->validateCreate($request);

            if ($validator->fails()) {
                $response = $this->apiLib->validationFailResponse($validator->errors());
                return response($response, Response::HTTP_BAD_REQUEST);
            }
            $data = $this->createInterestService->createInterest($request);
            DB::commit();

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
        DB::beginTransaction();
        try {

            $data = $this->createUserInterestService->createUserInterest($request);
            DB::commit();

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
        DB::beginTransaction();
        try {

            $data = $this->createActivityInterestService->createActivityInterest($request);
            DB::commit();

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
        DB::beginTransaction();
        try {

            $data = $this->createCommunityService->createCommunityInterest($request);
            DB::commit();

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
        DB::beginTransaction();
        try {

            $data = $this->createEventInterestService->createUserInterest($request);
            DB::commit();

            $response = $this->apiLib->singleData($data, []);
            return response($response, Response::HTTP_OK);

        }catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);
        }
    }

}