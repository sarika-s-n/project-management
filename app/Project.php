<?php

namespace App;
use App\Task;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name', 'status'];
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    public function time()
    {
        return $this->hasMany(TimeEntry::class);
    }
    public function totalHours()
    {
        return $this->time->sum('hours'); 
    }
}
