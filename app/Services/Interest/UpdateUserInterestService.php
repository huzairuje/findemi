<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 24/11/2018
 * Time: 21:24
 */

namespace App\Services\Interest;

use App\Models\Interest;
use App\Models\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class UpdateUserInterestService
{
    protected $model;
    protected $userModel;

    public function __construct()
    {
        $this->model = new Interest();
        $this->userModel = new User();
    }

    public function updateUserInterest(Request $request)
    {
        DB::beginTransaction();
        $user = $request->user()->id;
        $data = $this->model->findOrFail($request['interest_id']);
        $data->user()->attach($user);
        DB::commit();
        return $data;
    }
}