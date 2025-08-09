<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Project
 *
 * Represents a project entity within the application.
 *
 * @package App\Models
 *
 * @extends \Illuminate\Database\Eloquent\Model
 */
class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Get the tasks associated with the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Format the project data for frontend.
     *
     * @param \App\Models\Project $project
     * @return array
     */
    public static function formate(Project $project): array
    {
        return [
            'id'    => $project->id,
            'name'  => $project->name,
            'color' => self::generateColor($project->id),
        ];
    }

    /**
     * Generate a color based on the project ID.
     *
     * @param int $id
     * @return string
     */
    private static function generateColor(int $id): string
    {
        // Example: return one of several colors based on project ID
        $colors = ['#EF4444', '#F97316', '#3B82F6', '#10B981', '#6366F1'];
        return $colors[$id % count($colors)];
    }
}
