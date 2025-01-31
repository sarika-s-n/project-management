<?php

namespace App\Http\Controllers;
use App\Task;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with('project')->whereHas('project', function ($query) {
            $query->where('status', '=', 'Active');
        })->get();
        return view('tasks.index', compact('tasks'));
    }
}
