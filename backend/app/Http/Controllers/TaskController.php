<?php

namespace App\Http\Controllers;

use App\Models\Task;

use Illuminate\Http\Request;

//* Controller -> Receives data, validates data, acts as the entry point for the datas.

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
        public function index(Request $request){
            // 1. Start a database query (but don't execute it yet). Laravel version of "Select * from []", it sets up a query like command so the user can do business logic using the data from the database.
            $query = Task::query();

            // --- THE BLOAT: QUERY LOGIC START ---
        
            // Filter by Status (e.g., /api/tasks?status=completed)
            if ($request->has('status')) {
                $query->where('status', $request->query('status'));
            }

            // Filter by Priority (e.g., /api/tasks?priority=high)
            if ($request->has('priority')) {
                $query->where('priority', $request->query('priority'));
            }

            // Search by Title (e.g., /api/tasks?search=bug)
            if ($request->has('search')) {
                $searchTerm = $request->query('search');
                $query->where('title', 'LIKE', "%{$searchTerm}%");
            }

            // Always sort by newest first
            $query->orderBy('created_at', 'desc');
            
            // --- THE BLOAT: QUERY LOGIC END ---

            // 2. The Worker: Execute the query and get the results
            $tasks = $query->get();

            // 3. The Messenger: Return them
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
    //* The updated store function
    public function store(Request $request){
        // Laravel validate parses the string to be send to Validator objects (Deep inside the laravel framework). Will send an error if failed.
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending, completed',
            'priority' => 'required|in:low,medium,low',
        ]); 

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
    public function update(Request $request, string $id)
    {
        $task = Task::findOrFail($id);//    Find Specific Task (Will send an error 404 Not Found if failed.)

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending, completed',
            'priority' => 'required|in:low,medium,low',
        ]); 

        $task->update($validatedData);//  Updates the task found

        return response()->json($task, 200);
    }

    // 5. DELETE /api/tasks/{id} 
    // Triggered when React wants to DELETE a specific task
    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);

        $task->delete();

        return response()->json(null, 204);
    }
}
