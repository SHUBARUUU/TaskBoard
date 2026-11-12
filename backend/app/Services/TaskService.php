<?php

namespace App\Services;

//* REPOSITORY
use App\Repositories\TaskRepository;


use Illuminate\Database\Eloquent\Collection;

class TaskService{
    protected $taskRepository;

    // Inject the Repository into the Service so we can use it
    public function __construct(TaskRepository $taskRepository){
        $this->taskRepository = $taskRepository;
    }

    // This handles the request from the controller's index() method
    // The parameters accepts array and defaults to an empty array if theres nothing received
    // : Collection, This is a promise for PHP, because by default it returns anything. The promise states that you return a Collection
    public function getAllTasks(array $filters = []): Collection{
        // The service acts as the manager. It asks the repository for the data.
        return $this->taskRepository->getAllTasks($filters);
    }

    public function createTask(array $data){
        //  1. Enforce the business rule
        if(isset($data['status']) && $data['status'] === 'completed'){
            /*  Adding the backslash (\) tells PHP: "Go back to the root, global level of PHP. I want to use the core, built-in Exception class." 
                It’s exactly like typing / in a Linux terminal to go to the root directory.
                A shortcut rather than import a global class of Exception why not call it directly from its src when its about to be use.
            */
            throw new \Exception('Cheater! New tasks must begin with a pending status');
        }

        return $this->taskRepository->createTask($data);
    }

    public function getTaskById(string $id){
        return $this->taskRepository->getTaskById($id);
    }
    
    /*  Unlike Exception where the class lives in the root. We do the \App\Modelts\Task just to use the class in an instant.
        Have to do it use the shortcut all over again if we have to use it again. (But by stating it globally we wont have to.) 
    */  
    public function updateTask(\App\Models\Task $task, array $data){

        // Rule A: If task is completed, you can't change its priority.
        if ($task->status === 'completed' && isset($data['priority'])) {
            throw new \Exception('Cannot modify priority on a completed task.');
        }

        // Rule B: If they are marking it completed right now, let's pretend we need a timestamp
        if (isset($data['status']) && $data['status'] === 'completed') {
            $task->completed_at = now(); // Modifying the object directly based on a business rule
        }

        return $this->taskRepository->updateTask($task, $data);
    }
    public function deleteTask(\App\Models\Task $task){
        // Rule A: Prevent deleting a task created less than 5 minutes ago
        if ($task->created_at && $task->created_at->diffInMinutes(now()) < 5) {
            throw new \Exception('You must wait 5 minutes before deleting a newly created task.');
        }

        // Rule B: Prevent deleting tasks with long descriptions to avoid accidental data loss
        if (strlen($task->description) > 100) {
             throw new \Exception('Clear the detailed description before deleting this task.');
        }
        
        return $this->taskRepository->deleteTask($task);
    }
}