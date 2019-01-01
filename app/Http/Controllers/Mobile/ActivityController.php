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
    protected $apiLib;
    protected $activityApiLib;
    protected $createActivityService;
    protected $updateActivityService;
    protected $findActivityService;
    protected $deleteActivityService;

    public function __construct()
    {
        $this->apiLib = new ApiResponseLibrary;
        $this->activityApiLib = new ActivitiesResponseLibrary();
        $this->createActivityService = new CreateActivityService();
        $this->updateActivityService = new UpdateActivityService();
        $this->findActivityService = new FindActivityService();
        $this->deleteActivityService = new DeleteActivityService();
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
            $response = $this->apiLib->listPaginate($data, 10);
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
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
            $data = $this->findActivityService->findActivityById($request->activity_id);
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
     * get All Activity created by user login using Auth::id() facade.
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getAllActivityByUser()
    {
        try {
            $data = $this->findActivityService->findAllActivityByUser();
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
     * Save Activity by user Login.
     * @param CreateActivityRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(CreateActivityRequest $request)
    {
        try {
            $data = $this->createActivityService->createActivity($request);
            $response = $this->apiLib->singleData($data, []);
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update Activity. this method can't handle whose user going to update the activity (Handle By Android),
     * and get data which activity gonna be updated by method getAllActivityByUser().
     * because user could have many activity (and other feature Event and Community)
     * @param UpdateActivityRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(UpdateActivityRequest $request)
    {
        try {
            $data = $this->findActivityService->findActivityById($request->activity_id);
            if (is_null($data)) {
                $return = $this->apiLib->notFoundResponse();
                return response($return, Response::HTTP_NOT_FOUND);
            }
            $data = $this->updateActivityService->updateActivity($request);
            $return = $this->apiLib->singleData($data, []);
            return response($return, Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(FindActivityRequest $request)
    {
        try {
            $data = $this->findActivityService->findActivityById($request->activity_id);
            if (is_null($data)) {
                $return = $this->apiLib->notFoundResponse();
                return response($return, Response::HTTP_NOT_FOUND);
            }
            $this->deleteActivityService->deleteActivity($request->activity_id);
            $response = $this->activityApiLib->successDeleteActivity();
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
