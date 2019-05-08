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
use Exception;

class InterestController extends Controller
{
    protected $apiResponseLibrary;
    protected $createInterestService;
    protected $updateInterestService;
    protected $findInterestService;
    protected $createUserInterestService;
    protected $createActivityInterestService;
    protected $createCommunityService;
    protected $createEventInterestService;
    protected $updateUserInterestService;

    public function __construct(ApiResponseLibrary $apiResponseLibrary,
                                CreateInterestService $createInterestService,
                                UpdateInterestService $updateInterestService,
                                FindInterestService $findInterestService,
                                CreateUserInterestService $createUserInterestService,
                                CreateCommunityInterestService $createCommunityInterestService,
                                CreateActivityInterestService $createActivityInterestService,
                                CreateEventInterestService $createEventInterestService,
                                UpdateUserInterestService $updateUserInterestService)
    {
        $this->apiResponseLibrary = $apiResponseLibrary;
        $this->createInterestService = $createInterestService;
        $this->updateInterestService = $updateInterestService;
        $this->findInterestService = $findInterestService;
        $this->createUserInterestService = $createUserInterestService;
        $this->createActivityInterestService = $createActivityInterestService;
        $this->createCommunityService = $createCommunityInterestService;
        $this->createEventInterestService = $createEventInterestService;
        $this->updateUserInterestService = $updateUserInterestService;

    }

    /**
     * get all interest with response on ApiResponseLibrary, using list paginate
     * because it's on bulk data, don't confuse with query get->all. method listPaginate
     * get list item and get collection item from query.
     * @return response
     */
    public function index()
    {
        try {
            $data = $this->findInterestService->getAllInterest();
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
     * get Interest (Public because all user can see Interest)
     * @param $id
     * @return response
     */
    public function getInterestPublic($id)
    {
        try {
            $data = $this->findInterestService->findInterestById($id);
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
     * Save Interest.
     * @param CreateInterestRequest $request
     * @return response
     */
    public function store(CreateInterestRequest $request)
    {
        try {
            $data = $this->createInterestService->createInterest($request);
            $response = $this->apiResponseLibrary->singleData($data, []);
            return response($response, Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollBack();
            $response = $this->apiResponseLibrary->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    /**
     * save Interest when user first time login and choosing interest after login form.
     * @param Request $request
     * @return response
     */
    public function createUserInterest(Request $request)
    {
        try {
            $data = $this->createUserInterestService->createUserInterest($request);
            $response = $this->apiResponseLibrary->singleData($data, []);
            return response($response, Response::HTTP_OK);
        }catch (Exception $e) {
            DB::rollBack();
            $response = $this->apiResponseLibrary->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * save Interest when user create Activity and choosing interest.
     * @param Request $request
     * @return response
     */
    public function createActivityInterest(Request $request)
    {
        try {
            $data = $this->createActivityInterestService->createActivityInterest($request);
            $response = $this->apiResponseLibrary->singleData($data, []);
            return response($response, Response::HTTP_OK);
        }catch (Exception $e) {
            DB::rollBack();
            $response = $this->apiResponseLibrary->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * save Interest when user create Community and choosing interest.
     * @param Request $request
     * @return response
     */
    public function createCommunityInterest(Request $request)
    {
        try {
            $data = $this->createCommunityService->createCommunityInterest($request);
            $response = $this->apiResponseLibrary->singleData($data, []);
            return response($response, Response::HTTP_OK);
        }catch (Exception $e) {
            DB::rollBack();
            $response = $this->apiResponseLibrary->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * save Interest when user create Event and choosing interest.
     * @param Request $request
     * @return response
     */
    public function createEventInterest(Request $request)
    {
        try {
            $data = $this->createEventInterestService->createEventInterest($request);
            $response = $this->apiResponseLibrary->singleData($data, []);
            return response($response, Response::HTTP_OK);
        }catch (Exception $e) {
            DB::rollBack();
            $response = $this->apiResponseLibrary->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * update Interest when user update on her bio (account detail).
     * @param Request $request
     * @return response
     */
    public function updateUserInterest(Request $request)
    {
        try {
            $data = $this->updateUserInterestService->updateUserInterest($request);
            $response = $this->apiResponseLibrary->singleData($data, []);
            return response($response, Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollBack();
            $response = $this->apiResponseLibrary->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
