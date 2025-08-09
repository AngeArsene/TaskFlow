<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Task
 *
 * Represents a task entity within the application.
 *
 * @package App\Models
 *
 * @extends \Illuminate\Database\Eloquent\Model
 */
class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'priority', 'completed', 'project_id'];

    /**
     * Get the project that owns the task.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the highest priority value for a given project.
     *
     * @param int $project_id The ID of the project to retrieve the highest priority for.
     * @return int|null Returns the highest priority value as an integer, or null if no records exist.
    */
    public static function highestPriority(int $project_id): ?int
    {
        return self::where('project_id', $project_id)->max('priority');
    }

    /**
     * Format the task data for frontend.
     *
     * @param \App\Models\Task $task
     * @return array
     */
    public static function formate (Task $task): array
    {
        return [
            'id'          => $task->id,
            'name'        => $task->name,
            'priority'    => $task->priority,
            'completed'   => $task->completed,
            'projectId'   => $task->project_id,
            'createdAt'   => $task->created_at,
            'updatedAt'   => $task->updated_at,
            'completedAt' => $task->completed_at,
        ];
    }
}
