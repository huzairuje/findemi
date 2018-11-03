<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 03/11/2018
 * Time: 21:52
 */

namespace App\Validators;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentValidator extends Validator
{
    public function validateStoreComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'title' => 'required|max:255',
            'text' => 'max:255',

        ]);

        return $validator;
    }
}