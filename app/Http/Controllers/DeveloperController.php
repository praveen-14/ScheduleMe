<?php
/**
 * Created by PhpStorm.
 * User: Praveen
 * Date: 5/18/2017
 * Time: 10:38 PM
 */

namespace App\Http\Controllers;


use App\Developer;
use App\Task;
use Illuminate\Http\Request;
use App\Allocation;
use Illuminate\Support\Facades\Auth;

class DeveloperController extends Controller
{
    public function updateTask(Request $request){
        $taskID = $request['taskID'];
        $task = Task::find($taskID);
        $developer = Developer::find(Auth::user()->id);
        $allocation =  Allocation::firstOrNew(array('developer_id'=>Auth::user()->id,'task_id'=>$taskID));
        if($request['type']=='start'){
            $allocation->acceptedTime = date("Y-m-d");
        }else{
            $allocation->submittedTime = date("Y-m-d");
            $acceptedTime = $allocation->acceptedTime;
            $start = $task->startingTime;
            $end = $task->endingTime;
            $allocatedDays = date_diff(date_create($start),date_create($end))->format("%a");
            $elapsedDays = date_diff(date_create($acceptedTime),date_create(date("Y-m-d")))->format("%a");
            if($elapsedDays==0){
                $elapsedDays = 1;
            }
            if($allocatedDays==0){
                $allocatedDays = 1;
            }
            $ratio = $elapsedDays/$allocatedDays;
            if($task->type=='analysis'){
                if($ratio<=1){
                    $developer->analysisSkill = 10-(10-$developer->analysisSkill)*$ratio;
                }
                if($ratio>1){
                    $developer->analysisSkill = $developer->analysisSkill/$ratio;
                }
            }
            else if($task->type=='design'){
                if($ratio<=1){
                    $developer->designSkill = 10-(10-$developer->designSkill)*$ratio;
                }
                if($ratio>1){
                    $developer->designSkill = $developer->designSkill/$ratio;
                }
            }
            else if($task->type=='implementation'){
                if($ratio<=1){
                    $developer->implementingSkill = 10-(10-$developer->implementingSkill)*$ratio;
                }
                if($ratio>1){
                    $developer->implementingSkill = $developer->implementingSkill/$ratio;
                }
            }else{
                if($ratio<=1){
                    $developer->testingSkill = 10-(10-$developer->testingSkill)*$ratio;
                }
                if($ratio>1){
                    $developer->testingSkill = $developer->testingSkill/$ratio;
                }
            }
            $developer->save();
        }
        $newAllocation = new Allocation();
        $newAllocation->developer_id= $allocation->developer_id;
        $newAllocation->task_id= $allocation->task_id;
        $newAllocation->submittedTime= $allocation->submittedTime;
        $newAllocation->acceptedTime= $allocation->acceptedTime;
        Allocation::where(array('developer_id'=>Auth::user()->id,'task_id'=>$taskID))->delete();
        $newAllocation->save();
        return redirect('/developerHome');
    }
}