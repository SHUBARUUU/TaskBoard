php artisan tinker -> Laravel built-in fake data generator
Example CLI: User::factory()->create();

Command Flow{
User:: - Tells Laravel to look at your User model.

    factory() - Tells Laravel to open the "blueprint" for a fake user. (If you want to see this blueprint, look inside database/factories/UserFactory.php. You will see it uses a library called Faker to generate a random name, a fake email, and a hashed password).

    create() - Takes that fake data, writes the INSERT INTO users... SQL query, and actually saves it into your MySQL database.

}

## 📝 API HTTP Status Codes Cheat Sheet

### ✅ Success Codes (2xx) - "Everything worked perfectly"

| Code               | Meaning                                  | Used For                           |
| :----------------- | :--------------------------------------- | :--------------------------------- |
| **200 OK**         | Request succeeded, here is your data.    | `GET` (index/show), `PUT` (update) |
| **201 Created**    | Successfully inserted a new record.      | `POST` (store)                     |
| **204 No Content** | Successfully deleted, no data to return. | `DELETE` (destroy)                 |

### ⚠️ Client Errors (4xx) - "The Frontend sent bad data"

| Code                  | Meaning                            | Laravel Cause                          |
| :-------------------- | :--------------------------------- | :------------------------------------- |
| **401 Unauthorized**  | Missing or invalid login token.    | Sanctum blocked the request.           |
| **403 Forbidden**     | Logged in, but lacking permission. | Trying to edit someone else's task.    |
| **404 Not Found**     | The requested ID doesn't exist.    | `findOrFail()` didn't find the record. |
| **422 Unprocessable** | Data validation failed.            | Forgot a required field (e.g., title). |

### 🚨 Server Errors (5xx) - "The Backend crashed"

| Code                 | Meaning               | Laravel Cause                           |
| :------------------- | :-------------------- | :-------------------------------------- |
| **500 Server Error** | Total system failure. | Typo in controller, DB connection down. |

Integrating Tailwind Framework for the overall website feels:
npm install tailwindcss @tailwindcss/vite - Run this npm command to install tailwind for your frontend, after since we installed this after setting up the react, we also need to configure in vite.config.js and add this '@import "tailwindcss";' command in App.css, or any where in src as long as we are getting that command we have to import to use that tool.

## Command Breakdown (`npm create vite@latest . -- --template react`)

- `npm`: Uses Node.js to fetch packages.
- `create vite@latest .`: Bootstraps the Vite compiler and dev server inside the current folder.
- `-- --template react`: Pre-installs the React library and hooks up the `@vitejs/plugin-react` compiler plugin.
  _(Note: You cannot run React `.jsx` files without a compiler like Vite, Webpack, or Next.js because standard browsers don't recognize JSX syntax natively)._

## Tailwind CSS Integration (`@tailwindcss/vite`)

- Installed via: `npm install tailwindcss @tailwindcss/vite`
- Configured in `vite.config.js` by adding the plugin into the array alongside React, allowing Vite to compile both React components and Tailwind utility classes simultaneously:

  ```javascript
  import tailwindcss from "@tailwindcss/vite";
  import react from "@vitejs/plugin-react";
  import { defineConfig } from "vite";

  export default defineConfig({
    plugins: [react(), tailwindcss()],
  });

  ## 🎨 CSS Styling & Tailwind Architecture
  ```

### Tailwind Utility Classes vs. Traditional CSS Grouping

- **Utility-First Approach:** Applying individual styling rules directly inline via `className` (e.g., `bg-[#222138] border p-4`) for rapid interface building.
- **The Grouping Dilemma:** Writing long strings of utility classes repeatedly across multiple elements leads to code duplication.
- **Tailwind `@apply` Directive (`src/App.css`):** Allows you to bridge traditional CSS grouping with Tailwind by bundling utilities into custom class names:
  ```css
  @layer components {
    .task-card {
      @apply bg-[#222138] border border-[#3b3a54] hover:border-[#6f55f6] rounded-xl p-4 transition shadow-md;
    }
  }
  ```
  ```html
  {/* 2. Use it cleanly in your component */}
  <div className="task-card">
    <h3>Do the dishes</h3>
  </div>
  <div className="task-card">
    <h3>Write code</h3>
  </div>
  ```

This project will also tackle Controller/Service/Repository Layers for backend.
Controller -> the entry point of the data, validates and asks for layers to help.
Service -> the business logic, this handles the programs logic when needed.
Repository -> The one that literally talks to the database.
