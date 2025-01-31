@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Add Time Entry</h2>
        <form id="time-entry-form" method="POST" action="{{ url('/time-entries/add') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">


            <div class="form-group">
                <label for="project_id">Project</label>
                <select name="project_id" id="project_id" class="form-control" required>
                    <option value="">Select Project</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="task_id">Task</label>
                <select name="task_id" id="task_id" class="form-control" required>
                    <option value="">Select Task</option>
                </select>
            </div>

            <div class="form-group">
                <label for="hours">Hours</label>
                <input type="number" name="hours" class="form-control" required min="1">
            </div>

            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" name="date" id="date" class="form-control"  required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Add Time Entry</button>
        </form>

        <h3>Time Entries</h3>
        <table id="time-entries-table" class="table table-bordered">
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Task</th>
                    <th>Hours</th>
                    <th>Date</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($timeEntries as $entry)
                    <tr>
                        <td>{{ $entry->project->name }}</td>
                        <td>{{ $entry->task->name }}</td>
                        <td>{{ $entry->hours }}</td>
                        <td>{{ \Carbon\Carbon::parse($entry->date)->format('d/m/Y') }}</td>
                        <td>{{ $entry->description }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#time-entries-table').DataTable();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#project_id').change(function() {
                var projectId = $(this).val();
                $('#task_id').html('<option value="">Loading...</option>');

                if (projectId) {
                    $.ajax({
                        url: '/get-tasks/' + projectId,
                        type: 'GET',
                        success: function(data) {
                            $('#task_id').html('<option value="">Select Task</option>');
                            $.each(data, function(key, value) {
                                $('#task_id').append('<option value="' + value.id +
                                    '">' + value.name + '</option>');
                            });
                        }
                    });
                }
            });

            $('#time-entry-form').submit(function(e) {
                e.preventDefault();

                var formData = $(this).serialize(); // Serialize the form data

                $.ajax({
                    url: '/time-entries/add',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.status === 'success') {
                        var newRow = table.row.add([
                            response.project_name,
                            response.task_name,
                            response.hours,
                            response.date,
                            response.description
                        ]).draw().node();

                        $(newRow).addClass('new-entry');
                        $('#time-entry-form')[0].reset();
                        alert("saved successfully");
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error: ' + error);
                    }
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        var today = moment().format('YYYY-MM-DD');
        document.getElementById('date').setAttribute('max', today);
    });
    </script>
@endsection
