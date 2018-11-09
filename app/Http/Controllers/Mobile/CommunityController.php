<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Library\ApiResponseLibrary;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

use App\Services\Community\CreateCommunityService;
use App\Services\Community\UpdateCommunityService;
use App\Services\Community\FindCommunityService;

use App\Validators\CommunityValidator;

class CommunityController extends Controller
{
    protected $apiLib;
    protected $createCommunityService;
    protected $updateCommunityService;
    protected $findCommunityService;
    protected $communityValidator;

    public function __construct()
    {
        $this->apiLib = new ApiResponseLibrary;
        $this->createCommunityService = new CreateCommunityService();
        $this->updateCommunityService = new UpdateCommunityService();
        $this->findCommunityService = new FindCommunityService();
        $this->communityValidator = new CommunityValidator();

    }

    public function index()
    {
        try {
            $data = $this->findCommunityService->getAllCommunity();
            $response = $this->apiLib->listPaginate($data);
            return response($response, Response::HTTP_OK);

        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);
        }
    }

    public function getCommunityPublic($id)
    {
        try {
            $data = $this->findCommunityService->findCommunityById($id);

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
            $validator = $this->communityValidator->validateCreate($request);

            if ($validator->fails()) {
                $response = $this->apiLib->validationFailResponse($validator->errors());
                return response($response, Response::HTTP_BAD_REQUEST);
            }

            $data = $this->createCommunityService->createCommunity($request);
            DB::commit();

            $response = $this->apiLib->singleData($data, []);
            return response($response, Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollback();
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);
        }

    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = $this->findCommunityService->findCommunityById($id);

            if (is_null($data)){
                $response = $this->apiLib->notFoundResponse();
                return response($response, Response::HTTP_NOT_FOUND);

            } else {
                $validator = $this->communityValidator->validateUpdate($request);

                if ($validator->fails()) {
                    $response = $this->apiLib->validationFailResponse($validator->errors());
                    return response($response, Response::HTTP_BAD_REQUEST);

                }

                $data = $this->updateCommunityService->updateCommunity($request, $id);
                DB::commit();

                $return = $this->apiLib->singleData($data, []);
                return response($return, Response::HTTP_OK);

            }

        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);

        }

    }
}
