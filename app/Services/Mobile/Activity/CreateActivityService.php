<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 03/11/2018
 * Time: 20:12
 */

namespace App\Services\Activity;

use App\Http\Requests\Activity\CreateActivityRequest;
use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateActivityService
{
    protected $model;

    public function __construct(Activity $activity)
    {
        $this->model = $activity;
    }

    public function createActivity(CreateActivityRequest $request)
    {
        DB::beginTransaction();

        $user = Auth::id();
        $data = $this->model;
        $data->name = $request->name;
        $data->description = $request->description;
        $data->is_one_trip = (boolean)$request->is_one_trip;
        $data->start_date = new Carbon($request->get('start_date'));
        $data->end_date = new Carbon($request->get('end_date'));
        $data->address = $request->address;
        $data->tag = $request->tag;
        $data->lat = (float)$request->lat;
        $data->lon = (float)$request->lon;
        $data->address_from_map = $request->address_from_map;
        $data->type = "activity";

        $data->created_by = (int)$user;
        $data->save();
        DB::commit();

        return $data;
    }

}
