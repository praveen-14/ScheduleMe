@extends('layouts.app')
<?php
    use App\Developer;
    use App\Task;
    use Illuminate\Support\Facades\DB;
        $developers = Developer::all();
        $newTask = new Task();
?>
@section('content')
    <head>
        <style>
            td {border: 1px rgb(0, 0, 0) solid; padding: 5px; cursor: pointer;}
            .selected {
                background-color: #a5160b;
                color: #52f5ff;
            }
            th, td {
                padding: 15px;
                text-align: left;
            }
        </style>
    </head>
    <body>
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div id="main" class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Update Project Details</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form id="updateForm"  role="form"  action = '{{route("updateProject")}}'  method = "post">
            {{ csrf_field() }}
            <div id = "inputs">
            </div>
            <div class="box-body" >
                <div class="form-group">
                    <label for="projectID">Project ID</label>
                    <input type="text" class="form-control" id="projectID" name="projectID"  readonly value=<?php echo $projectID;?>>
                </div>
                <div class="form-group">
                    <label for="projectName">Project Name</label>
                    <input type="text" class="form-control" id="projectName" name="projectName" placeholder="Enter Project Name" value=<?php echo $projectName;?> >
                </div>
                <div class="form-group">
                    <label for="developers">Available Developers</label>
                    @foreach ($developers as $developer)
                        <br/><input id={{"dev".$developer->id}} type='checkbox' name='developers[]' value={{$developer->id}} <?php if(in_array($developer->id,$staff)){echo 'checked';};?> />{{$developer->user->name}}<br>
                    @endforeach
                </div>
                <div class="form-group">
                    <label >Task Details</label>
                    <p class="help-block"> Task IDs may change when tasks are removed. </p>
                </div>

            </div>
            <div class="form-group">
                <table id="table"  border="1">
                    <tr>
                        <th class="unselectable" width="20%">Task ID </th>
                        <th class="unselectable" width="20%">Task Name  </th>
                        <th class="unselectable" width="20%">Estimated Time </th>
                        <th class="unselectable" width="20%">Precedent Tasks </th>
                        <th class="unselectable" width="20%">Task Type </th>
                    </tr>
                    <tr style="display:none;">
                        <td class="unselectable" width="20%"> 0</td>
                        <td class="unselectable" width="20%">start </td>
                        <td class="unselectable" width="20%">0</td>
                        <td class="unselectable" width="20%"> &nbsp;</td>
                        <td class="unselectable" width="20%"> &nbsp;</td>
                    </tr>
                    <?php
                    foreach($tableData as $task){?>
                    <tr>
                        <td> <?php echo $task[0];?></td>
                        <td><?php echo $task[1];?></td>
                        <td><?php echo $task[2];?></td>
                        <td><?php echo $task[3];?></td>
                        <td> <?php echo $task[4];?></td>
                        <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.min.js"></script>
                        <script>
                            $('#table tr').click(function(){
                            if(this.rowIndex >0 ){
                            document.getElementById("Update Task").disabled=false;
                            document.getElementById("Remove Task").disabled=false;
                            $(this).addClass('selected').siblings().removeClass('selected');
                            sessionStorage.setItem('rowIndex',this.rowIndex );
                            sessionStorage.setItem('taskID',this.cells[0].innerHTML);
                            sessionStorage.setItem('taskName',this.cells[1].innerHTML);
                            sessionStorage.setItem('estimation',this.cells[2].innerHTML);
                            sessionStorage.setItem('precedents',this.cells[3].innerText);
                            sessionStorage.setItem('type',this.cells[4].innerText);
                            var taskIDs = [];
                            var table = document.getElementById('table');
                            for (i=2;i<table.rows.length-1;i++){
                                if(table.rows[i].cells[0].innerHTML!=sessionStorage.getItem('taskID')){
                                    taskIDs.push(table.rows[i].cells[0].innerText);
                                    taskIDs.push(table.rows[i].cells[1].innerText);
                                }
                            }
                            sessionStorage.tasks = JSON.stringify(taskIDs);
                            //value is project ID
                            }
                            });
                        </script>
                    </tr>
                    <?php }?>
                    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.min.js"></script>
                    <script>
                        function addTask(){
                            var definedTasks = parseInt(<?php echo sizeof($tableData);?>);
                            var databaseID = parseInt('<?php echo DB::table('tasks')->max('id') + 1;?>');
                            var tableIDs = parseInt(document.getElementById('table').rows.length)-3;
                            console.log(definedTasks);
                            console.log(databaseID);
                            console.log(tableIDs);
                            document.getElementById('Add_taskID').value = databaseID+tableIDs-definedTasks;
                            var taskIDs = [];
                            var table = document.getElementById('table');
                            for (i=2;i<table.rows.length-1;i++){
                                taskIDs.push(table.rows[i].cells[0].innerHTML);
                                taskIDs.push(table.rows[i].cells[1].innerHTML);
                            }
                            sessionStorage.setItem('tasks',JSON.stringify(taskIDs));
                            createPrecedents();
                            $('#formContainer2').modal('show');
                        }
                        function createPrecedents(){
                            document.getElementById('addCheckList').innerHTML="";
                            var tasks = JSON.parse(sessionStorage.tasks);
                            for (i=0;i<tasks.length/2;i++){
                                var checkBox = document.createElement("input");
                                var text = document.createElement("Label");
                                text.innerHTML = tasks[2 * i] + " - " + tasks[2 * i + 1] + "  ";
                                checkBox.setAttribute("type", "checkbox");
                                checkBox.setAttribute("value", tasks[2 * i]);
                                checkBox.setAttribute("id", "add"+i.toString());
                                document.getElementById('addCheckList').appendChild(checkBox);
                                document.getElementById('addCheckList').appendChild(text);
                            }
                        }
                        function updateTask(){
                            document.getElementById("taskID").value = sessionStorage.getItem('taskID');
                            document.getElementById("taskName").value = sessionStorage.getItem('taskName');
                            document.getElementById("estimation").value = sessionStorage.getItem('estimation');
                            setPrecedents();
                            document.getElementById('table').rows[sessionStorage.getItem('rowIndex')].className = 'unselected';
                            document.getElementById("Update Task").disabled=true;
                            document.getElementById("Remove Task").disabled=true;
                            $('#formContainer1').modal('show');
                        }
                        function removeTask(){
                            var table = document.getElementById('table');
                            var removedID = parseInt(table.rows[sessionStorage.getItem('rowIndex')].cells[0].innerText);
                            for(i=2;i<table.rows.length-1;i++){
                                if (removedID < parseInt(table.rows[i].cells[0].innerText && removedID>parseInt('<?php echo DB::table('tasks')->max('id') + 1;?>'))) {
                                    table.rows[i].cells[0].innerText = parseInt(table.rows[i].cells[0].innerText) - 1;
                                }
                                var precedents = table.rows[i].cells[3].innerText.split('/');
                                var precedentsString = '';
                                for (j = 0; j < precedents.length; j++) {
                                    if (removedID < parseInt(precedents[j]) && removedID>parseInt('<?php echo DB::table('tasks')->max('id') + 1;?>')) {
                                        precedentsString = precedentsString + (parseInt(precedents[j]) - 1).toString() + '/';
                                    }
                                    if (removedID < parseInt(precedents[j]) && removedID<=parseInt('<?php DB::table('tasks')->max('id') + 1;?>')) {
                                        precedentsString = precedentsString + (parseInt(precedents[j])).toString() + '/';
                                    }
                                    if (removedID > parseInt(precedents[j])) {
                                        precedentsString = precedentsString + parseInt(precedents[j]).toString() + '/';
                                    }
                                }
                                if (precedentsString != "") {
                                    precedentsString = precedentsString.substring(0, precedentsString.length - 1);
                                }
                                table.rows[i].cells[3].innerText = precedentsString;
                            }
                            table.deleteRow(sessionStorage.getItem('rowIndex'));
                            document.getElementById("Update Task").disabled=true;
                            document.getElementById("Remove Task").disabled=true;
                        }
                        function createHiddenInputs(){
                            var table = document.getElementById('table');
                            if(table.rows.length!=3){
                                for(i=2;i<table.rows.length-1;i++){
                                    var hiddenData = document.createElement("INPUT");
                                    hiddenData.setAttribute("type", "hidden");
                                    var data = table.rows[i].cells[0].innerText+"|"+table.rows[i].cells[1].innerText+"|"+table.rows[i].cells[2].innerText+"|"+table.rows[i].cells[3].innerText+"|"+table.rows[i].cells[4].innerText;
                                    hiddenData.setAttribute("id", "input_"+table.rows[i].cells[0].innerText);
                                    hiddenData.setAttribute("name", "rows[]");
                                    hiddenData.setAttribute("value", data);
                                    document.getElementById("inputs").appendChild(hiddenData);
                                }
                                document.getElementById("updateForm").submit();
                            }else{
                                alert("Define Project Tasks!");
                            }
                        }
                    </script>
                    <tr style="display:none;">
                        <td class="unselectable" width="20%"> 0</td>
                        <td class="unselectable" width="20%">end </td>
                        <td class="unselectable" width="20%">0</td>
                        <td class="unselectable" width="20%"> &nbsp;</td>
                        <td class="unselectable" width="20%"> &nbsp;</td>
                    </tr>
                </table>
            </div>
            <div class="box-footer">
                <span style="display:inline-block; width: 5px;"></span>
                <input  id="Update Task" type="button" onclick="updateTask()" style="height:30px;width:100px"  name="OK" class="btn btn-default"  value = "Update Task"  disabled/>
                <span style="display:inline-block; width: 40px;"></span>
                <input  id="Remove Task" type="button" onclick="removeTask()" style="height:30px;width:100px" name="OK" class="btn btn-default" value="Remove Task" disabled>
                <span style="display:inline-block; width: 40px;"></span>
                <input type="button" onclick="addTask()" style="height:30px;width:100px" name="OK" class="btn btn-default" value="Add New Task" />

            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="button" class="btn btn-primary" onclick="createHiddenInputs()">Update Project</button>
            </div>



        </form>
    </div>
    <div id="formContainer1" class="modal" role="dialog">
        <form role="form" name="taskUpdateForm">
            <div class="modal-dialog" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Update Task Details </h3>
                    </div>
                <div class="modal-body">
                    <label for="taskID">Task ID</label>
                    <input type="text" class="form-control" id="taskID" readonly>

                </div>
                <div class="modal-body">
                    <label for="taskName">Task Name</label>
                    <input type="text" class="form-control" id="taskName" name="taskName" placeholder="Enter Task Name">

                </div>
                <div class="modal-body">
                    <label for="estimatedTime">Estimated Time</label>
                    <p class="help-block">*in days </p>
                    <input type="text" class="form-control" name = "estimation" id="estimation" placeholder="Enter Time Estimation in days">

                </div>
                <div class="modal-body">
                    <label for="taskType">Task Type</label><br>
                    <input type="radio" id="analysis" name = "update_type" value="analysis"> Analysis<br>
                    <input type="radio" id="design" name = "update_type" value="design"> Design<br>
                    <input type="radio" id="implementation" name = "update_type" value="implementation"> Implementation<br>
                    <input type="radio" id="testing" name = "update_type" value="testing"> Testing<br>
                </div>
                <div class="modal-body">
                    <label for="precedents">Check Precedent Tasks</label>
                    <p class="help-block"> Before starting a task, all of its precedent tasks should be completed. </p>
                    <div id="updateCheckList">
                        <script>
                            function setPrecedents(){
                                document.getElementById('updateCheckList').innerHTML="";
                                var tasks = JSON.parse(sessionStorage.tasks);
                                var precedents = sessionStorage.getItem('precedents').split("/");
                                for (i=0;i<tasks.length/2;i++){
                                    var checkBox = document.createElement("input");
                                    var text = document.createElement("Label");
                                    text.innerHTML = tasks[2 * i] + " - " + tasks[2 * i + 1] + "  ";
                                    checkBox.setAttribute("type", "checkbox");
                                    checkBox.setAttribute("value", tasks[2 * i]);
                                    checkBox.setAttribute("id", "update"+i.toString());
                                    if (precedents.indexOf(tasks[2 * i]) != -1) {
                                        checkBox.checked = true;
                                    }
                                    document.getElementById('updateCheckList').appendChild(checkBox);
                                    document.getElementById('updateCheckList').appendChild(text);
                                }
                                setType();
                            }
                            function setType(){

                                if (sessionStorage.getItem('type') === "analysis"){
                                    document.getElementById('analysis').checked = true;
                                }else if (sessionStorage.getItem('type')==='design'){
                                    document.getElementById('design').checked = true;
                                }else if (sessionStorage.getItem('type')==='implementing'){
                                    document.getElementById('implementation').checked = true;
                                }else if (sessionStorage.getItem('type')==='testing'){
                                    document.getElementById('testing').checked = true;
                                }
                            }
                            function updateTaskTable(){
                                if(validateUpdateForm()) {
                                    var taskArray = JSON.parse(sessionStorage.tasks);
                                    var table = document.getElementById('table');
                                    var row = sessionStorage.getItem('rowIndex');
                                    precedentsString = "";
                                    for (j = 0; j < taskArray.length / 2; j++) {
                                        if (document.getElementById("update" + j.toString()).checked) {
                                            precedentsString = precedentsString + document.getElementById("update" + j.toString()).value + "/";
                                        }
                                    }
                                    if (precedentsString != "") {
                                        precedentsString = precedentsString.substring(0, precedentsString.length - 1);
                                    }
                                    table.rows[row].cells[1].innerHTML = document.getElementById('taskName').value;
                                    table.rows[row].cells[2].innerHTML = document.getElementById('estimation').value;
                                    table.rows[row].cells[3].innerHTML = precedentsString;
                                    table.rows[row].cells[4].innerHTML = document.querySelector('input[name="update_type"]:checked').value;
                                    $(function () {
                                        $('#formContainer1').modal('toggle')
                                    });
                                }
                            }
                            function validateUpdateForm() {
                                var name = document.forms["taskUpdateForm"]["taskName"].value;
                                var duration = document.forms["taskUpdateForm"]["estimation"].value;
                                if (name == "") {
                                    alert("Task Name cannot be empty!");
                                    return false;
                                }else if(!isNumeric(duration)){
                                    alert("Estimated Time is not valid!");
                                    return false;
                                }else{
                                    return true;
                                }
                            }
                        </script>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-primary" onClick="updateTaskTable()" value="Update Task"/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                    </div>
                </div>
            </div>
                </div>
        </form>
    </div>
    <div id="formContainer2"  class="modal" role="dialog">
        <form role="form" name="newTaskForm">
            <div class="modal-dialog" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Enter New Task Details</h3>
                    </div>
                <div class="modal-body">
                    <label for="taskID">Task ID</label>
                    <input type="text" class="form-control" id="Add_taskID"  readonly>

                </div>
                <div class="modal-body">
                    <label for="taskName">Task Name</label>
                    <input type="text" name="newTaskName" class="form-control" id="Add_taskName" placeholder="Enter Task Name">

                </div>
                <div class="modal-body">
                    <label for="estimatedTime">Estimated Time</label>
                        <p class="help-block"> *in days </p>
                    <input type="text" class="form-control" name="newTaskDuration" id="Add_estimation" placeholder="Enter Time Estimation in days">

                </div>
                <div class="modal-body">
                    <label for="taskType">Task Type</label><br>
                    <input type="radio" name = "type" value="analysis" checked> Analysis<br>
                    <input type="radio" name = "type" value="design"> Design<br>
                    <input type="radio" name = "type" value="implementation"> Implementation<br>
                    <input type="radio" name = "type" value="testing"> Testing<br>
                </div>
                <div id="container" class="modal-body">
                    <label for="precedents">Check Precedent Tasks</label>
                    <p class="help-block"> Before starting a task, all of its precedent tasks should be completed. </p>
                    <div id="addCheckList">
                        <script>
                            function Add_updateTaskTable(){
                                if(validateForm()) {
                                    var taskArray = JSON.parse(sessionStorage.tasks);
                                    var table = document.getElementById('table');
                                    precedentsString = "";
                                    for (j = 0; j < taskArray.length / 2; j++) {
                                        if (document.getElementById("add" + j.toString()).checked) {
                                            precedentsString = precedentsString + document.getElementById("add" + j.toString()).value + "/";
                                        }
                                    }
                                    if (precedentsString != "") {
                                        precedentsString = precedentsString.substring(0, precedentsString.length - 1);
                                    }
                                    var row = table.insertRow(2 + (taskArray.length / 2));
                                    cell0 = row.insertCell(0);
                                    cell0.innerHTML = document.getElementById("Add_taskID").value;
                                    cell1 = row.insertCell(1);
                                    cell1.innerHTML = document.getElementById("Add_taskName").value;
                                    cell2 = row.insertCell(2);
                                    cell2.innerHTML = document.getElementById("Add_estimation").value;
                                    cell3 = row.insertCell(3);
                                    cell3.innerHTML = precedentsString;
                                    cell4 = row.insertCell(4);
                                    cell4.innerHTML = document.querySelector('input[name="type"]:checked').value;
                                    $(row).click(function () {
                                        if (this.rowIndex > 0) {
                                            document.getElementById("Update Task").disabled = false;
                                            document.getElementById("Remove Task").disabled = false;
                                            $(this).addClass('selected').siblings().removeClass('selected');
                                            sessionStorage.setItem('rowIndex', this.rowIndex);
                                            sessionStorage.setItem('taskID', this.cells[0].innerHTML);
                                            sessionStorage.setItem('taskName', this.cells[1].innerHTML);
                                            sessionStorage.setItem('estimation', this.cells[2].innerHTML);
                                            sessionStorage.setItem('precedents', this.cells[3].innerHTML);
                                            sessionStorage.setItem('type', this.cells[4].innerHTML);
                                            var taskIDs = [];
                                            var table = document.getElementById('table');
                                            for (i = 2; i < table.rows.length - 1; i++) {
                                                if (table.rows[i].cells[0].innerHTML != sessionStorage.getItem('taskID')) {
                                                    taskIDs.push(table.rows[i].cells[0].innerHTML);
                                                    taskIDs.push(table.rows[i].cells[1].innerHTML);
                                                }
                                            }
                                            sessionStorage.tasks = JSON.stringify(taskIDs);
                                            //value is project ID
                                        }
                                    });
                                    document.getElementById("Add_taskID").value = "";
                                    document.getElementById("Add_taskName").value = "";
                                    document.getElementById("Add_estimation").value = "";
                                    $(function () {
                                        $('#formContainer2').modal('toggle')
                                    });
                                }
                            }
                            function validateForm() {
                                var name = document.forms["newTaskForm"]["newTaskName"].value;
                                var duration = document.forms["newTaskForm"]["newTaskDuration"].value;
                                if (name == "") {
                                    alert("Task Name cannot be empty!");
                                    return false;
                                }else if(!isNumeric(duration)){
                                    alert("Estimated Time is not valid!");
                                    return false;
                                }else{
                                    return true;
                                }
                            }
                            function isNumeric(n) {
                                return !isNaN(parseFloat(n)) && isFinite(n);
                            }

                        </script>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-primary" onClick="Add_updateTaskTable()" value="Add Task" />
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
                </div>
        </form>
    </div>
    </body>


@endsection