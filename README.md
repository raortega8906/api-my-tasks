<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Backend My Tasks

## Glossary

- **API**: Application Programming Interface
- **Laravel**: A web application framework with expressive, elegant syntax

## Goals

- Create a REST API for a task management application.
- Implement authentication using Sanctum.
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
    "name": "Task 1",
    "description": "Description 1",
    "status": "pending"
}
```

## License

My Tasks project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

