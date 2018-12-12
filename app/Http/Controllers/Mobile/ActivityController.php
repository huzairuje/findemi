<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Requests\Activity\CreateActivityRequest;
use App\Http\Requests\Activity\UpdateActivityRequest;
use App\Library\ApiResponseLibrary;
use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Activity\CreateActivityService;
use App\Services\Activity\UpdateActivityService;
use App\Services\Activity\FindActivityService;
use App\Validators\ActivityValidator;

class ActivityController extends Controller
{
    protected $apiLib;
    protected $eventValidator;
    protected $createActivityService;
    protected $updateActivityService;
    protected $findActivityService;

    public function __construct()
    {
        $this->apiLib = new ApiResponseLibrary;
        $this->eventValidator = new ActivityValidator();
        $this->createActivityService = new CreateActivityService();
        $this->updateActivityService = new UpdateActivityService();
        $this->findActivityService = new FindActivityService();
    }

    /**
     * get all activity with response on ApiResponseLibrary, using list paginate
     * because it's on bulk data, don't confuse with query get->all. method listPaginate
     * get list item and get collection item from query.
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = $this->findActivityService->getAllActivity();
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

    public function getActivityPublic($id)
    {
        try {
            $data = $this->findActivityService->findActivityById($id);
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

    public function store(CreateActivityRequest $request)
    {
        try {
//            $validator = $this->eventValidator->validateCreate($request);
//            if ($validator->fails()) {
//                $response = $this->apiLib->validationFailResponse($validator->errors());
//                return response($response, Response::HTTP_BAD_REQUEST);
//            }
            $data = $this->createActivityService->createActivity($request);
            $response = $this->apiLib->singleData($data, []);
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);
        }
    }

    public function update(UpdateActivityRequest $request, $id)
    {
        try {
            $data = $this->findActivityService->findActivityById($id);
            if (is_null($data)){
                $response = $this->apiLib->notFoundResponse();
                return response($response, Response::HTTP_NOT_FOUND);
            }
//            $validator = $this->eventValidator->validateUpdate($request);
//            if ($validator->fails()) {
//                $response = $this->apiLib->validationFailResponse($validator->errors());
//                return response($response, Response::HTTP_BAD_REQUEST);
//            }
            $data = $this->updateActivityService->updateActivity($request, $id);
            $return = $this->apiLib->singleData($data, []);
            return response($return, Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);
        }
    }
}
