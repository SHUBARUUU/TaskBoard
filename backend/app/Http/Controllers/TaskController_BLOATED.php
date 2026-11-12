<?php
//? The initial file now bloated and serve as the comparison of the other TaskController.
namespace App\Http\Controllers;

use App\Models\Task;

use Illuminate\Http\Request;

//* Controller -> Receives data, validates data, acts as the entry point for the datas.

class TaskController_BLOATED extends Controller
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

    /*
     * The Request object in Laravel captures the entire HTTP envelope 
     * received from the frontend (like React's fetch).
     * 
     * It holds:
     * 1. URL Meta-data (Query strings like ?priority=high, HTTP method, IP address)
     * 2. The Payload (The JSON body sent during POST/PUT requests)
     * 
     * In other words, Frontend always have a feedback regardless of what HTTP method you use. (Request object always catches does feedback) (Most frequent use of the Request object is for POST/PUT/PATCH)
    */

    // 1. GET /api/tasks 
    // Triggered when React wants a list of ALL tasks
    /*
    The previous index function (GET method (GET every data.))
        public function index(){
            return Task::all();//   Returns the tasks
        }
    */
        //* The updated index. Added backend filtering -> (Frontend filtering introduces bottlenecks)
    public function index(Request $request){
        //  --- DATABASE BLOAT ---

        // 1. Start a database query. 
        //  Like typing "SELECT * FROM tasks" but pausing before hitting Enter.
        $query = Task::query();

        // --- THE BLOAT: QUERY LOGIC START  ---
        /*
        ? NOTE: Our current React app just fetches "/api/tasks", so all these IF statements get skipped.
         This is intentionally bloated to future-proof the API. When we eventually add a search bar 
         or a filter dropdown in React, this backend will already know how to handle the new URL parameters.
        */

        // --- BUSINESS LOGIC AND DATABASE BLOAT ---
        // Check if the URL has ?status=something
        if ($request->has('status')) {
            //  Gets the string literal after checking if URL has status meaning (?status=) is in the URL
            //  Forming WHERE status = (pending, completed) anything that starts after the " = " sign.
            //  query() func focuses on the site's URL
            $query->where('status', $request->query('status'));
        }

        // Check if the URL has ?priority=something
        if ($request->has('priority')) {
            $query->where('priority', $request->query('priority'));
        }

        // Check if the URL has ?search=something
        if ($request->has('search')) {
            $searchTerm = $request->query('search');
            $query->where('title', 'LIKE', "%{$searchTerm}%");
        }

        // Always sort by newest first
        $query->orderBy('created_at', 'desc');

        // --- DATABASE BLOAT ---
        // 2. The Worker: Execute the query based on whatever filters (if any) were applied above.
        $tasks = $query->get();

        // 3. The Messenger: Return them as JSON.
        return response()->json($tasks, 200);
    }

    // 2. POST /api/tasks 
    // Triggered when React sends a JSON payload to CREATE a new task
    /*
    The previous store function (POST method). -> Without validation and accepting blindly the requests getting received  
        public function store(Request $request){
            $task = Task::create($request->all());//    Creates a task from $request (JSON Format)

            return response()->json($task, 201);
        }
    */
    //* The updated store function. Added validation (Controllers job)
    public function store(Request $request){
        // Laravel validate parses the string to be send to Validator objects (Deep inside the laravel framework). Will send an error(message and HTTP code) if failed.
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending, completed',
            'priority' => 'required|in:low,medium,low',
        ]); 

        // --- BUSINESS LOGIC BLOAT ---
        // Block anyone trying to create a task that is already finished.
        if($validatedData['status'] === 'completed'){
            return response()->json(['message' => 'Cheater! New tasks must begin with a pending status'], 422);
        }

        //  --- DATABASE BLOAT: Creating the validated data in controller---
        $task = Task::create($validatedData);

        return response()->json($task, 201);
    }

    // 3. GET /api/tasks/{id} 
    // Triggered when React wants to view ONE specific task (e.g., /api/tasks/5)
    public function show(string $id)
    {
        $task = Task::findOrFail($id);

        return response()->json($task, 200);
    }

    // 4. PUT /api/tasks/{id} 
    // Triggered when React wants to UPDATE a specific task
    /**
     * The previous update function (PUT method)
     * public function update(Request $request, string $id){
     *      $task = Task::findOrFail($id);
     *      $task ->update($validatedData);
     *      return response()->json($task, 200)
     * }
     */
    //* The updated update. Added validation as well (Still controller's job)
    public function update(Request $request, string $id){
        // --- DATABASE BLOAT: Manually searching for the record ---
        $task = Task::find($id);
        
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        // --- VALIDATION: Stays here, but adds to the size of the method ---
        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // --- BUSINESS LOGIC BLOAT: Hardcoded rules crammed into the controller ---
        
        // Rule A: If task is completed, you can't change its priority.
        if ($task->status === 'completed' && isset($validatedData['priority'])) {
            return response()->json(['error' => 'Cannot modify priority on a completed task.'], 422);
        }

        // Rule B: If they are marking it completed right now, let's pretend we need a timestamp
        if (isset($validatedData['status']) && $validatedData['status'] === 'completed') {
            $task->completed_at = now(); // Modifying the object directly based on a business rule
        }

        // --- DATABASE BLOAT: Saving the record ---
        $task->update($validatedData);

        return response()->json($task, 200);
    }

    // 5. DELETE /api/tasks/{id} 
    // Triggered when React wants to DELETE a specific task
    /*
        The previous destroy function (DELETE method)
        public function destroy(string $id)
        {
            $task = Task::findOrFail($id);

            $task->delete();

            return response()->json(null, 204);
        }
    */
        public function destroy(string $id){
        // 1. DATABASE BLOAT: Controller acting like a database query builder
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        // 2. BUSINESS LOGIC BLOAT: Controller acting like a manager making business decisions
        
        // Rule A: Prevent deleting a task created less than 5 minutes ago
        if ($task->created_at && $task->created_at->diffInMinutes(now()) < 5) {
            return response()->json(['message' => 'You must wait 5 minutes before deleting a newly created task.'], 422);
        }

        // Rule B: Prevent deleting tasks with long descriptions to avoid accidental data loss
        if (strlen($task->description) > 100) {
             return response()->json(['message' => 'Clear the detailed description before deleting this task.'], 422);
        }

        // 3. DATABASE BLOAT: Controller manually executing the delete command
        $task->delete();

        // 4. THE MESSENGER (Not bloat - this is what the controller actually SHOULD do)
        return response()->json(['message' => 'Task deleted successfully'], 200);
    }
}
