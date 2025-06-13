<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Task",
 *     type="object",
 *     title="Tarea",
 *     required={"id", "title", "status", "category_id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Completar reporte mensual"),
 *     @OA\Property(property="description", type="string", example="Finalizar el reporte de ventas del mes"),
 *     @OA\Property(property="due_date", type="string", format="date", example="2024-12-31"),
 *     @OA\Property(property="priority", type="string", enum={"low", "medium", "high"}, example="high"),
 *     @OA\Property(property="status", type="string", enum={"pending", "completed"}, example="pending"),
 *     @OA\Property(property="category_id", type="integer", example=3),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-06-13T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-06-14T09:30:00Z"),
 * )
 */

class Task extends Model
{
    protected $fillable = [
        'title',
        'status',
        'category_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
