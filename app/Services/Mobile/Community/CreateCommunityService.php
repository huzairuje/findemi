<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 03/11/2018
 * Time: 20:32
 */

namespace App\Services\Community;

use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateCommunityService
{
    protected $model;

    public function __construct()
    {
        $this->model = new Community();
    }

    public function createCommunity(Request $request)
    {
        DB::beginTransaction();

        $user = Auth::id();
        $data = $this->model;
        $data->name = $request->name;
        $data->description = $request->description;
        $data->image_banner_url = $request->image_banner_url;
        $data->is_public = $request->is_public;
        $data->base_camp_address = $request->base_camp_address;
        $data->tag = $request->tag;
        $data->lat = (float)$request->lat;
        $data->lon = (float)$request->lon;
        $data->address_from_map = $request->address_from_map;
        $data->type = "community";

        $data->created_by = (int)$user;
        $data->save();
        DB::commit();

        return $data;
    }

}