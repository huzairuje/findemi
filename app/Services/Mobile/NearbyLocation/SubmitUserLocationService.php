<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 1/18/19
 * Time: 12:20 AM
 */

namespace App\Services\Mobile\NearbyLocation;


use App\Http\Requests\NearbyLocation\SubmitNearbyLocationRequest;
use App\Library\ApiResponseLibrary;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SubmitUserLocationService
{
    protected $model;

    public function __construct()
    {
        $this->model = new User();
    }

//    /** this method just save lat lon to existing user. not been calculate by method under below
//     * @param SubmitNearbyLocationRequest $request
//     * @return ApiResponseLibrary|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
//     */
//    public function submitLocationUserWithoutLogin(SubmitNearbyLocationRequest $request)
//    {
//
//    }

    /** this method just save lat lon to existing user. not been calculate by method under below
     * @param SubmitNearbyLocationRequest $request
     * @return ApiResponseLibrary|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function submitLocation(SubmitNearbyLocationRequest $request)
    {
        DB::beginTransaction();
        $data = $this->model->findOrFail($request->user()->id);
        if ($data == null) {
            $data = new ApiResponseLibrary();
            return response($data->unauthorizedResponse(), Response::HTTP_UNAUTHORIZED);
        } else {
            $data->lat = (float)$request->lat;
            $data->lon = (float)$request->lon;
            $data->update();
            DB::commit();
            return $data;
        }
    }

    /**this method calculate user Location and what's on nearby
     * @param SubmitNearbyLocationRequest $request
     * @return $dataResult
     */
    public function calculateLocationUser(SubmitNearbyLocationRequest $request)
    {
        $dataLat = $request->input(['lat']);
        $dataLon = $request->input(['lon']);

        $dataResult = [];

        /*
         * calculate here
         */

        return $dataResult;

    }

}