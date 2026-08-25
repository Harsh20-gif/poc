<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Project;
use App\Models\Staff;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'role' => $user->role,
            'crm'  => [
                'total_leads'   => Lead::count(),
                'total_staff'   => Staff::where('status', 'active')->count(),
                'leads_by_status' => [
                    'new'           => Lead::where('status', 'new')->count(),
                    'contacted'     => Lead::where('status', 'contacted')->count(),
                    'proposal_sent' => Lead::where('status', 'proposal_sent')->count(),
                    'in_progress'   => Lead::where('status', 'in_progress')->count(),
                    'completed'     => Lead::where('status', 'completed')->count(),
                    'lost'          => Lead::where('status', 'lost')->count(),
                ],
                'pipeline_value' => Lead::whereNotIn('status', ['completed', 'lost'])->sum('expected_value'),
                'won_value'      => Lead::where('status', 'completed')->sum('expected_value'),
                'by_service'     => Lead::selectRaw('service_type, count(*) as count')->groupBy('service_type')->get(),
            ],
            'projects' => [
                'total'     => Project::count(),
                'active'    => Project::where('status', 'active')->count(),
                'completed' => Project::where('status', 'completed')->count(),
            ],
            'tasks' => [
                'total'       => Task::count(),
                'todo'        => Task::where('status', 'todo')->count(),
                'in_progress' => Task::where('status', 'in_progress')->count(),
                'done'        => Task::where('status', 'done')->count(),
            ],
        ]);
    }
}
