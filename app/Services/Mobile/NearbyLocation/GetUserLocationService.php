<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 1/18/19
 * Time: 12:47 AM
 */

namespace App\Services\Mobile\NearbyLocation;


use App\Library\ApiResponseLibrary;
use App\Models\User;
use App\Transformers\NearbyLocation\UserLocationTransformer;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GetUserLocationService
{
    protected $model;
    protected $userLocationTransformer;

    public function __construct()
    {
        $this->model = new User();
        $this->userLocationTransformer = new UserLocationTransformer();
    }

    public function getUserLocation(Request $request)
    {
        $data = $request->user()->id;
        if($data == null) {
            $data = new ApiResponseLibrary();
            return response($data->unauthorizedResponse(), Response::HTTP_UNAUTHORIZED);
        } else {
            $encodeData = $this->userLocationTransformer->transformWithLocation($request->user());
            return $encodeData;
        }
    }

}