<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Library\ApiResponseLibrary;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Event\CreateEventService;
use App\Services\Event\UpdateEventService;
use App\Services\Event\FindEventService;
use App\Validators\EventValidator;

class EventController extends Controller
{
    protected $apiLib;
    protected $eventValidator;
    protected $createEventService;
    protected $updateEventService;
    protected $findEventService;

    public function __construct()
    {
        $this->apiLib = new ApiResponseLibrary;
        $this->eventValidator = new EventValidator();
        $this->createEventService = new CreateEventService();
        $this->updateEventService = new UpdateEventService();
        $this->findEventService = new FindEventService();
    }

    public function index()
    {
        try {
            $data = $this->findEventService->getAllEvent();
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

    public function getEventPublic($id)
    {
        try {
            $data = $this->findEventService->findEventById($id);
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

    public function store(Request $request)
    {
        try {
            $validator = $this->eventValidator->validateCreate($request);
            if ($validator->fails()) {
                $response = $this->apiLib->validationFailResponse($validator->errors());
                return response($response, Response::HTTP_BAD_REQUEST);
            }
            $data = $this->createEventService->createEvent($request);
            $response = $this->apiLib->singleData($data, []);
            return response($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $this->findEventService->findEventById($id);
            if (is_null($data)){
                $response = $this->apiLib->notFoundResponse();
                return response($response, Response::HTTP_NOT_FOUND);
            }
            $validator = $this->eventValidator->validateUpdate($request);
            if ($validator->fails()) {
                $response = $this->apiLib->validationFailResponse($validator->errors());
                return response($response, Response::HTTP_BAD_REQUEST);
            }
            $data = $this->updateEventService->updateEvent($request, $id);
            $return = $this->apiLib->singleData($data, []);
            return response($return, Response::HTTP_OK);
        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);
        }
    }
}
