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
use App\Models\Activity;
use App\Models\Community;
use App\Models\Event;
use App\Models\User;
use App\Transformers\NearbyLocation\AllModuleNearbyLocationTransformer;
use App\Transformers\NearbyLocation\UserLocationTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SubmitUserLocationService
{
    protected $model;
    protected $communityModel;
    protected $allModuleNearbyLocationTransformer;
    protected $userLocationTransformer;

    public function __construct()
    {
        $this->model = new User();
        $this->communityModel = new Community();
        $this->allModuleNearbyLocationTransformer = new AllModuleNearbyLocationTransformer();
        $this->userLocationTransformer = new UserLocationTransformer();
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

            $encodeData = $this->userLocationTransformer->transformWithIdAndLocation($data);
            return $encodeData;
        }
    }

    /**this method calculate user Location and what's on nearby
     * @param SubmitNearbyLocationRequest $request
     * @return $dataResult
     */
//    public function getNearbyUser(SubmitNearbyLocationRequest $request)
    public function getNearbyUser()
    {
//        $dataLat = (float)$request->input(['lat']);
//        $dataLon = (float)$request->input(['lon']);
//        $dataRadius = (float)$request->input(['radius']);
//        $dataUnit = $request->input(['unit']);
//
//        $dataUnitMeter = (float)['3961'];
//        $dataUnitKiloMeter = (float)['6372.797'];

//        if ($dataUnit === "M") {
//            $dataUnit =  $dataUnitMeter;
//        } elseif($dataUnit === "KM") {
//            $dataUnit =  $dataUnitKiloMeter;
//        }

        $dataLat = (float)['33.72775'];
        $dataLon = (float)['150.8261'];
        $dataRadius = (float)['780000'];


        $communityModel = new Community();
        $communityModel->select(
                DB::raw('(3961 * acos( cos( radians(-6.919188) ) * cos( radians( lat ) ) * cos( radians( lon )
                 - radians(107.614911) ) + sin( radians(-6.919188) ) * sin( radians( lat ) ) ) ) as distance')
            )
            ->where('distance', '<', 100)
            ->orderBy('distance', 'asc');

        $eventModel = new Event();
        $eventModel
            ->select(
                DB::raw('(3961 * acos( cos( radians(-6.919188) ) * cos( radians( lat ) ) * cos( radians( lon )
                 - radians(107.614911) ) + sin( radians(-6.919188) ) * sin( radians( lat ) ) ) ) as distance')
            )
            ->where('distance', '<', 100)
            ->orderBy('distance', 'asc');

        $activityModel = new Activity();
        $activityModel
            ->select(
                DB::raw('(3961 * acos( cos( radians(-6.919188) ) * cos( radians( lat ) ) * cos( radians( lon )
                 - radians(107.614911) ) + sin( radians(-6.919188) ) * sin( radians( lat ) ) ) ) as distance')
            )
            ->where('distance', '<', 100)
            ->orderBy('distance', 'asc');


        return $communityModel;
//        return $communityModel
//            ->union($eventModel)
//            ->union($activityModel)
//            ->get();

    }

}
