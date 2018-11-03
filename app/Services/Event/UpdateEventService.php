<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 03/11/2018
 * Time: 21:23
 */

namespace App\Services\Event;

use App\Models\Event;
use Illuminate\Http\Request;

class UpdateEventService
{
    protected $model;

    public function __construct()
    {
        $this->model = new Event();
    }

    public function updateEvent(Request $request, $id)
    {
        $data = $this->model->find($id);
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

        return $data;
    }

}