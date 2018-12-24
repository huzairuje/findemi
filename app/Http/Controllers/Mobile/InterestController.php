<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Requests\Interest\CreateInterestRequest;
use App\Services\Interest\UpdateUserInterestService;
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


class InterestController extends Controller
{
    protected $apiLib;
    protected $createInterestService;
    protected $updateInterestService;
    protected $findInterestService;

    protected $createUserInterestService;
    protected $createActivityInterestService;
    protected $createCommunityService;
    protected $createEventInterestService;
    protected $updateUserInterestService;

    public function __construct()
    {
        $this->apiLib = new ApiResponseLibrary;
        $this->createInterestService = new CreateInterestService();
        $this->updateInterestService = new UpdateInterestService();
        $this->findInterestService = new FindInterestService();

        $this->createUserInterestService = new CreateUserInterestService();
        $this->createActivityInterestService = new CreateActivityInterestService();
        $this->createCommunityService = new CreateCommunityInterestService();
        $this->createEventInterestService = new CreateEventInterestService();
        $this->updateUserInterestService = new UpdateUserInterestService();

    }

    /**
     * get all interest with response on ApiResponseLibrary, using list paginate
     * because it's on bulk data, don't confuse with query get->all. method listPaginate
     * get list item and get collection item from query.
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = $this->findInterestService->getAllInterest();
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
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(CreateInterestRequest $request)
    {
        try {
            $data = $this->createInterestService->createInterest($request);
            $response = $this->apiLib->singleData($data, []);
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
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
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
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
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
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
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createEventInterest(Request $request)
    {
        try {
            $data = $this->createEventInterestService->createEventInterest($request);
            $response = $this->apiLib->singleData($data, []);
            return response($response, Response::HTTP_OK);
        }catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateUserInterest(Request $request)
    {
        try {
            $data = $this->updateUserInterestService->updateUserInterest($request);
            $response = $this->apiLib->singleData($data, []);
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}