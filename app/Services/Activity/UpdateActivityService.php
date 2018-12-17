<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 03/11/2018
 * Time: 20:18
 */

namespace App\Services\Activity;

use App\Models\Activity;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UpdateActivityService
{
    protected $model;

    public function __construct()
    {
        $this->model = new Activity();
    }

    public function updateActivity(Request $request, $id)
    {
        DB::beginTransaction();
        $data = $this->model->find($id);
        $data->name = $request->name;
        $data->description = $request->description;
        $data->start_date = new Carbon($request->get('start_date'));
        $data->end_date = new Carbon($request->get('end_date'));
        $data->address = $request->address;
        $data->tag = $request->tag;
        $data->lat = $request->lat;
        $data->lon = $request->lon;
        $data->address_from_map = $request->address_from_map;
        $data->update();
        DB::commit();

        return $data;
    }

}
