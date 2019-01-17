<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Requests\NearbyLocation\SubmitNearbyLocationRequest;
use App\Library\ApiResponseLibrary;
use App\Services\Mobile\NearbyLocation\GetUserLocationService;
use App\Services\Mobile\NearbyLocation\SubmitUserLocationService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class NearbyLocationController extends Controller
{
    protected $apiLib;
    protected $submitUserLocation;
    protected $getUserLocation;

    public function __construct()
    {
        $this->apiLib = new ApiResponseLibrary();
        $this->submitUserLocation = new SubmitUserLocationService();
        $this->getUserLocation = new GetUserLocationService();
    }
//
//    /** submit Location User and save it (With Authorization / USer Must Login)
//     * @param SubmitNearbyLocationRequest $request
//     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
//     */
//    public function submitLocationUserWithoutLogin(SubmitNearbyLocationRequest $request)
//    {
//        try {
//            $data = $this->submitUserLocation->submitLocationUserWithoutLogin($request);
//            $return = $this->apiLib->singleData($data, []);
//            return response($return, Response::HTTP_OK);
//        } catch (\Exception $e) {
//            DB::rollBack();
//            $response = $this->apiLib->errorResponse($e);
//            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
//        }
//    }

    /** submit Location User and save it (With Authorization / USer Must Login)
     * @param SubmitNearbyLocationRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function submitLocationUser(SubmitNearbyLocationRequest $request)
    {
        try {
            $data = $this->submitUserLocation->submitLocation($request);
            $return = $this->apiLib->singleData($data, []);
            return response($return, Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getLocationUser(Request $request)
    {
        try {
            $data = $this->getUserLocation->getUserLocation($request);
            $return = $this->apiLib->singleData($data, []);
            return response($return, Response::HTTP_OK);
        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
