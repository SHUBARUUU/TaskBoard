<?php

namespace App\Repositories;

use App\Models\Task;

use Illuminate\Database\Eloquent\Collection;

class TaskRepository{
    // This is the actual database worker for index()
    public function getAllTasks(array $filters = []): Collection
    {
        //  Starts a db query "Select * FROM tasks"
        $query = Task::query();

        // Notice how we use the $filters array passed from the Service, instead of the raw $request object
        //  raw php isset checks the $filters that were passed on if it has status ex: $filter = ['status' => 'pending']. If it's in there = true, false if not.
        if (isset($filters['status'])) {
            //  Forms the WHERE status = pending (Value inside the associative array.) 
            $query->where('status', $filters['status']);
        }

        if (isset($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (isset($filters['search'])) {
            $query->where('title', 'LIKE', "%{$filters['search']}%");
        }

        return $query->orderBy('created_at', 'desc')->get();

        //  IF all the statement is false by default it will return everything.
    }
    public function createTask(array $data){
        return Task::create($data);
    }
    public function getTaskById(string $id){
        // findOrFail will automatically throw a ModelNotFoundException if the ID doesn't exist
        return Task::findOrFail($id);
    }
    public function updateTask(Task $task, $data){
        $task->update($data);
        
        return $task;
    }
    public function deleteTask(Task $task){
        $task->delete();
    }
}