@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Project Report</h2>

        <div class="form-group">
        <label for="project_id">Search by Project</label>
        <select name="project_id" id="project_id" class="form-control">
            <option value="">Select Project</option>
            @foreach ($projects as $project)
                <option value="{{ $project->id }}">{{ $project->name }}</option>
            @endforeach
        </select>
    </div>

        <h3>Report</h3>
        <table class="table table-bordered" id="report-table">
            <thead>
                <tr>
                    <th>Sno</th>
                    <th>Name</th>
                    <th>Total Hours</th>
                </tr>
            </thead>
            <tbody>
                @php $sno = 1; @endphp
                @foreach ($projects as  $project)
                    @if ($project->totalHours() > 0)
                    <tr style="background-color: lightblue;">
                            <td>{{ $sno++ }}</td>
                            <td>{{ $project->name }}</td>
                            <td>{{ $project->totalHours() }}</td>
                        </tr>
                        @foreach ($project->tasks as $task)
                            @if ($task->tasktrack->sum('hours') > 0)
                                <tr>
                                    <td></td>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;{{ $task->name }}</td>
                                    <td>{{ $task->tasktrack->sum('hours') }}</td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- jQuery and Ajax -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#project_id').change(function() {
                var projectId = $(this).val();

                if (projectId) {
                    $.ajax({
                        url: '/report/filter/' + projectId,
                        type: 'GET',
                        success: function(response) {
                            var tableBody = $('#report-table tbody');
                            tableBody.empty(); 

                            var sno = 1;
                            tableBody.append('<tr style="background-color: lightblue;"><td>' + sno++ + '</td><td>' + response
                                .project_name + '</td><td>' + response.total_hours +
                                '</td></tr>');

                            $.each(response.tasks, function(index, task) {
                                tableBody.append('<tr><td></td><td>&nbsp;&nbsp;&nbsp;&nbsp;' + task
                                    .name + '</td><td>' + task.hours + '</td></tr>');
                            });
                        }
                    });
                } else {
                    location.reload();
                }
            });
        });
    </script>
@endsection
