@extends('layouts.app')

@section('content')
    <div class="box-header with-border">
        <h3 class="box-title">Update Task Details </h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form role="form">
        <div class="box-body">
            <div class="form-group">
                <label for="taskID">Task ID</label>
                <input type="text" class="form-control" id="taskID" readonly>
                <script>
                    document.getElementById("taskID").value = sessionStorage.getItem('taskID');
                </script>
            </div>
            <div class="form-group">
                <label for="taskName">Task Name</label>
                <input type="text" class="form-control" id="taskName" placeholder="Enter Task Name">
                <script>
                    document.getElementById("taskName").value = sessionStorage.getItem('taskName');
                </script>
            </div>
            <div class="form-group">
                <label for="estimatedTime">Estimated Time</label>
                <input type="text" class="form-control" id="estimation" placeholder="Enter Time Estimation in hours">
                <script>
                    document.getElementById("estimation").value = sessionStorage.getItem('estimation');
                </script>
            </div>
            <div id="container" class="form-group">
                <label for="precedents">Check Precedent Tasks</label>
                <p class="help-block"> Before starting a task, all of its precedent tasks should be completed. </p>
                <script>
                    var tasks = JSON.parse(sessionStorage.tasks);
                    var precedents = sessionStorage.getItem('precedents').split("/");
                    for (i=0;i<tasks.length/2;i++){
                        var checkBox = document.createElement("input");
                        var text = document.createElement("Label");
                        text.innerHTML = tasks[2*i]+" - "+tasks[2*i+1];
                        checkBox.setAttribute("type", "checkbox");
                        checkBox.setAttribute("value", tasks[2*i]);
                        checkBox.setAttribute("id", i);
                        document.getElementById('container').appendChild(checkBox);
                        document.getElementById('container').appendChild(text);
                        if(precedents.indexOf(tasks[2*i]) != -1)
                        {
                            checkBox.checked=true;
                        }
                    }
                    function updateTask(){
                        var precedents = [];
                        for (i=0;i<tasks.length/2;i++){
                            if(document.getElementById(i).checked){
                                precedents.push(document.getElementById(i).value);
                            }
                        }
                        sessionStorage.setItem('precedents',precedents);
                        sessionStorage.setItem('taskID',document.getElementById('taskID').value);
                        sessionStorage.setItem('taskName',document.getElementById('taskName').value);
                        sessionStorage.setItem('estimation',document.getElementById('estimation').value);
                        sessionStorage.setItem('precedents',$(this).attr('data-precedents'));
                        $.getscript("createProject",function(){
                            updateTaskTable();
                        });
                        window.location.href ='/createTask';
                    }
                </script>
                <div class="form-group">
                <input type="button" class="btn btn-primary" onClick="updateTask()"value="Update Task"/>
                    </div>
                </div>
        </div>
    </form>

@endsection