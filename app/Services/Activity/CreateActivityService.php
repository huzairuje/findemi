<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 03/11/2018
 * Time: 20:12
 */

namespace App\Services\Activity;

use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateActivityService
{
    protected $model;

    public function __construct()
    {
        $this->model = new Activity();
    }

    public function createActivity(Request $request)
    {
        DB::beginTransaction();

        $user = Auth::id();
        $data = $this->model;
        $data->name = $request->name;
        $data->description = $request->description;
        $data->is_one_trip = $request->is_one_trip;
        $data->start_date = new Carbon($request->get('start_date'));
        $data->end_date = new Carbon($request->get('end_date'));
        $data->address = $request->address;
        $data->tag = $request->tag;
        $data->lat = $request->lat;
        $data->lon = $request->lon;
        $data->address_from_map = $request->address_from_map;

        $data->created_by = $user;
        $data->save();
        DB::commit();

        return $data;
    }

}