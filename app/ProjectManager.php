<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectManager extends Model
{
    public function project(){
        return $this->hasMany('App\Project','id');
    }
    public function user(){
        return $this->belongsTo('App\User','id');
    }
}
