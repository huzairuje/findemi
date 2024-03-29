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
use Exception;

class EventController extends Controller
{
    protected $apiResponseLibrary;
    protected $createEventService;
    protected $updateEventService;
    protected $findEventService;

    public function __construct(ApiResponseLibrary $apiResponseLibrary,
                                CreateEventService $createEventService,
                                UpdateEventService $updateEventService,
                                FindEventService $findEventService)
    {
        $this->apiResponseLibrary = $apiResponseLibrary;
        $this->createEventService = $createEventService;
        $this->updateEventService = $updateEventService;
        $this->findEventService = $findEventService;
    }

    /**
     * get all event with response on ApiResponseLibrary, using list paginate
     * because it's on bulk data, don't confuse with query get->all. method listPaginate
     * get list item and get collection item from query.
     * @return response
     */
    public function index()
    {
        try {
            $data = $this->findEventService->getAllEvent();
            if ($data === null) {
                $response = $this->apiResponseLibrary->notFoundResponse();
                return response($response, Response::HTTP_NOT_FOUND);
            }
            $response = $this->apiResponseLibrary->listPaginate($data, 10);
            return response($response, Response::HTTP_OK);
        } catch (Exception $e) {
            $response = $this->apiResponseLibrary->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * get Event (Public because all user can see detail of the Event)
     * @param $request
     * @return response
     */
    public function getEventPublic(FindEventRequest $request)
    {
        try {
            $data = $this->findEventService->findEventById($request->input('event_id'));
            if ($data === null) {
                $response = $this->apiResponseLibrary->notFoundResponse();
                return response($response, Response::HTTP_NOT_FOUND);
            }
            $response = $this->apiResponseLibrary->singleData($data, []);
            return response($response, Response::HTTP_OK);
        } catch (Exception $e) {
            $response = $this->apiResponseLibrary->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * get All Event created by user login using Auth::id() facade.
     * @return response
     */
    public function getAllEventByUser()
    {
        try {
            $data = $this->findEventService->findAllEventByUser();
            if ($data === null) {
                $response = $this->apiResponseLibrary->notFoundResponse();
                return response($response, Response::HTTP_NOT_FOUND);
            }
            $response = $this->apiResponseLibrary->listPaginate($data, 10);
            return response($response, Response::HTTP_OK);
        } catch (Exception $e) {
            $response = $this->apiResponseLibrary->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Save Event by user Login.
     * @param CreateEventRequest $request
     * @return response
     */
    public function store(CreateEventRequest $request)
    {
        try {
            $data = $this->createEventService->createEvent($request);
            $response = $this->apiResponseLibrary->singleData($data, []);
            return response($response, Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollBack();
            $response = $this->apiResponseLibrary->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update Event. this method can't handle whose user going to update the event (Handle By Android),
     * and get data which event gonna be updated by method getAllEventByUser().
     * because user could have many event (and other feature Activity and Community)
     * @param UpdateEventRequest $request
     * @return response
     */
    public function update(UpdateEventRequest $request)
    {
        try {
            $data = $this->findEventService->findEventById($request->input('event_id'));
            if ($data === null){
                $response = $this->apiResponseLibrary->notFoundResponse();
                return response($response, Response::HTTP_NOT_FOUND);
            }
            $data = $this->updateEventService->updateEvent($request);
            $return = $this->apiResponseLibrary->singleData($data, []);
            return response($return, Response::HTTP_OK);
        } catch (Exception $e) {
            $response = $this->apiResponseLibrary->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
