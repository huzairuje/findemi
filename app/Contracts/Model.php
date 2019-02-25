<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 2/4/19
 * Time: 11:00 PM
 */
namespace App\Contracts;

interface Model
{
    /**
     * Declare sql method for custom query.
     *
     * @return string
     */
    public function sql();
}
