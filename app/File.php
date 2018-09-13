<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    public function task(){
        return $this->belongsTo('App\Task','id');
    }
}
