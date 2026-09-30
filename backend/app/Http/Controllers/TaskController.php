<?php

namespace App\Http\Controllers;

use App\Models\Task;

use Illuminate\Http\Request;



class TaskController extends Controller
{
    /*
    * Laravel apiResource Standard Method Mapping:
    * -----------------------------------------------------------
    * index()   -> GET    /api/tasks       (Fetch all items)
    * store()   -> POST   /api/tasks       (Create a new item)
    * show()    -> GET    /api/tasks/{id}  (Fetch one specific item)
    * update()  -> PUT    /api/tasks/{id}  (Update a specific item)
    * destroy() -> DELETE /api/tasks/{id}  (Delete a specific item)
    */

    // 1. GET /api/tasks 
    // Triggered when React wants a list of ALL tasks
    public function index(){
        return Task::all();
    }
    // 2. POST /api/tasks 
    // Triggered when React sends a JSON payload to CREATE a new task
    public function store(Request $request)
    {
        $task = Task::create($request->all());
        return response()->json($task, 201);
    }

    // 3. GET /api/tasks/{id} 
    // Triggered when React wants to view ONE specific task (e.g., /api/tasks/5)
    public function show(string $id)
    {
        // We will write this later
    }

    // 4. PUT /api/tasks/{id} 
    // Triggered when React wants to UPDATE a specific task
    public function update(Request $request, string $id)
    {
        // We will write this later
    }

    // 5. DELETE /api/tasks/{id} 
    // Triggered when React wants to DELETE a specific task
    public function destroy(string $id)
    {
        // We will write this later
    }
}
