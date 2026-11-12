<?php

namespace App\Http\Controllers;

//* MODELS
use App\Models\Task;

//* SERVICES
use App\Services\TaskService;

use Illuminate\Http\Request;

//* Controller -> Receives data, validates data, acts as the entry point for the datas.

//? The flow of the system should be controller -> service -> repository layer.
class TaskController extends Controller{   
    /*
    * Laravel apiResource Standard Method Mapping:
    * -----------------------------------------------------------
    * index()   -> GET    /api/tasks       (Fetch all items)
    * store()   -> POST   /api/tasks       (Create a new item)
    * show()    -> GET    /api/tasks/{id}  (Fetch one specific item)
    * update()  -> PUT    /api/tasks/{id}  (Update a specific item)
    * destroy() -> DELETE /api/tasks/{id}  (Delete a specific item)
    */

    protected $taskService;

    //  --- NEW: Inject the Service into the Controller ---
    public function __construct(TaskService $taskService){
            $this->taskService = $taskService;
    }
    
    // --- REFACTORED: Clean index method ---
    // Uses TaskService (inside uses TaskRepository)
    public function index(Request $request){
        /*
        ? NOTE: Our current React app just fetches "/api/tasks", so all these IF statements get skipped.
         This is intentionally bloated to future-proof the API. When we eventually add a search bar 
         or a filter dropdown in React, this backend will already know how to handle the new URL parameters.
        */

        // 1. The Bouncer: Grab only the allowed filter parameters from the HTTP request
        //  Ignores the other attributes of request such as its headers, tokens, etc..
        //  Also builds an associative array the for it like url have status filters = ['status' => 'something']. It can also add more in the array if its not only status.
        $filters = $request->only(['status', 'priority', 'search']);

        // 2. The Manager: Pass the clean array to the Service
        $tasks = $this->taskService->getAllTasks($filters);

        // 3. The Messenger: Return JSON to React
        return response()->json($tasks, 200);
    }
    
    // --- REFACTORED: Clean store method ---
    // Added validation, business logic (inside taskService), and the appropriate repository layer (talks to the database.)
    public function store(Request $request){
        // validate function returns an associative array after validating. Perfect for sending down to the taskService
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending, completed',
            'priority' => 'required|in:low,medium,high',
        ]);

        //  Sends the array to the taskService function
        $task = $this->taskService->createTask($validatedData);

        return response()->json($task, 201);
    }
    // --- REFACTORED: Clean show method ---
    //  Made a function dedicated for getting the task id in task service to be then pass to repository layer to actually talk to database and find it.
    public function show(string $id){
        $task = $this->taskService->getTaskById($id);

        return response()->json($task, 200);
    }

    // -- REFACTORED: Clean update method ---
    //  Combined concept of the store and show by implementing both of how they manage their datas in this function.
    public function update(Request $request, string $id){
        //  Confirms and gets the object first if its existing in the database.
        $task = $this->taskService->getTaskById($id);

        //  Validates the new set of data received.
        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        //  If both passes the requirement then will be sent to the service layer. updateTask function
        $task = $this->taskService->updateTask($task, $validatedData);

        return response()->json($task, 200);
    }

    public function destroy(string $id){
       
        $task = $this->taskService->getTaskById($id);

        $this->taskService->deleteTask($task);

        return response()->json(null, 200);
    }
}
