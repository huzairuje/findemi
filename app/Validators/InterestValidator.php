<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 07/11/2018
 * Time: 15:47
 */

namespace App\Validators;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InterestValidator extends Validator
{
    public function validateCreate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'required|max:255',

        ]);

        return $validator;
    }

    public function validateUpdate(Request $request)
    {
        //TODO
    }
}