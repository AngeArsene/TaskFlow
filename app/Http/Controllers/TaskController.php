<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Task;
use Inertia\Response;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Class TaskController
 *
 * Handles HTTP requests related to task management, including
 * creating, updating, deleting tasks, and reordering them.
 *
 * @package App\Http\Controllers
 *
 * @extends App\Http\Controllers\Controller
 */
class TaskController extends Controller
{
    /**
     * Display a listing of the tasks and projects.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request): Response
    {
        $projects = Project::all()->map(fn ($project) => Project::formate($project));

        $tasks = Task::orderBy('priority')->get()->map(fn ($task) => Task::formate($task));

        return Inertia::render(
            'Welcome',
            [
                'tasks'           => $tasks,
                'projects'        => $projects,
                'selectedProject' => null
            ]
        );
    }

    /**
     * Store a newly created task in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'project_id' => 'required|exists:projects,id',
        ]);

        $task = Task::create([
            'name'       => $request->name,
            'priority'   => Task::highestPriority($request->project_id) + 1,
            'project_id' => $request->project_id,
        ]);

        return response()->json(Task::formate($task));
    }

    /**
     * Update the specified task in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'name' => 'required|string',
            'completed' => 'nullable|boolean',
            'completedAt' => 'nullable|string|date',
        ]);
        $task->update($request->only(['name', 'completed', 'completedAt']));

        return response()->json(Task::formate($task));
    }

    /**
     * Remove the specified task from storage.
     *
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json(['status' => 'deleted']);
    }

    /**
     * Reorder tasks based on the provided order.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reorder(Request $request): JsonResponse
    {
        foreach ($request->orderedTaskIds as $index => $id) {
            Task::where('id', $id)->update(['priority' => $index + 1]);
        }

        return response()->json(['status' => 'ok']);
    }
}
