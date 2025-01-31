@extends('layouts.app')

@section('content')
    <h2>Task List</h2>
<table class="table table-bordered">
    <tr>
        <th>SNo</th>
        <th>Project Name</th>
        <th>Task Name</th>
        <th>Status</th>
    </tr>
    @foreach($tasks as $index => $task)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $task->project->name }}</td>
        <td>{{ $task->name }}</td>
        <td>{{ $task->status }}</td>
    </tr>
    @endforeach
</table>
@endsection
