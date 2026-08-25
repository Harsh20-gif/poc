<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $user     = $request->user();
        $projects = $user->isAdmin()
            ? Project::with('owner')->withCount('tasks')->latest()->get()
            : Project::where('user_id', $user->id)->withCount('tasks')->latest()->get();

        return response()->json(['projects' => $projects]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'in:active,completed,archived',
        ]);

        $project = Project::create([...$validated, 'user_id' => $request->user()->id]);

        return response()->json(['message' => 'Project created', 'project' => $project], 201);
    }

    public function show(Request $request, Project $project)
    {
        $project->load(['tasks', 'owner']);
        return response()->json(['project' => $project]);
    }

    public function update(Request $request, Project $project)
    {
        $project->update($request->validate([
            'name'        => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'sometimes|in:active,completed,archived',
        ]));

        return response()->json(['message' => 'Project updated', 'project' => $project]);
    }

    public function destroy(Request $request, Project $project)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Admins only'], 403);
        }
        $project->delete();
        return response()->json(['message' => 'Project deleted']);
    }
}
