<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 07/11/2018
 * Time: 21:23
 */

namespace App\Services\Interest;

use App\Models\Interest;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreateEventInterestService
{
    protected $model;
    protected $eventModel;

    public function __construct()
    {
        $this->model = new Interest();
        $this->eventModel = new Event();
    }

    public function createEventInterest(Request $request)
    {
        DB::beginTransaction();
        $event = $this->eventModel->findOrFail($request['event_id']);
        $data = $this->model->findOrFail($request['interest_id']);
        $data->event()->attach($event);
        DB::commit();
        return $data;
    }
}