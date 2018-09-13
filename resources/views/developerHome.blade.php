@extends('layouts.app1')

@section('content')
    <?php
        use App\Developer;
        use App\Allocation;
        use Illuminate\Support\Facades\Auth;

        $tasks = Developer::find(Auth::user()->id)->task()->get();
        $startedTasks = array();
        $status = array();
        foreach ($tasks as $task){
            if(date("Y-m-d") >$task->startingTime){
                $allocation =  Allocation::where(array('developer_id'=>Auth::user()->id,'task_id'=>$task->id))->first();
                if(!$allocation->acceptedTime && !$allocation->submittedTime){
                    $task->acceptedTime = 'Not started';
                }else if($allocation->acceptedTime && !$allocation->submittedTime){
                    $task->acceptedTime = 'Currently in progress';
                }else if($allocation->acceptedTime && $allocation->submittedTime){
                    $task->acceptedTime = 'Finished';
                }else{
                    $task->acceptedTime = 4;
                }
                $startedTasks[] = $task;
            }
        }
//        foreach ($startedTasks as $task){
//            $allocation =  Allocation::where(array('developer_id'=>Auth::user()->id,'task_id'=>$task->id))->first();
//            if(!$allocation->acceptedTime && !$allocation->submittedTime){
//                $status[$task->id] = 1;
//            }else if($allocation->acceptedTime && !$allocation->submittedTime){
//                $status[$task->id] = 2;
//            }else if($allocation->acceptedTime && $allocation->submittedTime){
//                $status[$task->id] = 3;
//            }else{
//                $status[$task->id] = 4;
//            }
//        }
    ?>
    <head>
        <style type="text/css">
            td {border: 1px rgb(0, 0, 0) solid; padding: 5px; cursor: pointer;}
            .selected {
                background-color: #a5160b;
                color: #52f5ff;
            }
            td {
                padding: 20px;
                text-align: center;
            }
            th {
                padding: 15px;
                text-align: center;
                background-color: #f9f8fb;
            }

        </style>
    </head>
    <body>
    <form id="developerForm" role="form" name="developerForm" action ='{{route("updateTask")}}' method="post">
        {{ csrf_field() }}
    <div class="container">
        <div >
            <div >
                <hr>
        <table id='table'  border="1">
            <tr>
                <th class="col-lg-1">Task ID</th><th class="col-lg-1">Task Name</th><th class="col-lg-1">Started Date</th><th class="col-lg-1">End Date</th><th class="col-lg-1">Status</th>
            </tr>
            @foreach($startedTasks as $task)
            <tr>
                <td>{{$task->id}}</td><td>{{$task->name}}</td><td>{{$task->startingTime}}</td><td>{{$task->endingTime}}</td><td>{{$task->acceptedTime}}</td>
                <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.min.js"></script>
                <script>
                    var taskIDValue = null;
                    $("#table tr").click(function(){
                        if(this.rowIndex > 0){
                            $(this).addClass('selected').siblings().removeClass('selected');
                            taskIDValue=$(this).find('td:first').html();
                            if('{{$task->id}}'==taskIDValue){
                                var status = '{{$task->acceptedTime}}';
                            }
                            if(status == 'Not started'){
                                document.getElementById('start').disabled = false;
                                document.getElementById('end').disabled = true;
                            }else if(status == 'Currently in progress'){
                                document.getElementById('end').disabled = false;
                                document.getElementById('start').disabled = true;
                            }else if(status == 'Finished'){
                                document.getElementById('end').disabled = true;
                                document.getElementById('start').disabled = true;
                            }
                        }
                    });
                </script>
            </tr>
            @endforeach
        </table>
                <script type="text/javascript">
                    function submitTask() {
                        document.getElementById("taskID").value = taskIDValue;
                        document.getElementById("type").value = 'submit';
                        document.getElementById("developerForm").submit();
                    }
                    function startTask(){
                        document.getElementById("taskID").value = taskIDValue;
                        document.getElementById("type").value = 'start';
                        document.getElementById("developerForm").submit();
                    }

                    {{--$("#table tr").click(function(){--}}
                        {{--if(this.rowIndex > 0){--}}
                            {{----}}
                            {{--var status = '{{$task->acceptedTime}}';--}}
                            {{--console.log(status);--}}
                            {{--if(status == 1){--}}
                                {{--document.getElementById('start').disabled = false;--}}
                                {{--document.getElementById('end').disabled = true;--}}
                            {{--}else if(status == 2){--}}
                                {{--document.getElementById('end').disabled = false;--}}
                                {{--document.getElementById('start').disabled = true;--}}
                            {{--}--}}
                        {{--}--}}
                    {{--});--}}

                </script>
                <input  id="taskID" type="hidden" style="height:30px;width:100px" name="taskID" class="ok">
                <input  id="type" type="hidden" style="height:30px;width:100px" name="type" class="ok">
            </div>
            <br>
            <div class = "form-group">
                <span style="display:inline-block; width: 720px;"></span>

                <input type="button" onclick="startTask()" style="height:40px;width:150px" id= 'start' name="start" class="btn btn-primary" value="Start" disabled/>
                <span style="display:inline-block; width: 70px;"></span>
                <input type="button" onclick="submitTask()" style="height:40px;width:150px" id= 'end' name="end" class="btn btn-primary" value="Submit" disabled/>
            </div>

</div>
</div>
        </form>
    </body>

@endsection