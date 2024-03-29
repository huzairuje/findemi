<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 07/11/2018
 * Time: 19:21
 */

namespace App\Services\Interest;

use App\Models\Interest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreateUserInterestService
{
    protected $model;
    protected $userModel;

    public function __construct()
    {
        $this->model = new Interest();
        $this->userModel = new User();
    }

    public function createUserInterest(Request $request)
    {
        DB::beginTransaction();
        $user = $request->user()->id;
        $data = $this->model->findOrFail($request['interest_id']);
        $data->user()->attach($user);
        DB::commit();
        return $data;
    }
}