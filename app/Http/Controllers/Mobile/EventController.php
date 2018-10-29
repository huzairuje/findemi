<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Library\ApiResponseLibrary;
use Symfony\Component\HttpFoundation\Response;

class EventController extends Controller
{
    protected $apiLib;
    protected $model;

    public function __construct()
    {
        $this->apiLib = new ApiResponseLibrary;
        $this->model = new Event();

    }

    public function index()
    {
        try {
            $data = $this->model->get();

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

    public function getEventPublic($id)
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
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'description' => 'required|max:255',
                'start_date' => 'required',
                'end_date' => 'required',
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
            $data->start_date = $request->start_date;
            $data->end_date = $request->end_date;
            $data->address = $request->address;
            $data->tag = $request->tag;
            $data->lat = $request->lat;
            $data->lon = $request->lon;
            $data->address_from_map = $request->address_from_map;

            $data->created_by = auth()->user()->id;
            $data->save();

            $response = $this->apiLib->singleData($data, []);
            return response($response, Response::HTTP_OK);

        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);
        }

    }

    public function update(Request $request, $id)
    {
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
                $data->description = $request->first_name;
                $data->start_date = $request->start_date;
                $data->end_date = $request->end_date;
                $data->address = $request->address;
                $data->tag = $request->tag;
                $data->lat = $request->lat;
                $data->lon = $request->lon;
                $data->address_from_map = $request->address_from_map;

                $data->update();

                $return = $this->apiLib->singleData($data, []);
                return response($return, Response::HTTP_OK);

            }

        } catch (\Exception $e) {
            $response = $this->apiLib->errorResponse($e);
            return response($response, Response::HTTP_BAD_GATEWAY);

        }

    }

}
