<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Requests\Event\CreateEventRequest;
use App\Http\Requests\Event\FindEventRequest;
use App\Http\Requests\Event\UpdateEventRequest;
use App\Http\Controllers\Controller;
use App\Library\ApiResponseLibrary;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Event\CreateEventService;
use App\Services\Event\UpdateEventService;
use App\Services\Event\FindEventService;

class EventController extends Controller
{
    protected $apiLib;
    protected $createEventService;
    protected $updateEventService;
    protected $findEventService;

    public function __construct()
    {
        $this->apiLib = new ApiResponseLibrary;
        $this->createEventService = new CreateEventService();
        $this->updateEventService = new UpdateEventService();
        $this->findEventService = new FindEventService();
    }

    /**
     * get all event with response on ApiResponseLibrary, using list paginate
     * because it's on bulk data, don't confuse with query get->all. method listPaginate
     * get list item and get collection item from query.
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = $this->findEventService->getAllEvent();
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
     * get Event (Public because all user can see detail of the Event)
     * @param $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getEventPublic(FindEventRequest $request)
    {
        try {
            $data = $this->findEventService->findEventById($request->event_id);
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
     * get All Event created by user login using Auth::id() facade.
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getAllEventByUser()
    {
        try {
            $data = $this->findEventService->findAllEventByUser();
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
     * Save Event by user Login.
     * @param CreateEventRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(CreateEventRequest $request)
    {
        try {
            $data = $this->createEventService->createEvent($request);
            $response = $this->apiLib->singleData($data, []);
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update Event. this method can't handle whose user going to update the event (Handle By Android),
     * and get data which event gonna be updated by method getAllEventByUser().
     * because user could have many event (and other feature Activity and Community)
     * @param UpdateEventRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update(UpdateEventRequest $request)
    {
        try {
            $data = $this->findEventService->findEventById($request->event_id);
            if (is_null($data)){
                $response = $this->apiLib->notFoundResponse();
                return response($response, Response::HTTP_NOT_FOUND);
            }
            $data = $this->updateEventService->updateEvent($request);
            $return = $this->apiLib->singleData($data, []);
            return response($return, Response::HTTP_OK);
        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
