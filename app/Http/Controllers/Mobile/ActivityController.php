<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Requests\Activity\CreateActivityRequest;
use App\Http\Requests\Activity\FindActivityRequest;
use App\Http\Requests\Activity\UpdateActivityRequest;
use App\Library\ActivitiesResponseLibrary;
use App\Library\ApiResponseLibrary;
use App\Http\Controllers\Controller;
use App\Services\Mobile\Activity\DeleteActivityService;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Activity\CreateActivityService;
use App\Services\Activity\UpdateActivityService;
use App\Services\Activity\FindActivityService;


class ActivityController extends Controller
{
    protected $apiResponseLibrary;
    protected $activityApiResponseLibrary;
    protected $createActivityService;
    protected $updateActivityService;
    protected $findActivityService;
    protected $deleteActivityService;

    public function __construct(ApiResponseLibrary $apiResponseLibrary,
                                ActivitiesResponseLibrary $activityApiResponseLibrary,
                                CreateActivityService $createActivityService,
                                UpdateActivityService $updateActivityService,
                                FindActivityService $findActivityService,
                                DeleteActivityService $deleteActivityService)
    {
        $this->apiResponseLibrary = $apiResponseLibrary;
        $this->activityApiResponseLibrary = $activityApiResponseLibrary;
        $this->createActivityService = $createActivityService;
        $this->updateActivityService = $updateActivityService;
        $this->findActivityService = $findActivityService;
        $this->deleteActivityService = $deleteActivityService;
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
     * get Activity (Public because all user can see detail of the activity)
     * @param $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getActivityPublic(FindActivityRequest $request)
    {
        try {
            $data = $this->findActivityService->findActivityById($request->input('activity_id'));
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
     * get All Activity created by user login using Auth::id() facade.
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getAllActivityByUser()
    {
        try {
            $data = $this->findActivityService->findAllActivityByUser();
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
     * Save Activity by user Login.
     * @param CreateActivityRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(CreateActivityRequest $request)
    {
        try {
            $data = $this->createActivityService->createActivity($request);
            $response = $this->apiResponseLibrary->singleData($data, []);
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiResponseLibrary->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update Activity. this method can't handle whose user going to update the activity (Handle By Android),
     * and get data which activity gonna be updated by method getAllActivityByUser().
     * because user could have many activity (and other feature Event and Community)
     * @param UpdateActivityRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(UpdateActivityRequest $request)
    {
        try {
            $data = $this->findActivityService->findActivityById($request->input('activity_id'));
            if ($data === null) {
                $return = $this->apiResponseLibrary->notFoundResponse();
                return response($return, Response::HTTP_NOT_FOUND);
            }
            $data = $this->updateActivityService->updateActivity($request);
            $return = $this->apiResponseLibrary->singleData($data, []);
            return response($return, Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiResponseLibrary->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(FindActivityRequest $request)
    {
        try {
            $data = $this->findActivityService->findActivityById($request->input('activity_id'));
            if ($data === null) {
                $return = $this->apiResponseLibrary->notFoundResponse();
                return response($return, Response::HTTP_NOT_FOUND);
            }
            $this->deleteActivityService->deleteActivity($request);
            $response = $this->activityApiResponseLibrary->successDeleteActivity();
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiResponseLibrary->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
