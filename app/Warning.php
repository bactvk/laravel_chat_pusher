<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warning extends Model
{
    protected $table = 'warning';

    public static function add($inputs)
    {
    	return self::insert($inputs);
    }
}
