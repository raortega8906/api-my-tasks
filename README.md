<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Backend My Tasks (Building)

## Glossary

- **API**: Application Programming Interface
- **Laravel**: A web application framework with expressive, elegant syntax

## Goals

- Create a REST API for a task management application.
- Implement tasks management.

## Prerequisites

- PHP 8.2 or higher
- MySQL 8.0 or higher
- Composer 2.0 or higher

### Installation

1. Clone the repository
2. Run `composer install`
3. Copy the `.env.example` file to `.env`
4. Run `php artisan key:generate`
5. Run `php artisan migrate`
6. Run `php artisan serve`

### Authentication

- **Register**: POST /api/register
- **Login**: POST /api/login

### Tasks

- **Get all tasks**: GET /api/tasks
- **Get task by id**: GET /api/tasks/{id}
- **Create**: POST /api/tasks
- **Update**: PUT /api/tasks/{id}
- **Delete**: DELETE /api/tasks/{id}

### Example

```json
{
    "message": "Tasks retrieved successfully",
    "status": 200,
    "data": [/* Task objects */]
}
```

## Live Demo
Frontend: [https://tasks-front.wpcache.es/](https://tasks-front.wpcache.es/)
Backend: [https://tasks-back.wpcache.es/](https://tasks-back.wpcache.es/)

## License
This project is open-source.

## Next Steps
You can now start using API My Tasks freely.

