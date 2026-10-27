/* Hooks:
 * useState -> React's way of remembering things (memory)
 * useEffect -> React's way of doing things automatically when a page loads(action)
 */
import { useState, useEffect } from "react";
import "./App.css";

function App() {
  /*
   * tasks: Actual variable that holds your data.
   * setTasks: Special function of react. You are never allowed to do tasks = newData. You must use setTasks(newData) so React knows
   *   the data changed and can automatically redraw the screen.
   * useState([]): Telling React that tasks, should start as an empty array.
   */
  //* Additional info: Array Destructuring
  const [tasks, setTasks] = useState([]);

  /*
   * API calls directly inside a React component will result in an infinite loop, crashing your broswer and spamming server 1000x a sec.
   * Using useEffect, and adding empty array at the end gives rule -> Only run this code EXACTLY ONE TIME, the moment page loads.
   */
  //* Additional info: Dependency array/Watch list.
  useEffect(() => {
    fetch("http://localhost:8000/api/tasks")
      .then((response) => response.json())
      .then((data) => {
        //* Prints in console the data from the database
        console.log("Here's your data: ", data);
        setTasks(data);
      })
      .catch((error) => console.error("Error fetching tasks:", error));
  }, []);

  //* newTask as a single data. Uses a single string as a default value.
  // const [newTask, setNewTask] = useState("");

  //* newTask as an object. Uses object with multiple attributes w their own default values
  const [newTask, setNewTask] = useState({ title: "", description: "" });

  const handleCreate = () => {
    // 1. Send POST request to laravel
    fetch("http://localhost:8000/api/tasks", {
      method: "POST", // The action/ What to do
      headers: { "Content-Type": "application/json" }, // The shipping label & id badge of the package

      //* Package the new task into the JSON box Laravel expects
      body: JSON.stringify({
        title: newTask.title,
        description: newTask.description,
        status: "pending",
      }),
    })
      .then((response) => response.json())
      .then((createdTask) => {
        /* 2. MAGIC TRICK:
         * Grab the existing tasks shelf (...tasks), drop the new task at the end, and
         * instantly redraw the screen
         */
        //* Spread Operator: Dump all existing task from old array, put it inside a new array alongside a new task.
        setTasks([...tasks, createdTask]);

        // 3. Clear the input box so they can type another one
        setNewTask({ title: "", description: "" });
      })
      .catch((error) => console.error("Error creating task:", error));
  };

  const handleDelete = (id) => {
    /*  When you send a response from laravel it unpacks the data that was sent alongside a message.
        For DELETE methods, it produces an error if we try to unpack a null data. So response is optionally can be place.
        However, we still need to get the message to ensure deletion.
    */
    //*  Deleting happens in the fetch side
    fetch(`http://localhost:8000/api/tasks/${id}`, {
      method: "DELETE",
    })
      .then((response) => {
        if (response.ok) {
          //* After deletion we set the tasks list by getting only the tasks that aren't equal to the id.
          setTasks(tasks.filter((task) => task.id !== id));
        } else {
          console.error("Backend/Laravel refused to delete the task!");
        }
      })
      .catch((error) => console.error("Network error:", error));
  };

  // This remembers exactly WHICH task we are currently editing (by its ID)
  const [editingId, setEditingId] = useState(null);

  // 1. Triggered when you click "Edit"
  const handleEditClick = (task) => {
    setEditingId(task.id); // Tell React this specific task is in "Edit Mode"

    // Pre-fill the input boxes with the task's current data
    setEditForm({
      title: task.title,
      description: task.description,
    });
  };

  // This remembers the text we are typing into the edit boxes
  const [editForm, setEditForm] = useState({ title: "", description: "" });

  const handleUpdate = (task) => {
    fetch(`http://localhost:8000/api/tasks/${task.id}`, {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        title: editForm.title,
        description: editForm.description,
        status: editForm.status,
      }),
    })
      .then((response) => response.json()) // 4. Unpack the fresh box from Laravel
      .then((updatedTask) => {
        // 5. The Factory Inspector (.map)
        // Swap the old task on the screen with the newly updated one
        setTasks(tasks.map((t) => (t.id === task.id ? updatedTask : t)));
        //* .map() returns an array. This block of code determines if the current task iterating, is the task that we update, if so we change it if not still use the same object (t).

        setEditingId(null);
      })
      .catch((error) => console.error("Error updating title:", error));
  };

  return (
    <div className="min-h-screen bg-[#17162b] text-white flex justify-center py-10 px-5 font-sans">
      <div className="w-full max-w-2xl">
        {/* --- CENTERED HEADER --- */}
        <div className="text-center mb-8">
          <h1
            className="text-4xl font-extrabold tracking-tight mb-4 drop-shadow-md"
            style={{
              background:
                "linear-gradient(to right, #ffffff, #9d8aff, #6f55f6)",
              WebkitBackgroundClip: "text",
              WebkitTextFillColor: "transparent",
            }}
          >
            MY TASKBOARD
          </h1>
        </div>

        {/* --- CREATE TASK FORM CONTAINER --- */}
        <div className="flex gap-3 bg-[#222138] p-4 rounded-xl border border-[#3b3a54] mb-6 shadow-lg">
          <input
            className="flex-1 bg-[#17162b] border border-[#3b3a54] text-white px-3.5 py-2.5 rounded-lg text-sm outline-none focus:border-[#6f55f6] placeholder-[#a1a0bd]"
            type="text"
            placeholder="Enter task title"
            value={newTask.title}
            onChange={(e) => setNewTask({ ...newTask, title: e.target.value })}
          />
          <input
            className="flex-1 bg-[#17162b] border border-[#3b3a54] text-white px-3.5 py-2.5 rounded-lg text-sm outline-none focus:border-[#6f55f6] placeholder-[#a1a0bd]"
            type="text"
            placeholder="Enter task description"
            value={newTask.description}
            onChange={(e) =>
              setNewTask({ ...newTask, description: e.target.value })
            }
          />
          <button
            className="bg-[#6f55f6] hover:bg-[#5b41e0] text-white px-5 py-2.5 rounded-lg text-sm font-semibold cursor-pointer transition shadow-md"
            onClick={handleCreate}
          >
            Add Task
          </button>
        </div>

        {/* --- CENTERED & SPACED TASK COUNTER (Right after form, before list) --- */}
        <div className="text-center mb-6">
          <p className="inline-flex items-center gap-2 text-[#a1a0bd] text-sm font-medium bg-[#222138] px-4 py-1.5 rounded-full border border-[#3b3a54] shadow-inner">
            <span className="w-2 h-2 rounded-full bg-[#6f55f6] animate-pulse"></span>
            Loaded {tasks.length} tasks from database
          </p>
        </div>

        {/* --- TASK LIST --- */}
        <div className="flex flex-col gap-3">
          {tasks.map((task) => (
            <div
              key={task.id}
              className="bg-[#222138] border border-[#3b3a54] hover:border-[#6f55f6] rounded-xl p-4 transition shadow-md"
            >
              {editingId === task.id ? (
                /* --- EDIT MODE --- */
                <div className="flex justify-between items-center gap-4">
                  <div className="flex flex-1 gap-2.5">
                    <input
                      className="flex-1 bg-[#17162b] border border-[#3b3a54] text-white px-3 py-2 rounded-lg text-sm outline-none focus:border-[#6f55f6]"
                      value={editForm.title}
                      onChange={(e) =>
                        setEditForm({ ...editForm, title: e.target.value })
                      }
                    />
                    <input
                      className="flex-1 bg-[#17162b] border border-[#3b3a54] text-white px-3 py-2 rounded-lg text-sm outline-none focus:border-[#6f55f6]"
                      value={editForm.description}
                      onChange={(e) =>
                        setEditForm({
                          ...editForm,
                          description: e.target.value,
                        })
                      }
                    />
                  </div>
                  <div className="flex gap-2">
                    <button
                      className="bg-[#6f55f6] hover:bg-[#5b41e0] text-white px-4 py-2 rounded-lg text-xs font-semibold cursor-pointer transition"
                      onClick={() => handleUpdate(task)}
                    >
                      Save
                    </button>
                    <button
                      className="border border-[#3b3a54] hover:border-[#a1a0bd] text-[#a1a0bd] hover:text-white px-4 py-2 rounded-lg text-xs font-semibold cursor-pointer transition"
                      onClick={() => setEditingId(null)}
                    >
                      Cancel
                    </button>
                  </div>
                </div>
              ) : (
                /* --- VIEW MODE --- */
                <div className="flex justify-between items-center gap-4">
                  <div className="flex flex-col gap-1">
                    <h3 className="text-base font-semibold text-white">
                      {task.title}
                    </h3>
                    <p className="text-sm text-[#a1a0bd]">{task.description}</p>
                    <div className="flex gap-2 mt-1">
                      <span className="text-[11px] font-semibold uppercase px-2.5 py-1 rounded-full bg-[#6f55f6]/20 text-[#9d8aff] border border-[#6f55f6]/40">
                        {task.status}
                      </span>
                      {task.priority && (
                        <span className="text-[11px] font-semibold uppercase px-2.5 py-1 rounded-full bg-[#a1a0bd]/10 text-[#a1a0bd] border border-[#3b3a54]">
                          {task.priority}
                        </span>
                      )}
                    </div>
                  </div>

                  <div className="flex gap-2">
                    <button
                      className="border border-[#3b3a54] hover:border-[#a1a0bd] text-[#a1a0bd] hover:text-white px-4 py-2 rounded-lg text-xs font-semibold cursor-pointer transition"
                      onClick={() => handleEditClick(task)}
                    >
                      Edit
                    </button>
                    <button
                      className="bg-[#ff4d4d]/15 hover:bg-[#ff4d4d] text-[#ff4d4d] hover:text-white border border-[#ff4d4d]/30 px-4 py-2 rounded-lg text-xs font-semibold cursor-pointer transition"
                      onClick={() => handleDelete(task.id)}
                    >
                      Delete
                    </button>
                  </div>
                </div>
              )}
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}

export default App;
