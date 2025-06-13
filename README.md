<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# Backend My Tasks (Building)

## Glossary

-   **API**: Application Programming Interface
-   **Laravel**: A web application framework with expressive, elegant syntax

## Goals

-   Create a REST API for a task management application.
-   Implement tasks management.

## Prerequisites

-   PHP 8.2 or higher
-   MySQL 8.0 or higher
-   Composer 2.0 or higher

### Installation

1. Clone the repository
2. Run `composer install`
3. Copy the `.env.example` file to `.env`
4. Run `php artisan key:generate`
5. Run `php artisan migrate`
6. Run `php artisan serve`

## API Endpoints

### Authentication

-   **Register**: POST /api/register
-   **Login**: POST /api/login

### Categories

-   **Get all categories**: GET /categories
-   **Get category by id**: GET /categories/{category}
-   **Create**: POST /categories
-   **Update**: PUT /categories/{category}
-   **Delete**: DELETE /categories/{category}

### Example

```json
{
    "message": "Categories retrieved successfully",
    "status": 200,
    "data": [
        /* Category objects */
    ]
}
```

### Tasks

-   **Get all tasks**: GET /tasks/{category}
-   **Get task by id**: GET /tasks/{task}/{category}
-   **Create**: POST /tasks/{category}
-   **Update**: PUT /tasks/{task}/{category}
-   **Delete**: DELETE /tasks/{task}/{category}

### Example

```json
{
    "message": "Tasks retrieved successfully",
    "status": 200,
    "data": [
        /* Task objects */
    ]
}
```

## Documentation

For detailed API documentation, please refer to the [API Documentation](https://tasks-back.wpcache.es/api/documentation).

## Live Demo

-   **Frontend:** [https://tasks-front.wpcache.es/](https://tasks-front.wpcache.es/)
-   **Backend:** [https://tasks-back.wpcache.es/](https://tasks-back.wpcache.es/)

## License

This project is open-source.

## Next Steps

You can now start using API My Tasks freely.
