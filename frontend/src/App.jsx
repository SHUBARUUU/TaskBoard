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

  const handleUpdate = (task) => {
    // 1. Pop up a box asking for the new title. It defaults to the current title.
    const newTitle = prompt("Enter the new title:", task.title);

    // If the user clicks "Cancel" or leaves it blank, stop the function immediately.
    if (!newTitle) return;

    // 2. The Delivery Truck (PUT)
    fetch(`http://localhost:8000/api/tasks/${task.id}`, {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      // 3. The Packing Machine:
      // Pack the brand NEW title, but pack the OLD description and status
      // so Laravel doesn't accidentally erase them!
      body: JSON.stringify({
        title: newTitle,
        description: task.description,
        status: task.status,
      }),
    })
      .then((response) => response.json()) // 4. Unpack the fresh box from Laravel
      .then((updatedTask) => {
        // 5. The Factory Inspector (.map)
        // Swap the old task on the screen with the newly updated one
        setTasks(tasks.map((t) => (t.id === task.id ? updatedTask : t)));
        //? Ask for explaination for the next coding sesh
      })
      .catch((error) => console.error("Error updating title:", error));
  };

  return (
    <div>
      <h1>My Task Board</h1>

      {/* Managing a single data. We lock the value, then using the onChange everytime we change the value of input field the state of the newTask also changes. */}
      {/* <input
        type="text"
        placeholder="Enter task"
        //* Permanently locks the value of the input field, only gets the value from useState
        value={newTask}
        //* onChange helps by makin an event whenever we press the input field then using the awakener called "setNewTask" and getting the modified value
        onChange={(e) => setNewTask(e.target.value)}
      /> */}

      {/* Managing an object. We lock the object attribute, then using the onChange we have to re-set up the object w its old data, then change the specific attribute that needed to be change.  */}
      <input
        type="text"
        placeholder="Enter task title"
        //* Permanently locks the value of the input field, only gets the value from useState
        value={newTask.title}
        //* onChange helps by makin an event whenever we press the input field then using the awakener called "setNewTask" and getting the modified value
        onChange={(e) => setNewTask({ ...newTask, title: e.target.value })}
      />
      <input
        type="text"
        placeholder="Enter task description"
        value={newTask.description}
        onChange={(e) =>
          setNewTask({ ...newTask, description: e.target.value })
        }
      />
      <button onClick={handleCreate}>Add Task</button>

      <p>Loaded {tasks.length} tasks from database</p>

      {/* Needs to use mapping so we can iterate or get data one at a time, displaying it. 
        The use of .map()*/}
      {/* .map() built in iterable nullifying the manual use of loops.*/}
      {tasks.map((task) => (
        <div>
          <span>{task.title}</span>
          <span> - {task.description}</span>
          <span> - {task.status}</span>
          <span> - {task.priority}</span>
          {/* The Delete button specifically for THIS task */}
          <button onClick={() => handleDelete(task.id)}>Delete</button>
          <button onClick={() => handleUpdate(task)}>Update</button>
        </div>
      ))}
    </div>
  );
}

export default App;
