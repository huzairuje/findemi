<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Library\ApiResponseLibrary;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class CommunityController extends Controller
{
    protected $apiLib;
    protected $model;

    public function __construct()
    {
        $this->apiLib = new ApiResponseLibrary;
        $this->model = new Community();

    }

    public function index()
    {
        try {
            $activityAll = $this->model->get();
            $response = $this->apiLib->singleData($activityAll, []);
            return response($response, Response::HTTP_OK);

        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);
        }
    }

    public function getActivityPublic($id)
    {
        try {
            $data = $this->model->find($id);

            if (is_null($data)) {
                $response = $this->apiLib->notFoundResponse();
                return response($response, Response::HTTP_NOT_FOUND);

            } else {
                $response = $this->apiLib->singleData($data, []);
                return response($response, Response::HTTP_OK);

            }

        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);

        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'description' => 'required|max:255',
                'image_banner_url' => 'required',
                'base_camp_address' => 'required',
                'address' => 'required|max:255',
                'tag' => 'required|max:255',
                'lat' => 'required',
                'lon' => 'required',
                'address_from_map' => 'required',
            ]);

            if ($validator->fails()) {
                $response = $this->apiLib->validationFailResponse($validator->errors());
                return response($response, Response::HTTP_BAD_REQUEST);
            }
            $data = $this->model;
            $data->name = $request->name;
            $data->description = $request->description;
            $data->address_from_map = $request->address_from_map;
            $data->tag = $request->tag;
            $data->lat = $request->lat;
            $data->lon = $request->lon;
            $data->address_from_map = $request->address_from_map;

            $data->created_by = auth()->user()->id;
            $data->save();
            DB::commit();

            $response = $this->apiLib->singleData($data, []);
            return response($response, Response::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollback();
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);
        }

    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = $this->model->find($id);

            if (is_null($data)){
                $response = $this->apiLib->notFoundResponse();
                return response($response, Response::HTTP_NOT_FOUND);

            } else {
                $validator = Validator::make($request->all(), [
                    'name' => 'max:255',
                    'description' => 'max:255',
                    'address' => 'max:255',
                    'tag' => 'max:255',

                ]);

                if ($validator->fails()) {
                    $response = $this->apiLib->validationFailResponse($validator->errors());
                    return response($response, Response::HTTP_BAD_REQUEST);

                }

                $data->name = $request->name;
                $data->description = $request->description;
                $data->address_from_map = $request->address_from_map;
                $data->is_public = $request->is_public;
                $data->tag = $request->tag;
                $data->lat = $request->lat;
                $data->lon = $request->lon;
                $data->address_from_map = $request->address_from_map;

                $data->update();
                DB::commit();

                $return = $this->apiLib->singleData($data, []);
                return response($return, Response::HTTP_OK);

            }

        } catch (\Exception $e) {
            DB::rollBack();
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);

        }

    }
}
