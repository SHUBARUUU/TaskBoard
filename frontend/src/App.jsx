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
      .then((data) => setTasks(data))
      .catch((error) => console.error("Error fetching tasks:", error));
  }, []);

  return (
    <div>
      <h1>My Task Board</h1>
      <p>Loaded {tasks.length} tasks from database</p>
    </div>
  );

  // const [newTask, setNewTask] = useState("");
}

export default App;
