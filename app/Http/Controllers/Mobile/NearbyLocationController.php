<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Requests\NearbyLocation\SubmitNearbyLocationRequest;
use App\Library\ApiResponseLibrary;
use App\Services\Mobile\NearbyLocation\GetUserLocationService;
use App\Services\Mobile\NearbyLocation\SubmitUserLocationService;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class NearbyLocationController extends Controller
{
    protected $apiResponseLibrary;
    protected $submitUserLocation;
    protected $getUserLocation;

    public function __construct(ApiResponseLibrary $apiResponseLibrary,
                                SubmitUserLocationService $submitUserLocationService,
                                GetUserLocationService $getUserLocationService)
    {
        $this->apiResponseLibrary = $apiResponseLibrary;
        $this->submitUserLocation = $submitUserLocationService;
        $this->getUserLocation = $getUserLocationService;
    }

    /** submit Location User and save it (Without Authorization / Anyone can access this method)
     * @param SubmitNearbyLocationRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */

    public function checkNearby(SubmitNearbyLocationRequest $request)
    {
        try {
            $data = $this->submitUserLocation->getNearbyUser($request);
            $return = $this->apiResponseLibrary->listPaginate($data, 50);
            return response($return, Response::HTTP_OK);
        } catch (\Exception $e) {
            $response = $this->apiResponseLibrary->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
