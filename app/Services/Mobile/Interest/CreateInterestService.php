<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 07/11/2018
 * Time: 15:34
 */

namespace App\Services\Interest;

use App\Models\Interest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreateInterestService
{
    protected $model;

    public function __construct()
    {
        $this->model = new Interest();
    }

    public function createInterest(Request $request)
    {
        DB::beginTransaction();
        $data = $this->model;
        $data->name = $request->name;
        $data->description = $request->description;
        $data->save();
        DB::commit();
        return $data;
    }
}