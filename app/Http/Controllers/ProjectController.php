<?php

namespace App\Http\Controllers;

use App\Project;
use App\TimeEntry;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return view('projects.index', compact('projects'));
    }
    public function filter($id)
    {
        $project = Project::with('tasks.tasktrack')->find($id);
        $tasks = $project->tasks;
        if (!$project) {
            return response()->json(['error' => 'Project not found'], 404);
        }

        $tasks = $project->tasks->map(function ($task) {
            return [
                'id' => $task->id,
                'name' => $task->name,
                'hours' => $task->tasktrack->sum('hours'),
            ];
        })->filter(function ($task) {
            return $task['hours'] > 0; 
        })->values();

        $total_hours = $project->totalHours();

        return response()->json([
            'project_name' => $project->name,
            'tasks' => $tasks,
            'total_hours' => $total_hours,
        ]);
    }
    public function showReport()
    {
        $projects = Project::where('status', 'Active')->with('tasks.tasktrack')->get();
        return view('time.report', compact('projects'));
    }
}
