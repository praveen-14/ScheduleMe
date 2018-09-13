@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        var tasks = {
        data:[
        {id:1, text: '{{$record['name']}}',start_date:"10-05-2017", duration:11,
        progress: 0.6, open: true},
        {id:2, text:"Task #1",   start_date:date, duration:5,
        progress: 1,   open: true, parent:1},
        {id:3, text:"Task #2",   start_date:date, duration:7,
        progress: 0.5, open: true, parent:1},
        {id:4, text:"Task #2.1", start_date:date, duration:2,
        progress: 1,   open: true, parent:3},
        {id:5, text:"Task #2.2", start_date:date, duration:3,
        progress: 0.8, open: true, parent:3},
        {id:6, text:"Task #2.3", start_date:"05-04-2015", duration:4,
        progress: 0.2, open: true, parent:3}
        ],
        links:[
        {id:1, source:1, target:2, type:"1"},
        {id:2, source:1, target:3, type:"1"},
        {id:3, source:3, target:4, type:"1"},
        {id:4, source:4, target:5, type:"0"},
        {id:5, source:5, target:6, type:"0"}
        ]
        };
    </div>
</div>
@endsection

