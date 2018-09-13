<?php
/**
 * Created by PhpStorm.
 * User: Praveen
 * Date: 4/30/2017
 * Time: 10:25 PM
 */

namespace App\Http\Controllers;

use App\Allocation;
use App\Dependencies;
use App\Developer;
use App\ProjectStaff;
use App\Task;

use Illuminate\Http\Request;
use App\Project;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;


class ManagerController extends  Controller
{
    public function createProject(){
        return view('createProject');
    }
    public function showSchedule(Request $request){
        $this->validate($request,['projectName'=>'required','projectID'=>'required','startDate'=>'required']);
        $projectID=$request['projectID'];
        $productivity = $request['productivity'];
        $generations = null;
        $start = new Task();
        $start->name = 'start';
        $end = new Task();
        $end->name = 'end';
        $taskArray = array();
        $tasks = Task::where('project_id',$projectID)->get();
        foreach ($tasks as $task){
            $taskArray[$task->id] = $task;
        }
//        dd($tasks);
        foreach($taskArray as $task){
//            dd($task);
            $dependencies = Dependencies::where('parentTask',$task->id)->get();
            foreach($dependencies as $dependency){
                $taskArray[$task->id]->setDependants($taskArray[$dependency->childTask]);
//                foreach ($tasks as $taskIterator){
//                    if($taskIterator->id==$dependency->childTask){
//
//                        $task->setDependants($task$taskIterator);
//                    }
//                }
            }
            if(Dependencies::where('childTask',$task->id)->first()==null){
                $start->setDependants($taskArray[$task->id]);
            }
            if(Dependencies::where('parentTask',$task->id)->first()==null){
                $taskArray[$task->id]->setDependants($end);
            }
        }
//        dd($tasks[1]);
//        dd($start);
        if($productivity =='high'){
            $generations = 30;
        }
        elseif ($productivity =='medium'){
            $generations = 15;
        }
        else{
            $generations = 5;
        }
        $project = Project::find($projectID);
        $scheduleDetails = $project->schedule($projectID,$start,$generations);
        $scheduleStart = $scheduleDetails[0];
        $developers = $scheduleDetails[1];
//        dd($developers);
        $taskQueue = array();
        $taskQueue[] = $scheduleStart;
        $checkedTasks = array();
        $checkedTasks[]=$scheduleStart;
        while (!empty($taskQueue)) {
            $task = $taskQueue[0];
            foreach ($task->getDependants() as $dependant) {
                $flag1=false;
                $flag2=false;
                foreach($taskQueue as $taskIterator){
                    if ($taskIterator->id==$dependant->id) {
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
            }
            unset($taskQueue[0]);
            $taskQueue = array_values($taskQueue);
//            $taskQueue=array_splice($taskQueue,0,1);
        }
        $returnArray = array();
        foreach ($checkedTasks as $task){
            if($task->name!='end' && $task->name!='start'){
                Allocation::where('task_id',$task->id)->delete();
                $startingDate = date("Y-m-d",strtotime($request['startDate'].'+'.round($task->startTime).'days'));
                $endingDate = date("Y-m-d",strtotime($request['startDate'].'+'.(round($task->startTime)+round($task->getDuration())).'days'));
                $task->startingTime = $startingDate;
                $task->endingTime = $endingDate;
                $task->save();
                $assignedDevelopers = "";
                $tempArray = array();
                $tempArray['id']= $task->id;
                $tempArray['startTime'] = round($task->startTime );
                $tempArray['duration'] = round($task->getDuration());
                for($i=0;$i<sizeof($developers);$i++){
                    if($task->getDevelopers()[$i]==1){
                        $assignedDevelopers = $assignedDevelopers.$developers[$i]->user->name.'/';
                        $allocation = new Allocation();
                        $allocation->task_id=$task->id;
                        $allocation->developer_id=$developers[$i]->id;
                        $allocation->save();
                    }
                }
                if($assignedDevelopers!=''){
                    $assignedDevelopers=substr($assignedDevelopers, 0, -1);
                }
                $taskDetails = ($task->name)." | developers - ".$assignedDevelopers;
                $tempArray['details'] = $taskDetails;
                $returnArray[] = $tempArray;
            }
        }
//        dd($checkedTasks);
        return view('schedule')->with('projectID',$projectID)->with('schedule',$returnArray);
    }
    public function showUpdateProject($projectID){
        $project = Project::find($projectID);
        $tasks = Task::where("project_id",$projectID)->get();
        $staff = array();
        $table = array();
        $projectStaff = Project::find($projectID)->developer()->get();
        foreach ($tasks as $task){
            $precedents = "";
            $data =array();
            $dependencies = Dependencies::where('childTask',$task->id)->get();
            foreach($dependencies as $dependency){
                $precedents =  $precedents."/".$dependency->parentTask;
            }
            if($precedents!=""){
                $precedents = substr($precedents,1);
            }
            $data[] = $task->id;
            $data[] = $task->name;
            $data[] = $task->estimatedTime;
            $data[] = $precedents;
            $data[] = $task->type;
            $table[] = $data;
        }
        foreach ($projectStaff as $developer){
            $staff[]=$developer->id;
        }
        return view('updateProject')->with('projectID',$projectID)->with('projectName',$project->name)->with('staff',$staff)->with('tableData',$table);
    }
    public function doCreateProject(Request $request){
        $this->validate($request,['projectName'=>'required','developers'=>'required']);
        $projectName = $request['projectName'];
        $developers = $request['developers'];
        $newProject = new Project();
        $table = $request['rows'];
//        var_dump($table);
        $tasks = array();
        $newProject->name= $projectName;
        $newProject->project_manager_id=Auth::user()->id;
        $newProject->save();
        foreach($developers as $developer){
            $staff =new ProjectStaff();
            $staff->project_id = $newProject->id;
            $staff->developer_id = $developer;
            $staff->save();
        }
        foreach($table as $row){
            $task = new Task();
            $data = explode("|", $row);
            $taskNumber = $data[0];
            $taskName = $data[1];
            $taskEstimation = $data[2];
            $taskType = $data[4];
            $task->name = $taskName;
            $task->estimatedTime = $taskEstimation;
            $task->project_id = $newProject->id;
            $task->type=$taskType;
            $task->save();
            $tasks[$taskNumber] = $task;
        }
        foreach($table as $row){
            $data = explode("|", $row);
            $taskNumber = $data[0];
            if($data[3]!=""){
                $precedents = explode("/",$data[3]);
                foreach($precedents as $precedent){
                    $dependency = new Dependencies();
                    $dependency->parentTask = $tasks[$precedent]->id;
                    $dependency->childTask = $tasks[$taskNumber]->id;
                    $dependency->save();
                }
            }
        }
        return view('projectManagerHome');
    }
    public function updateProject(Request $request){
        $this->validate($request,['projectName'=>'required','developers'=>'required']);
        $projectName = $request['projectName'];
        $developers = $request['developers'];
        $prevTasks = Task::where('project_id',$request['projectID'])->get();
        foreach($prevTasks as $task){
            Dependencies::where('parentTask',$task->id)->delete();
        }
        $newProject = Project::firstOrNew(array('id' => $request['projectID']));
        $table = $request['rows'];
        $tasks = array();
        $newProject->name= $projectName;
        $newProject->project_manager_id=Auth::user()->id;
        $newProject->save();
        ProjectStaff::where('project_id',$request['projectID'])->delete();
        foreach($developers as $developer){
            $staff = new ProjectStaff();
            $staff->project_id = $newProject->id;
            $staff->developer_id = $developer;
            $staff->save();
        }
        foreach($table as $row){

            $data = explode("|", $row);
            $taskNumber = $data[0];
            $taskName = $data[1];
            $taskEstimation = $data[2];
            $taskType = $data[4];
            $task = Task::firstOrNew(array('id' => $taskNumber,'project_id'=>$request['projectID']));
//            $task = Task::where('id', $taskNumber)->where('project_id',$request['projectID'])->first();
            if(!$task){
                $newTask  = new Task();
                $newTask->name = $taskName;
                $newTask->estimatedTime = $taskEstimation;
                $newTask->project_id = $newProject->id;
                $newTask->type=$taskType;
                $newTask->save();
            }
            $task->name = $taskName;
            $task->estimatedTime = $taskEstimation;
            $task->project_id = $newProject->id;
            $task->type=$taskType;
            $task->save();
            $tasks[$taskNumber] = $task;
        }
        $allTasks = Task::where('project_id',$request['projectID'])->get();
        foreach($allTasks as $tempTask){
            if(!in_array($tempTask,$tasks)){
                Task::where('id',$tempTask->id)->delete();
            }
        }
        foreach($table as $row){
            $data = explode("|", $row);
            $taskNumber = $data[0];
            if($data[3]!=""){
                $precedents = explode("/",$data[3]);
                foreach($precedents as $precedent){
                    $dependency = new Dependencies();
                    $dependency->parentTask = $tasks[$precedent]->id;
                    $dependency->childTask = $tasks[$taskNumber]->id;
                    $dependency->save();
                }
            }
        }
        return view('projectManagerHome');
    }
    public function removeProject(Request $request){
        $projectID = $request["projectID"];
        Project::destroy($projectID);
        ProjectStaff::where('project_id',$projectID)->delete();
        $tasks = Task::where('project_id',$projectID)->get();
        foreach($tasks as $task){
            Dependencies::where('parentTask',$task->id)->delete();
        }
        Task::where('project_id',$projectID)->delete();
        return view('projectManagerHome');
    }



}