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
    protected $apiResponseLibrary;
    protected $createCommunityService;
    protected $updateCommunityService;
    protected $findCommunityService;

    public function __construct(ApiResponseLibrary $apiResponseLibrary,
                                CreateCommunityService $createCommunityService,
                                UpdateCommunityService $updateCommunityService,
                                FindCommunityService $findCommunityService)
    {
        $this->apiResponseLibrary = $apiResponseLibrary;
        $this->createCommunityService = $createCommunityService;
        $this->updateCommunityService = $updateCommunityService;
        $this->findCommunityService = $findCommunityService;
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
            if ($data === null) {
                $response = $this->apiResponseLibrary->notFoundResponse();
                return response($response, Response::HTTP_NOT_FOUND);
            }
            $data = $this->findCommunityService->getAllCommunity();
            $response = $this->apiResponseLibrary->listPaginate($data, 10);
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            $response = $this->apiResponseLibrary->errorResponse($e);
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
            if ($data === null) {
                $response = $this->apiResponseLibrary->notFoundResponse();
                return response($response, Response::HTTP_NOT_FOUND);
            }
            $response = $this->apiResponseLibrary->singleData($data, []);
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            $response = $this->apiResponseLibrary->errorResponse($e);
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
            if ($data === null) {
                $response = $this->apiResponseLibrary->notFoundResponse();
                return response($response, Response::HTTP_NOT_FOUND);
            }
            $response = $this->apiResponseLibrary->listPaginate($data, 10);
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            $response = $this->apiResponseLibrary->errorResponse($e);
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
            $response = $this->apiResponseLibrary->singleData($data, []);
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiResponseLibrary->errorResponse($e);
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
            if ($data === null){
                $response = $this->apiResponseLibrary->notFoundResponse();
                return response($response, Response::HTTP_NOT_FOUND);
            }
            $data = $this->updateCommunityService->updateCommunity($request);
            $return = $this->apiResponseLibrary->singleData($data, []);
            return response($return, Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiResponseLibrary->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
