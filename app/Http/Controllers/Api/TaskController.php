<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request, Project $project)
    {
        $tasks = $project->tasks()->with(['assignee', 'creator'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()->get();

        return response()->json(['tasks' => $tasks]);
    }

    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'in:todo,in_progress,done',
            'priority'    => 'in:low,medium,high',
            'due_date'    => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $task = Task::create([...$validated, 'project_id' => $project->id, 'created_by' => $request->user()->id]);

        return response()->json(['message' => 'Task created', 'task' => $task], 201);
    }

    public function show(Task $task)
    {
        $task->load(['assignee', 'creator', 'project']);
        return response()->json(['task' => $task]);
    }

    public function update(Request $request, Task $task)
    {
        $task->update($request->validate([
            'title'       => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'sometimes|in:todo,in_progress,done',
            'priority'    => 'sometimes|in:low,medium,high',
            'due_date'    => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]));

        return response()->json(['message' => 'Task updated', 'task' => $task]);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['message' => 'Task deleted']);
    }
}
