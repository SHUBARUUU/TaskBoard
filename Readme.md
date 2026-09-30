# TaskBoard

A small full-stack task management application built as a learning project.

The goal of this project is to practice the fundamentals of **backend development, frontend development, databases, APIs, Git, and Docker** by building one complete application from the ground up.

---

## Tech Stack

### Backend

- PHP
- Laravel
- REST API
- Eloquent ORM

### Frontend

- JavaScript
- React
- Vite
- npm

### Database

- MySQL

### DevOps

- Docker
- Docker Compose

### Development Tools

- Git
- VS Code

---

## Project Goals

This project is mainly for learning and practicing:

- Laravel fundamentals
- REST API development
- CRUD operations
- MySQL databases
- Database relationships
- Authentication and authorization
- API validation
- HTTP methods and status codes
- Frontend development with React
- npm and Vite
- Frontend-to-backend communication
- Environment variables
- Docker and containerization
- Git and Git branches
- Basic testing and debugging

---

## Application Overview

TaskBoard allows users to manage their own tasks.

Users will be able to:

- Create an account
- Log in and log out
- Create tasks
- View their tasks
- Edit tasks
- Delete tasks
- Mark tasks as completed
- Set task priorities
- Set due dates
- Search and filter tasks

Each user's tasks are associated with their account.

---

## Planned Architecture

```text
                    ┌───────────────┐
                    │    Browser    │
                    └───────┬───────┘
                            │
                            │ HTTP / JSON
                            ▼
                    ┌───────────────┐
                    │    React      │
                    │    Frontend   │
                    └───────┬───────┘
                            │
                            │ API Requests
                            ▼
                    ┌───────────────┐
                    │    Laravel    │
                    │     API       │
                    └───────┬───────┘
                            │
                            │ Eloquent
                            ▼
                    ┌───────────────┐
                    │     MySQL     │
                    └───────────────┘
```

Docker will eventually be used to run the different parts of the application in containers.

---

## Project Structure

The project is planned to use the following structure:

```text
TaskBoard/
│
├── backend/
│   └── Laravel application
│
├── frontend/
│   └── React + Vite application
│
├── docker/
│   └── Docker configuration
│
├── docker-compose.yml
└── README.md
```

The exact structure may change as the project develops.

---

# Backend

The backend is built with Laravel and is responsible for:

- Handling API requests
- Authenticating users
- Validating incoming data
- Performing database operations
- Managing task ownership
- Returning JSON responses

### Planned API Endpoints

#### Authentication

```text
POST   /api/register
POST   /api/login
POST   /api/logout
```

#### Tasks

```text
GET    /api/tasks
GET    /api/tasks/{id}
POST   /api/tasks
PUT    /api/tasks/{id}
DELETE /api/tasks/{id}
```

Additional endpoints may be added as the project develops.

---

# Database

The application will use MySQL.

## Users

Laravel's user system will be used for authentication.

```text
users
├── id
├── name
├── email
├── password
├── created_at
└── updated_at
```

## Tasks

```text
tasks
├── id
├── user_id
├── title
├── description
├── status
├── priority
├── due_date
├── created_at
└── updated_at
```

### Relationship

A user can have many tasks.

```text
User
 │
 └── hasMany
       │
       ├── Task
       ├── Task
       └── Task
```

Each task belongs to one user.

```text
Task
 │
 └── belongsTo
       │
       └── User
```

---

# Frontend

The frontend will use React with Vite.

It will be responsible for:

- Displaying the user interface
- Sending API requests
- Displaying API responses
- Handling forms
- Managing frontend state
- Showing errors
- Updating the interface when tasks change

Planned pages/components include:

```text
Login
Register
Dashboard
Task List
Task Form
Task Details
Edit Task
```

---

# Docker

Docker will be introduced after the application works locally.

The planned Docker setup will contain services such as:

```text
Docker
│
├── Laravel / PHP
├── MySQL
└── React / Node
```

Docker will be used to practice:

- Images
- Containers
- Dockerfiles
- Docker Compose
- Ports
- Volumes
- Container networking
- Environment variables
- Persistent database storage

---

# Development Roadmap

The project will be developed incrementally.

## Phase 1 — Laravel Foundation

- [ ] Create Laravel project
- [ ] Configure database
- [ ] Create Task model
- [ ] Create Task migration
- [ ] Create Task controller
- [ ] Implement basic CRUD API
- [ ] Test API endpoints

## Phase 2 — Authentication

- [ ] Create registration
- [ ] Create login
- [ ] Create logout
- [ ] Protect task endpoints
- [ ] Connect users to tasks
- [ ] Implement authorization

## Phase 3 — API Improvements

- [ ] Add request validation
- [ ] Add proper HTTP status codes
- [ ] Add consistent error responses
- [ ] Add searching
- [ ] Add filtering
- [ ] Add pagination

## Phase 4 — Frontend

- [ ] Create React/Vite project
- [ ] Learn npm workflow
- [ ] Create login page
- [ ] Create registration page
- [ ] Create dashboard
- [ ] Create task list
- [ ] Create task form
- [ ] Connect frontend to Laravel API

## Phase 5 — Docker

- [ ] Create Laravel Docker setup
- [ ] Create MySQL container
- [ ] Create frontend container
- [ ] Configure Docker Compose
- [ ] Configure container networking
- [ ] Configure persistent database storage
- [ ] Run the complete application through Docker

## Phase 6 — Testing & Cleanup

- [ ] Test API endpoints
- [ ] Test authentication
- [ ] Test task ownership
- [ ] Test validation
- [ ] Fix bugs
- [ ] Clean up project structure
- [ ] Improve documentation

---

# Git Workflow

Git will be used throughout development.

Feature branches can be used for individual features:

```text
main
│
├── feature/auth
├── feature/tasks-api
├── feature/task-ui
├── feature/search
└── feature/docker
```

Example workflow:

```bash
git switch -c feature/tasks-api

# Make changes

git add .
git commit -m "Implement task CRUD API"

git switch main
git merge feature/tasks-api
```

The exact Git workflow may change as the project develops.

---

# Learning Approach

This project is intentionally being built incrementally.

The goal is **not** to copy a complete tutorial.

Each feature should be understood before moving to the next one.

For example:

```text
Requirement
     ↓
Try implementing it
     ↓
Run into a problem
     ↓
Investigate / debug
     ↓
Understand the solution
     ↓
Implement it
     ↓
Test it
```

The project should prioritize understanding **why** something works rather than simply memorizing framework syntax.

---

# Current Status

🚧 **In Development**

The project is currently being built as a learning exercise.

Features will be added progressively as the fundamentals are learned.

---

## Future Improvements

Possible features that may be added later:

- Task categories
- Task sorting
- Task pagination
- Task statistics
- User profile
- Dark mode
- Notifications
- Admin functionality
- Automated tests
- API documentation
- Production deployment

These features are intentionally not part of the initial scope.

The primary goal is to understand the fundamentals before adding complexity.

---

## Author

Built as a personal full-stack development learning project.
