<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public function task(){
        return $this->hasMany('App\Task','id');
    }
    public function developer(){
        return $this->belongsToMany('App\Developer','project_staffs','project_id','developer_id');
    }
    public function projectManager(){
        return $this->belongsTo('App\ProjectManager','id');
    }
    public function costScore(&$start, array $developers){
        $costScore=0;
        $taskQueue = array();
        $taskQueue[] = $start;
        $checkedTasks = array();
        $checkedTasks[]=$start;
        while (!empty($taskQueue)) {
            $task = $taskQueue[0];
            foreach ($task->getDependants() as $dependant) {
                $flag1=false;
                $flag2=false;
                $skill = 0;
                for($i=0;$i<sizeof($developers);$i++){
                    if($dependant->type=='analysis') {
                        $skill=$skill+($developers[$i]->analysisSkill)*($dependant->getDevelopers()[$i]);
                    }elseif ($dependant->type=='design'){
                        $skill=$skill+($developers[$i]->designSkill)*($dependant->getDevelopers()[$i]);
                    }elseif ($dependant->type=='implementing'){
                        $skill=$skill+($developers[$i]->implementingSkill)*($dependant->getDevelopers()[$i]);
                    }else {
                        $skill=$skill+($developers[$i]->testingSkill)*($dependant->getDevelopers()[$i]);
                    }
                }
                if($skill==0){
                    $skill=1;
                }
                $dependant->setDuration(($dependant->estimatedTime)/($skill/5));
                if ($dependant->startTime < ($task->startTime + $task->getDuration())) {
                    $dependant->startTime = $task->startTime + $task->getDuration();
                }
                foreach($taskQueue as $taskIterator){
                    if ($taskIterator->name==$dependant->name) {
                        $flag1=true;
                    }
                }
                foreach($checkedTasks as $taskIterator){
                    if ($taskIterator->name==$dependant->name) {
                        $flag2=true;
                    }
                }
                if (!$flag1){
                    $taskQueue[] =$dependant;
                }
                if (!$flag2){
                    $checkedTasks[] =$dependant;
                }
            }
            unset($taskQueue[0]);
            $taskQueue = array_values($taskQueue);
        }
        foreach($checkedTasks as $tempTask){
            if($tempTask->name=='end'){
                $costScore=1/($tempTask->startTime);
            }
        }
        $start->setCostScore($costScore);

    }

    public function schedule($projectID,$startNode,$noOfGenerations)
    {
        $firstGeneration = array();
        $parentGeneration = array();
        $childGeneration = array();
        $generationCounter = 0;
        $generations = $noOfGenerations;
        $developers = array();
        $project = Project::find($projectID);
        foreach ($project->developer as $developer) {
            $developers[] = $developer;
        }

        for ($i = 0; $i < 100; $i++) {
            $firstGeneration[] = unserialize(serialize($startNode));
        }
        foreach ($firstGeneration as $start) {
            $taskQueue = array();
            $taskQueue[] = $start;
            for ($i = 0; $i < sizeof($developers); $i++) {
                $start->setDevelopers(mt_rand(0, 1));
            }
            while (!empty($taskQueue)) {
                $task = $taskQueue[0];
                foreach ($task->getDependants() as $dependant) {
                    $flag1 = false;
                    if (sizeof($dependant->getDevelopers()) == 0) {
                        for ($j = 0; $j < sizeof($developers); $j++) {
                            $dependant->setDevelopers(mt_rand(0, 1));
                        }
                    }
                    foreach ($taskQueue as $taskIterator) {
                        if ($taskIterator->name == $dependant->name) {
                            $flag1 = true;
                        }
                    }
                    if (!$flag1) {
                        $taskQueue[] = $dependant;
                    }
                }
                unset($taskQueue[0]);
                $taskQueue = array_values($taskQueue);
            }
            $this->costScore($start, $developers);
            $parentGeneration[] = unserialize(serialize($start));
        }
        while($generationCounter<$generations){
            $generationCounter=$generationCounter+1;
            $childGeneration[] = unserialize(serialize($this->getFittest($parentGeneration)));
            while(sizeof($childGeneration)!=sizeof($parentGeneration)){
                $taskQueue1=array();
                $taskQueue2=array();
                $checkedTasks = array();
                $parent1 = $this->tournamentSelection($parentGeneration,2);
                $parent2 = $this->tournamentSelection($parentGeneration,2);
                $child1= unserialize(serialize($parent1));
                $child2= unserialize(serialize($parent2));
                $taskQueue1[]=$child1;
                $taskQueue2[]=$child2;
                $checkedTasks[]=$child1;
                $counter=0;
                while (!empty($taskQueue1)) {
                    $task1 = $taskQueue1[0];
                    $task2 = $taskQueue2[0];
                    for($i = 0; $i < sizeof($task1->getDependants()); $i++){
                        $flag1 = false;
                        $flag2 = false;
                        foreach ($taskQueue1 as $taskIterator1) {
                            if ($taskIterator1->name == $task1->getDependants()[$i]->name) {
                                $flag1 = true;
                            }
                        }
                        foreach ($checkedTasks as $taskIterator2) {
                            if ($taskIterator2->name == $task1->getDependants()[$i]->name) {
                                $flag2 = true;
                            }
                        }
                        if (!$flag2){
                            if($counter%2==0){
                                $tempDevelopers = $task1->getDependants()[$i]->getDevelopers();
                                $task1->getDependants()[$i]->setDeveloperArray($task2->getDependants()[$i]->getDevelopers());
                                $task2->getDependants()[$i]->setDeveloperArray($tempDevelopers);
                            }
                            $counter++;
                            if(mt_rand(1,100)<5){
                                $task1Array = array();
                                $task2Array = array();
                                for ($k = 0; $k < sizeof($developers); $k++) {
                                    $task1Array[] = mt_rand(0, 1);
                                    $task2Array[] = mt_rand(0, 1);
                                }
                                $task1->getDependants()[$i]->setDeveloperArray($task1Array);
                                $task2->getDependants()[$i]->setDeveloperArray($task2Array);
                            }
                            $task1->getDependants()[$i]->startTime=0;
                            $task2->getDependants()[$i]->startTime=0;
                            $checkedTasks[] =$task1->getDependants()[$i];
                        }
                        if (!$flag1) {
                            $taskQueue1[] = $task1->getDependants()[$i];
                            $taskQueue2[] = $task2->getDependants()[$i];
                        }
                    }
                    unset($taskQueue1[0]);
                    unset($taskQueue2[0]);
                    $taskQueue1 = array_values($taskQueue1);
                    $taskQueue2 = array_values($taskQueue2);
                }
                $this->costScore($child1,$developers);
                $this->costScore($child2,$developers);
                if($child1->getCostScore()<$child2->getCostScore()){
                    $childGeneration[] = $child2;
                }else{
                    $childGeneration[] = $child1;
                }
            }
            $parentGeneration=$childGeneration;
            $childGeneration = [];
        }
        return [$this->getFittest($parentGeneration),$developers];
    }

    public function tournamentSelection(array $population,$range){
        $best=null;
        for($i=0;$i<$range;$i++){
            $individual = $population[mt_rand(0,99)];
            if ($best==null || $individual->getCostScore()>$best->getCostScore()){
                $best=$individual;
            }
        }
        return $best;
    }
    public function getFittest(array $population){
        $fittest = null;
        foreach($population as $schedule){
            if($fittest==null || $schedule->getCostScore()>$fittest->getCostScore()){
                $fittest=$schedule;
            }
        }
        return $fittest;
    }

    function multiTaskingScore(){
        $tasks = array(array(0,0,2),array(1,1,4),array(2,1,1),array(3,2,7),array(4,2,5),array(5,3,3)); //(task id,type,duration)
        $dependencies = array(array(0,2),array(0,1),array(1,3),array(2,4),array(3,5),array(4,5)); //(a,b) b depends on a
        $developer = array(array(0,9,2,3,6),array(1,3,8,8,3),array(2,5,5,5,7)); //(id,analysis skill,design skl,implmnt skl,test skill)

        $schedule = array(0,0,0,1,1,1,2,1,0,1,2,4,0,0,1,3,6,1,0,0,4,7,0,0,1,5,10,0,1,0);//(task id,start time,dev1,dev2,dev3)


        $skill =0;
        for($i=0;$i<sizeof($schedule);$i=$i+5){
            $tasktype=0;
            foreach ($tasks as $temptask){
                if ($schedule[$i]==$temptask[0]){
                    $tasktype=$temptask[1];
                    $dependencies[] = $dependencies[1];
                }
            }
            for($j=0;$j<sizeof($developer);$j++){
                $skill+=$schedule[$i+$j+2]*$developer[$j][$tasktype+1];
            }

        }
        $tasks=array();
        $project =new Project();
        $project->name='testProject';
        $project->project_manager_id=21;
        $project->save();
        $start = new Task();
        $start->name='start';
        $start->type='analysis';
        $start->project_id=$project->id;
        $start->startTime=0;
        $start->estimatedTime=0;
        $task1= new Task();
        $task1->name='1';
        $task1->type='analysis';
        $task1->project_id=$project->id;
        $task1->estimatedTime=20;
        $task1->startTime=0;
        $task1->acceptedTime=0;
        $task1->submittedTime=0;
        $task1->save();
        $start->setDependants($task1);
        $task2 = new Task();
        $task2->name='2';
        $task2->type='design';
        $task2->project_id=$project->id;
        $task2->estimatedTime=25;
        $task2->startTime=0;
        $task2->acceptedTime=0;
        $task2->submittedTime=0;
        $task1->setDependants($task2);
        $task2->save();
        $task3 = new Task();
        $task3->name='3';
        $task3->type='design';
        $task3->project_id=$project->id;
        $task3->estimatedTime=35;
        $task3->startTime=0;
        $task3->acceptedTime=0;
        $task3->submittedTime=0;
        $task1->setDependants($task3);
        $task3->save();
        $task4 = new Task();
        $task4->name='4';
        $task4->type='implementing';
        $task4->project_id=$project->id;
        $task4->estimatedTime=30;
        $task4->startTime=0;
        $task4->acceptedTime=0;
        $task4->submittedTime=0;
        $task2->setDependants($task4);
        $task4->save();
        $task5 = new Task();
        $task5->name='5';
        $task5->type='implementing';
        $task5->project_id=$project->id;
        $task5->estimatedTime=15;
        $task5->startTime=0;
        $task5->acceptedTime=0;
        $task5->submittedTime=0;
        $task3->setDependants($task5);
        $task5->save();
        $task6 = new Task();
        $task6->name='6';
        $task6->type='testing';
        $task6->project_id=$project->id;
        $task6->estimatedTime=20;
        $task6->startTime=0;
        $task6->acceptedTime=0;
        $task6->submittedTime=0;
        $task4->setDependants($task6);
        $task5->setDependants($task6);
        $task6->save();
        $end = new Task();
        $end->name='end';
        $end->type='testing';
        $end->project_id=$project->id;
        $end->startTime=0;
        $end->estimatedTime=0;
        $task6->setDependants($end);
        $checkedTasks = array();
        while (!empty($taskQueue)) {
            $task = $taskQueue[0];
            foreach ($task->getDependants() as $dependant) {
                $flag1=false;
                $flag2=false;
                $skill = 0;
                for($i=0;$i<sizeof($developer);$i++){
                    if($dependant->type=='analysis') {
                        $skill=$skill+($developer[$i]->analysisSkill)*($dependant->getDevelopers()[$i]);
                    }elseif ($dependant->type=='design'){
                        $skill=$skill+($developer[$i]->designSkill)*($dependant->getDevelopers()[$i]);
                    }elseif ($dependant->type=='implementing'){
                        $skill=$skill+($developer[$i]->implementingSkill)*($dependant->getDevelopers()[$i]);
                    }else {
                        $skill=$skill+($developer[$i]->testingSkill)*($dependant->getDevelopers()[$i]);
                    }
                }
                if($skill==0){
                    $skill=1;
                }
                $dependant->setDuration(($dependant->estimatedTime)/($skill/5));
                if ($dependant->startTime < ($task->startTime + $task->getDuration())) {
                    $dependant->startTime = $task->startTime + $task->getDuration();
                }
                foreach($taskQueue as $taskIterator){
                    if ($taskIterator->name==$dependant->name) {
                        $flag1=true;
                    }
                }
                foreach($checkedTasks as $taskIterator){
                    if ($taskIterator->name==$dependant->name) {
                        $flag2=true;
                    }
                }
                if (!$flag1){
                    $taskQueue[] =$dependant;
                }
                if (!$flag2){
                    $checkedTasks[] =$dependant;
                }
            }
            unset($taskQueue[0]);
            $taskQueue = array_values($taskQueue);
        }
//        $staff1=new ProjectStaff();
//        $staff1->project_id=$project->id;
//        $staff1->developer_id=15;
//        $staff1->save();
//        $staff2=new ProjectStaff();
//        $staff2->project_id=$project->id;
//        $staff2->developer_id=16;
//        $staff2->save();
//        $staff3=new ProjectStaff();
//        $staff3->project_id=$project->id;
//        $staff3->developer_id=18;
//        $staff3->save();
//        $staff4=new ProjectStaff();
//        $staff4->project_id=$project->id;
//        $staff4->developer_id=19;
//        $staff4->save();
//        $staff5=new ProjectStaff();
//        $staff5->project_id=$project->id;
//        $staff5->developer_id=20;
//        $staff5->save();
//        $tasks[]=$start;
//        $tasks[]=$task1;
//        $tasks[]=$task2;
//        $tasks[]=$task3;
//        $tasks[]=$task4;
//        $tasks[]=$task5;
//        $tasks[]=$task6;
//        $tasks[]=$end;
//        return $tasks;
    }





}
