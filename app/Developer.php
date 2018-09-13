<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Developer extends Model
{
    public function project(){
        return $this->belongsToMany('App\Project','project_staffs','developer_id','project_id');
    }
    public function task(){
        return $this->belongsToMany('App\Task','allocations','developer_id','task_id');
    }
    public function user(){
        return $this->belongsTo('App\User','id');
    }
}
