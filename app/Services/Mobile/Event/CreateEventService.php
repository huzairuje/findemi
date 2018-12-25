<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 03/11/2018
 * Time: 20:54
 */

namespace App\Services\Event;

use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateEventService
{
    protected $model;
    protected $userModel;

    public function __construct()
    {
        $this->model = new Event();
        $this->userModel = new User();

    }

    public function createEvent(Request $request)
    {
        DB::beginTransaction();

        $user = Auth::id();
        $data = $this->model;
        $data->name = $request->name;
        $data->description = $request->description;
        $data->is_paid = $request->is_paid;
        $data->start_date = new Carbon($request->get('start_date'));
        $data->end_date = new Carbon($request->get('end_date'));
        $data->image_banner_url = $request->image_banner_url;
        $data->tag = $request->tag;
        $data->lat = $request->lat;
        $data->lon = $request->lon;
        $data->address_from_map = $request->address_from_map;

        $data->created_by = (int)$user;
        $data->save();
        DB::commit();

        return $data;
    }

}
