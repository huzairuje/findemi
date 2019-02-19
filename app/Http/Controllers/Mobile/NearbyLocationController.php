<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Requests\NearbyLocation\SubmitNearbyLocationRequest;
use App\Library\ApiResponseLibrary;
use App\Models\Community;
use App\Services\Mobile\NearbyLocation\GetUserLocationService;
use App\Services\Mobile\NearbyLocation\SubmitUserLocationService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
//
//    /** submit Location User and save it (With No Authorization / Anyone can access this method)
//     * @param SubmitNearbyLocationRequest $request
//     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
//     */
//    public function submitLocationUserWithoutLogin(SubmitNearbyLocationRequest $request)
//    {
//        try {
//            $data = $this->submitUserLocation->submitLocationUserWithoutLogin($request);
//            $return = $this->apiResponseLibrary->singleData($data, []);
//            return response($return, Response::HTTP_OK);
//        } catch (\Exception $e) {
//            DB::rollBack();
//            $response = $this->apiResponseLibrary->errorResponse($e);
//            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
//        }
//    }

    /** submit Location User and save it (With Authorization / User Must Login)
     * @param SubmitNearbyLocationRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function submitLocationUser(SubmitNearbyLocationRequest $request)
    {
        try {
            $data = $this->submitUserLocation->submitLocation($request);
            $return = $this->apiResponseLibrary->singleData($data, []);
            return response($return, Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiResponseLibrary->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getLocationUser(Request $request)
    {
        try {
            $data = $this->getUserLocation->getUserLocation($request);
            $return = $this->apiResponseLibrary->singleData($data, []);
            return response($return, Response::HTTP_OK);
        } catch (\Exception $e) {
            $response = $this->apiResponseLibrary->errorResponse($e);
            return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function checkNearby()
    {
       try {
           $data = $this->submitUserLocation->getNearbyUser();
//           dd($data);
           $return = $this->apiResponseLibrary->listPaginate($data, 10);
           return response($return, Response::HTTP_OK);
       } catch (\Exception $e) {
           $response = $this->apiResponseLibrary->errorResponse($e);
           return response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
       }
    }

}
