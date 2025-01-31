<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Task;
use Illuminate\Http\Request;
use App\Project;
use App\TimeEntry;
use Illuminate\Support\Facades\Validator;

use Symfony\Component\HttpFoundation\JsonResponse;

class TimeEntryController extends Controller
{
    public function index()
    {
        $projects = Project::where('status', '!=', 'Inactive')->get();
        $timeEntries = TimeEntry::with('project', 'task')->orderBy('date', 'desc')->get();
        return view('time.AddEntry', compact('projects', 'timeEntries'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'task_id' => 'required|exists:tasks,id',
            'hours' => 'required|integer|min:1',
            'date' => 'required|date',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'error' => 'Validation failed',
                'message' => $validator->errors()
            ], 422);
        }

        $date_formated = Carbon::parse($request->date)->format('Y-m-d');
        $timeEntry = TimeEntry::create([
            'project_id' => $request->project_id,
            'task_id' => $request->task_id,
            'hours' => $request->hours,
            'date' => $date_formated,
            'description' => $request->description,
        ]);
        $project = Project::find($timeEntry->project_id);
        $task = Task::find($timeEntry->task_id);

        return response()->json([
            'status' => 'success',
            'project_name' => $project->name,
            'task_name' => $task->name,
            'hours' => $timeEntry->hours,
            'date' => Carbon::parse($timeEntry->date)->format('d/m/Y'),
            'description' => $timeEntry->description,
        ], 200);
    }
    public function fetchTask($id)
    {
        $tasks = Task::where('project_id', $id)
            ->where('status', 'Active')
            ->select('id', 'name')
            ->get();
        return response()->json($tasks);
    }
}
