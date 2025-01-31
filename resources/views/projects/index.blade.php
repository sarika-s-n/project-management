@extends('layouts.app')

@section('content')
    <h2>Projects List</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Project Name</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($projects as $index => $project)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $project->name }}</td>
                    <td>{{ $project->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
