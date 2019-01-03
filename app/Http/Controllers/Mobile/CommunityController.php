<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Requests\Community\CreateCommunityRequest;
use App\Http\Requests\Community\FindCommunityRequest;
use App\Http\Requests\Community\UpdateCommunityRequest;
use App\Http\Controllers\Controller;
use App\Library\ApiResponseLibrary;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use App\Services\Community\CreateCommunityService;
use App\Services\Community\UpdateCommunityService;
use App\Services\Community\FindCommunityService;

class CommunityController extends Controller
{
    protected $apiLib;
    protected $createCommunityService;
    protected $updateCommunityService;
    protected $findCommunityService;

    public function __construct()
    {
        $this->apiLib = new ApiResponseLibrary;
        $this->createCommunityService = new CreateCommunityService();
        $this->updateCommunityService = new UpdateCommunityService();
        $this->findCommunityService = new FindCommunityService();
    }

    /**
     * get all community with response on ApiResponseLibrary, using list paginate
     * because it's on bulk data, don't confuse with query get->all. method listPaginate
     * get list item and get collection item from query.
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = $this->findCommunityService->getAllCommunity();
            if (is_null($data)) {
                $response = $this->apiLib->notFoundResponse();
                return response($response, Response::HTTP_NOT_FOUND);
            }
            $data = $this->findCommunityService->getAllCommunity();
            $response = $this->apiLib->listPaginate($data, 10);
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * get Community (Public because all user can see detail of the community)
     * @param $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getCommunityPublic(FindCommunityRequest $request)
    {
        try {
            $data = $this->findCommunityService->findCommunityById($request->input('community_id'));
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
     * get All Community created by user login using Auth::id() facade.
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getAllCommunityByUser()
    {
        try {
            $data = $this->findCommunityService->findAllCommunityByUser();
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

    /**
     * Save Community by user Login.
     * @param CreateCommunityRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(CreateCommunityRequest $request)
    {
        try {
            $data = $this->createCommunityService->createCommunity($request);
            $response = $this->apiLib->singleData($data, []);
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollback();
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update Community. this method can't handle whose user going to update the community (Handle By Android),
     * and get data which community and get list by method getAllCommunityByUser().
     * gonna be updated by this method.
     * because user could have many community (and other feature Activity and Event)
     * @param UpdateCommunityRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(UpdateCommunityRequest $request)
    {
        try {
            $data = $this->findCommunityService->findCommunityById($request->input('community_id'));
            if (is_null($data)){
                $response = $this->apiLib->notFoundResponse();
                return response($response, Response::HTTP_NOT_FOUND);
            }
            $data = $this->updateCommunityService->updateCommunity($request);
            $return = $this->apiLib->singleData($data, []);
            return response($return, Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
