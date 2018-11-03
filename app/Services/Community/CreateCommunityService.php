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

class CreateCommunityService
{
    protected $model;

    public function __construct()
    {
        $this->model = new Community();
    }

    public function createCommunity(Request $request)
    {
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

        return $data;
    }

}