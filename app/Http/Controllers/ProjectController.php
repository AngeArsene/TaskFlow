<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Class ProjectController
 *
 * Handles HTTP requests related to project management, including
 * creating, updating, and deleting projects.
 *
 * @package App\Http\Controllers
 *
 * @extends App\Http\Controllers\Controller
 */
class ProjectController extends Controller
{

    /**
     * Store a newly created project in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate(['name' => 'required']);
        $project = Project::create($request->only('name'));

        return response()->json(self::formate($project)); // ✅ required for frontend update
    }

    /**
     * Remove the specified project from storage.
     *
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Project $project): JsonResponse
    {
        $project->delete();

        return response()->json(['status' => 'deleted']);
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
}
