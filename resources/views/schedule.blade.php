@extends('layouts.app')

@section('content')
    <head>
        <style type="text/css" media="screen">
            html, body{
                margin:0px;
                padding:0px;
                height:100%;
                overflow:hidden;
            }

        </style>
        <title>Schedule Project</title>
        <script src="/dhtmlxGantt/codebase/dhtmlxgantt.js"></script>
        <link href="/dhtmlxGantt/codebase/dhtmlxgantt.css" rel="stylesheet">
        {{--<script src="https://docs.dhtmlx.com/gantt/codebase/dhtmlxgantt.js"></script>--}}
        {{--<link href="https://docs.dhtmlx.com/gantt/codebase/dhtmlxgantt.css" rel="stylesheet">--}}
    </head>
    <body>
    <div id="gantt_here" style="width:1285px; height:550px;">

    </div>
    <div class="box-footer">
        <input type="button" onClick="resheduleProject()" class="btn btn-primary" value="Reshedule Project" />

    </div>
    <script type="text/javascript">
        function resheduleProject() {
            location.reload();
        }
        var tasks = {};
        tasks['data'] = [];
        tasks['links'] = [];
        @foreach($schedule as $record)
        var date = new Date(sessionStorage.getItem('startDate'));
        date.setDate(date.getDate()+parseInt({{$record['startTime']}}));
        var dateString = date.getDate()+"-"+(date.getMonth()+1)+"-"+date.getFullYear();
        tasks['data'].push({id:'{{$record['id']}}', text:'{{$record['details']}}',start_date:dateString , duration:'{{$record['duration']}}',
            progress: 0, open: false});
        @endforeach
//        gantt.config.xml_date = "%Y-%m-%d %H:%i";
        gantt.init("gantt_here");
        gantt.parse(tasks);
//        console.log(sessionStorage.getItem('productivity'));
    </script>
    </body>
@endsection