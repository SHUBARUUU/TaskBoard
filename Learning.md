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
