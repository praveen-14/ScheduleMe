<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = array('id', 'project_id');
    public function project(){
        return $this->belongsTo('App\Project','id');
    }
    public function file(){
        return $this->hasMany('App\File','id');
    }
    public function task(){
        return $this->belongsToMany('App\Task','dependencies','parentTask','childTask');
    }
    public function developer(){
        return $this->belongsToMany('App\Developer','allocations','task_id','developer_id');
    }
    //
    private $dependants = array();
    private $developers = array();
    private $costScore=0;
    private $duration=0;
    public function setDuration($duration){
        $this->duration=$duration;
    }
    public function getDuration(){
        return $this->duration;
    }
    public function setCostScore($cost){
        $this->costScore=$cost;
    }
    public function getCostScore(){
        return $this->costScore;
    }

    public function setDependants(Task $task){
        $this->dependants[]=$task;
    }
    public function setDevelopers($developer){
        $this->developers[]=$developer;
    }
    public function setDeveloperArray(array $developers){
        $this->developers = $developers;
    }
    public function getDevelopers(){
        return $this->developers;
    }
    public function getDependants(){
        return $this->dependants;
    }


}
