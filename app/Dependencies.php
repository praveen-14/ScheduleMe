<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dependencies extends Model
{
    protected $fillable = array('parentTask', 'childTask');
}
