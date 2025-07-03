# ✅ Quality Management API – Graduation Project

This is the backend API for a full-stack Quality Management System (QMS) developed as a graduation project. The API handles user authentication, quality reports, document management, and internal communication between users and departments.

---

## 🚀 Features

- 🔐 Secure login and role-based authorization
- 📝 Create and manage quality reports
- 📁 Upload and retrieve documents
- 📨 Internal messaging between users
- 🧑‍💼 Admin-level control for departments and users
- 📊 API response in JSON format (RESTful)

---

## 🛠️ Tech Stack

- **Backend Framework:** Laravel 9.x (or pure PHP if applicable)  
- **Database:** MySQL  
- **Authentication:** Laravel Sanctum / Session-based  
- **Web Server:** Apache or Laravel built-in server

---

## 📦 Installation & Setup

1. Clone the repository:
   ```bash
   git clone https://github.com/Youssef-Gamal-1/Quality-management-api.git
   cd Quality-management-api
````

2. Install dependencies:

   ```bash
   composer install
   ```

3. Copy `.env` file and configure DB:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Set up database and run migrations:

   ```bash
   php artisan migrate
   ```

5. (Optional) Seed the database with sample data:

   ```bash
   php artisan db:seed
   ```

6. Start the local server:

   ```bash
   php artisan serve
   ```

---

## 🔗 API Endpoints Overview

| Method | Endpoint                   | Description           |
| ------ | -------------------------- | --------------------- |
| POST   | `/api/login`               | User login            |
| GET    | `/api/reports`             | List quality reports  |
| POST   | `/api/reports`             | Submit new report     |
| GET    | `/api/users/{id}/messages` | Get internal messages |
| ...    | More coming soon...        |                       |

ℹ️ Full API documentation coming soon.

---

## 📌 Notes

> This API was developed as part of a full-stack graduation project.
> It is currently under continuous improvement and may receive updates to the structure and database design.

---

## 👨‍💻 Related Project

The frontend interface for this system is available here:
🔗 [Quality-GUI Repo](https://github.com/Youssef-Gamal-1/Quality-GUI)

---

## 📃 License

This project is for **educational use only** and may be reused with attribution.
