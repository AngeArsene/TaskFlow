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
        $request->validate(['name' => 'required|string']);
        $project = Project::create($request->only('name'));

        return response()->json(self::formate($project)); // ✅ required for frontend update
    }

    /**
     * Update the specified project in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Project $project): JsonResponse
    {
        $request->validate(['name' => 'required|string']);
        $project->update($request->only('name'));   // ✅ required for frontend update

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
}
