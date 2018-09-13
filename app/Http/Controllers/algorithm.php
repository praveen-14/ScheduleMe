<?php


namespace App\Http\Controllers;
ini_set('max_execution_time', 300);
use App\Dependencies;
use App\Project;
use App\ProjectStaff;
use App\Task;
use App\Developer;
use Illuminate\Http\Request;
use DeepCopy\DeepCopy;

class algorithm extends Controller
{
    //

    private $tasks = array(array(0,0,2),array(1,1,4),array(2,1,1),array(3,2,7),array(4,2,5),array(5,3,3)); //(task id,type,duration)
    private $dependencies = array(array(0,2),array(0,1),array(1,3),array(2,4),array(3,5),array(4,5)); //(a,b) b depends on a
    private $developer = array(array(0,9,2,3,6),array(1,3,8,8,3),array(2,5,5,5,7)); //(id,analysis skill,design skl,implmnt skl,test skill)

    private $schedule = array(0,0,0,1,1,1,2,1,0,1,2,4,0,0,1,3,6,1,0,0,4,7,0,0,1,5,10,0,1,0);//(task id,start time,dev1,dev2,dev3)
    function skillFitness(){
        $schedule=$this->schedule;
        $developer=$this->developer;
        $tasks=$this->tasks;
        $skill =0;
        for($i=0;$i<sizeof($schedule);$i=$i+5){
            $tasktype=0;
            foreach ($tasks as $temptask){
                if ($schedule[$i]==$temptask[0]){
                    $tasktype=$temptask[1];
                }
            }
            for($j=0;$j<sizeof($developer);$j++){
                $skill+=$schedule[$i+$j+2]*$developer[$j][$tasktype+1];
            }

        }
//        echo $skill;
    }

    function durationFitness(){
        $schedule=$this->schedule;
    }
    function contextSwitchingFitness(){

    }
    function test(){
        echo "11";
    }
    function initialize(){
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
//        $dependency = new Dependencies();
//        $dependency->parentTask=$task1->id;
//        $dependency->childTask=$task2->id;
//        $dependency->save();
        $staff1=new ProjectStaff();
        $staff1->project_id=$project->id;
        $staff1->developer_id=15;
        $staff1->save();
        $staff2=new ProjectStaff();
        $staff2->project_id=$project->id;
        $staff2->developer_id=16;
        $staff2->save();
        $staff3=new ProjectStaff();
        $staff3->project_id=$project->id;
        $staff3->developer_id=18;
        $staff3->save();
        $staff4=new ProjectStaff();
        $staff4->project_id=$project->id;
        $staff4->developer_id=19;
        $staff4->save();
        $staff5=new ProjectStaff();
        $staff5->project_id=$project->id;
        $staff5->developer_id=20;
        $staff5->save();
//        array_push($tasks,$start);
//        array_push($tasks,$task1);
//        array_push($tasks,$task2);
//        array_push($tasks,$task3);
//        array_push($tasks,$task4);
//        array_push($tasks,$task5);
//        array_push($tasks,$task6);
//        array_push($tasks,$end);
//        array_values($tasks);


        $tasks[]=$start;
        $tasks[]=$task1;
        $tasks[]=$task2;
        $tasks[]=$task3;
        $tasks[]=$task4;
        $tasks[]=$task5;
        $tasks[]=$task6;
        $tasks[]=$end;
        return $tasks;

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
//                echo $dependant->estimatedTime;
//                echo "<br>";
                if($skill==0){
                    $skill=1;
                }
                $dependant->setDuration(($dependant->estimatedTime)/($skill/5));
//                echo $dependant->estimatedTime;
//                echo "<br>";
//                var_dump($dependant->getDevelopers());
//                echo "<br>";
                if ($dependant->startTime < ($task->startTime + $task->getDuration())) {
                    $dependant->startTime = $task->startTime + $task->getDuration();
                }
                foreach($taskQueue as $taskIterator){
                    if ($taskIterator->name==$dependant->name) {
                        $flag1=true;
                    }
//                    if (!in_array($dependant, $checkedTasks)) {
//                        $checkedTasks[] =& $dependant;
//                    }
                }
                foreach($checkedTasks as $taskIterator){
                    if ($taskIterator->name==$dependant->name) {
                        $flag2=true;
                    }
////                    if (!in_array($dependant, $checkedTasks)) {
////                        $checkedTasks[] =& $dependant;
////                    }
                }
                if (!$flag1){
                    $taskQueue[] =$dependant;
                }
                if (!$flag2){
                    $checkedTasks[] =$dependant;
                }
//                echo $dependant->name;
            }
            unset($taskQueue[0]);
            $taskQueue = array_values($taskQueue);
//            $taskQueue=array_splice($taskQueue,0,1);
        }
        foreach($checkedTasks as $tempTask){
            if($tempTask->name=='end'){
                $costScore=1/($tempTask->startTime);
//                echo $costScore;
//                echo "<br>";
            }
        }
//        echo $costScore;
//        echo "<br>";
        $start->setCostScore($costScore);


//        foreach ($checkedTasks as $tasks){
//            echo $tasks->name;
//            echo "<br>";
//            echo $tasks->startTime;
//            echo "<br>";
//            echo $tasks->estimatedTime;
//            echo "<br>";
//            var_dump($tasks->getDevelopers());
//            echo "<br>";
//        }
//        return $schedule[sizeof($schedule)-1]->startTime;
    }
    public function schedule($projectID, array $tasks)
    {
        $firstGeneration = array();
        $parentGeneration = array();
        $childGeneration = array();
        $generationCounter = 0;
        $generations = 50;
        $developers = array();
        $project = Project::find($projectID);
        foreach ($project->developer as $developer) {
            $developers[] =& $developer;
//            echo $developer->id;
        }

        for ($i = 0; $i < 100; $i++) {
//            $deepCopy = new DeepCopy();
//            $copy = $deepCopy->copy($tasks[0]);
            $firstGeneration[] = unserialize(serialize($tasks[0]));
//                echo spl_object_hash($copy);
//                echo "<br>";
        }
        foreach ($firstGeneration as $start) {
//            $checkedTasks = array();
            $taskQueue = array();
            $taskQueue[] = $start;
//            $checkedTasks[]=$start;
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
//                    if (!in_array($dependant, $checkedTasks)) {
//                        $checkedTasks[] =& $dependant;
//                    }
                    }
//                    foreach($checkedTasks as $taskIterator){
//                        if ($taskIterator->name==$dependant->name) {
//                            $flag2=true;
//                        }
//                    if (!in_array($dependant, $checkedTasks)) {
//                        $checkedTasks[] =& $dependant;
//                    }

                    if (!$flag1) {
                        $taskQueue[] = $dependant;
                    }
//                    if (!$flag2){
//                        $checkedTasks[] =$dependant;
//                    }
//                    print_r($dependant->getDevelopers());
                }
                unset($taskQueue[0]);
                $taskQueue = array_values($taskQueue);
//                $taskQueue=array_splice($taskQueue,0,1);
            }
//            echo $start->getDependants()[0]->getDependants()[0]->getDependants()[0]->getDependants()[0]->getDependants()[0]->startTime;
//            echo "<br>";
            $this->costScore($start, $developers);
//            echo $start->getDependants()[0]->getDependants()[0]->getDependants()[0]->getDependants()[0]->getDependants()[0]->startTime;
//            echo "<br>";
//            echo "<br>";
            $parentGeneration[] = unserialize(serialize($start));

//            echo $start->getCostScore();
        }
        while($generationCounter<$generations){
            $generationCounter=$generationCounter+1;
            $childGeneration[] = unserialize(serialize($this->getFittest($parentGeneration)));
            while(sizeof($childGeneration)!=sizeof($parentGeneration)){
                $taskQueue1=array();
                $taskQueue2=array();
                $checkedTasks = array();
                $parent1 = $this->tournamentSelection($parentGeneration,2);
//                var_dump($parent1[0]->getDevelopers());
//                echo "<br>";
                $parent2 = $this->tournamentSelection($parentGeneration,2);
//                var_dump($parent2[0]->getDevelopers());
//                echo "<br>";
//                echo $parent1->getDependants()[0]->getDependants()[1]->getDependants()[0]->name;
//                echo "<br>";
//                var_dump($parent1->getDependants()[0]->getDependants()[1]->getDependants()[0]->getDevelopers());
//                echo "<br>";
//                echo $parent2->getDependants()[0]->getDependants()[1]->getDependants()[0]->name;
//                echo "<br>";
//                var_dump($parent2->getDependants()[0]->getDependants()[1]->getDependants()[0]->getDevelopers());
//                echo "<br>";
//                echo "<br>";
                $child1= unserialize(serialize($parent1));
                $child2= unserialize(serialize($parent2));

//                $child1->setDeveloperArray($parent1->getDevelopers());
//                $child2->setDeveloperArray($parent2->getDevelopers());
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
//                            $task1->getDependants()[$i]->estimatedTime= ($task1->getDependants()[$i]->estimatedTime)*($task1->getDependants()[$i]->getSkill())/5;
                            $checkedTasks[] =$task1->getDependants()[$i];

                        }
//                        var_dump($task1->getDevelopers());
//                        echo "<br>";
//                        var_dump($task2->getDevelopers());
//                        echo "<br>";
//                        var_dump($task1->getDependants()[$i]->getDevelopers());
//                        echo "<br>";
//                        var_dump($task2->getDependants()[$i]->getDevelopers());
//                        echo "<br>";
//                    foreach($checkedTasks as $taskIterator){
//                        if ($taskIterator->name==$dependant->name) {
//                            $flag2=true;
//                        }
//                    if (!in_array($dependant, $checkedTasks)) {
//                        $checkedTasks[] =& $dependant;
//                    }

                        if (!$flag1) {
                            $taskQueue1[] = $task1->getDependants()[$i];
                            $taskQueue2[] = $task2->getDependants()[$i];
                        }

//                    print_r($dependant->getDevelopers());
                    }
                    unset($taskQueue1[0]);
                    unset($taskQueue2[0]);
                    $taskQueue1 = array_values($taskQueue1);
                    $taskQueue2 = array_values($taskQueue2);
//                $taskQueue=array_splice($taskQueue,0,1);
                }
//                for($i=0;$i<sizeof($tasks);$i=$i+1){
//                    if($i%2==1){
//                        $child1[$i]->setDeveloperArray($parent2[$i]->getDevelopers());
//                        $child2[$i]->setDeveloperArray($parent1[$i]->getDevelopers());
//                    }
//
//                }
//                for($i=0;$i<sizeof($tasks);$i=$i+1){
//                    var_dump($parent1[$i]->getDevelopers());
//                    echo "<br>";
//                    var_dump($parent2[$i]->getDevelopers());
//                    echo "<br>";
//                    var_dump($child1[$i]->getDevelopers());
//                    echo "<br>";
//                    var_dump($child2[$i]->getDevelopers());
//                    echo "<br>";
//
//                    echo "<br>";
//                }

//                var_dump($child1[0]->getDevelopers());
//                echo "<br>";
//                var_dump($child2[0]->getDevelopers());
//                echo "<br>";
//                echo "<br>";
//                echo $child1->getCostScore();
//                echo "<br>";
//                echo $child1->getCostScore();
//                echo "<br>";
//                echo $parent1[0]->getCostScore();
//                echo "<br>";
//                echo $parent2[0]->getCostScore();
//                echo "<br>";


//                echo $child1->getDependants()[0]->getDependants()[0]->getDependants()[0]->getDependants()[0]->getDependants()[0]->startTime;
//                echo "<br>";
                $this->costScore($child1,$developers);
                $this->costScore($child2,$developers);

//                echo $child1->getDependants()[0]->getDependants()[0]->getDependants()[0]->getDependants()[0]->getDependants()[0]->startTime;
//                echo "<br>";
//                echo "<br>";
                if($child1->getCostScore()<$child2->getCostScore()){
                    $childGeneration[] = $child2;
                }else{
                    $childGeneration[] = $child1;
                }
//                for($k=0;$k<sizeof($tasks);$k=$k+1){
//                    var_dump($checkedTasks[$k]->getDevelopers());
//                    echo "<br>";
//                    var_dump($checkedTasks[$k]->getDevelopers())
//                    echo "<br>";
//                    echo $child1->getCostScore();
//                    echo "<br>";
//                    echo $child2->getCostScore();
//                    echo "<br>";
//                    echo "<br>";
//                }

            }
            $parentGeneration=$childGeneration;
            $childGeneration = [];


//            echo 1/($this->getFittest($parentGeneration)->getCostScore());
//            echo "<br>";
//            var_dump($this->getFittest($parentGeneration)->getDependants()[0]->getDependants()[1]->getDevelopers());
//            echo "<br>";
//            echo "<br>";
        }
//        var_dump($this->getFittest($parentGeneration)->getDependants()[0]->getDependants()[1]->getDevelopers());




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

//        for ($j = 0; $j < sizeof($developers); $j++) {
//            $tasks[0]->setDevelopers(mt_rand(0, 1));
//        }
//        $this->costScore($tasks[0],$developers);



//        for($i = 0; $i < sizeof($schedule); $i++) {
//            for ($k = 0; $k < sizeof($schedule[0]); $k++) {
//                for ($j = 0; $j < sizeof($developers); $j++) {
//                    $schedule[$i][$k]->setDevelopers(mt_rand(0, 1));
//                }
////                print_r($schedule[array_search($tempSchedule,$schedule)][array_search($task,$tempSchedule)]->getDevelopers());
//            }
//            echo $this->costScore($schedule[$i],$developers);
//            echo "\n";
//        }



//            $dependency=$task->getDependencies();
//            if($dependency!= null){
//                $task->startTime=max(array_map(create_function('$task', 'return $task->endTime;'), $dependency));
//                echo $task->startTime;
//            }
//            foreach($task->getDependencies() as $dependency){
//                if($dependency!= null){
//                    $task->startTime=max(array_map(create_function('$task', 'return $task->endTime;'), $de););
//                }
//            }





}
