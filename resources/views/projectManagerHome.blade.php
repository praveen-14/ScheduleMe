@extends('layouts.app')
<?php
use App\Project;
$projects = Project::where('project_manager_id',Auth::user()->id)->get();
?>
@section('content')
    <head xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
        <style>
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
<div>
    <div>
        <br><br><br>
<table id="table" border="1" align="center">
<tr>
        <th  class="col-lg-2">Project ID </th>
        <th  class="col-lg-2">Project Name </th>
</tr>
    <?php
    foreach($projects as $project) {?>
    <tr>
        <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.min.js"></script>
        <script>
            var value = null;
            var row = null;
            $("#table tr").click(function(){
                if(this.rowIndex > 0){
                    document.getElementById("updateProject").disabled=false;
                    document.getElementById("removeProject").disabled=false;
                    document.getElementById("startSchedule").disabled=false;
                    $(this).addClass('selected').siblings().removeClass('selected');
                    value=$(this).find('td:first').html();
                    row = this;
                    document.getElementById("projectIDHidden").value=value;
                    //value is project ID
                }
            });

            function removeProject(){

            }
        </script>
        <td ><?php echo $project->id;?></td>
        <td ><?php echo $project->name;?></td>
    </tr>
        <?php }?>

</table>
    </div>
    <script>
        function update_project(){
            window.location.href = 'http://scheduleme.dev/updateProject/'+ value;
        }

        function getParameters(){
            document.getElementById("projectID").value=value;
            document.getElementById("projectName").value=row.cells[1].innerHTML;
        }

    </script>
    <div class="box-footer"><br>
        <form role="form" action = '{{route("removeProject")}}' method = "post">
            {{ csrf_field() }}
        <span style="display:inline-block; width: 365px;"></span>
        <input  id="updateProject" type="button" onclick="update_project()" style="height:40px;width:150px"  name="updateProject" class="btn btn-primary" value="Update Project" disabled >
        <span style="display:inline-block; width: 40px;"></span>
            <input  id="removeProject" type="submit" style="height:40px;width:150px" name="removeProject" class="btn btn-primary" value="Remove Project" disabled>
            <input  id="projectIDHidden" type="hidden" style="height:30px;width:100px" name="projectID" class="ok">
            <span style="display:inline-block; width: 40px;"></span>
            <input  id="startSchedule" type="button" onClick = "getParameters()" style="height:40px;width:150px" name="startSchedule" value="Start Scheduling" data-toggle="modal" data-target="#formContainer" class="btn btn-primary" disabled >
        </form>

    </div>
</div>
<div id="formContainer" class="modal" role="dialog">
<form id="scheduleForm" name="scheduleForm" action ='{{route("schedule")}}' method = "post">
    {{ csrf_field() }}
    <div class="modal-dialog" role="document" >
        <div class="modal-content">
            <script>
                function showSchedule(){
                    if(validateForm()) {
                        var details = value.toString() + ":" + document.querySelector('input[name="productivity"]:checked').value.toString();
                        sessionStorage.setItem('startDate', document.getElementById('startDate').value);
                        sessionStorage.setItem('details', details);
                        document.getElementById("scheduleForm").submit();
                    }

                }
                function validateForm() {
                    var projectID = document.forms["scheduleForm"]["projectID"].value;
                    var projectName = document.forms["scheduleForm"]["projectName"].value;
                    var startDate = document.forms["scheduleForm"]["startDate"].value;
                    if (projectName == "") {
                        alert("Project Name cannot be empty!");
                        return false;
                    }else if(!isNumeric(projectID)){
                        alert("Project ID not valid not valid!");
                        return false;
                    }else if(!startDate){
                        alert("Set Start Date!");
                        return false;
                    }else{
                        return true;
                    }
                }
                function isNumeric(n) {
                    return !isNaN(parseFloat(n)) && isFinite(n);
                }
            </script>
            <div class="modal-body">
                <label for="projectID">Project ID</label>
                <input type="text" class="form-control" id="projectID" name="projectID"  readonly>
            </div>
            <div class="modal-body">
                <label for="projectName">Project Name</label>
                <input type="text" class="form-control" id="projectName" name="projectName"  readonly>
            </div>
            <div class="modal-body">
                <label for="startDate">Select Project Starting Date</label><br>
                <input type="date" class="date" id="startDate" name="startDate" >
            </div>
            <div class="modal-body">
                <label for="productivity">Expected Productivity of the Schedule</label>
                <p class="help-block"> *Note - to generate highly productive schedules, system takes longer time periods </p>
                <input type="radio" id="high" name = "productivity" value="high"> High<br>
                <input type="radio" id="medium" name = "productivity" value="medium" checked> Medium<br>
                <input type="radio" id="low" name = "productivity" value="low"> Low<br>
            </div>
            <div class="modal-footer">
                <input  id="schedule" type="button" onClick = "showSchedule()" style="height:30px;width:100px" name="schedule" value="Schedule" class="btn btn-primary" >
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
    </form>
    </div>
    </body>

@endsection


