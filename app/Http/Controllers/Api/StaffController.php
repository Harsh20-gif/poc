<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    /**
     * List all staff members.
     */
    public function index(Request $request)
    {
        $staff = Staff::query()
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->department, fn($q) => $q->where('department', $request->department))
            ->withCount('leads')
            ->latest()
            ->get();

        return response()->json([
            'total' => $staff->count(),
            'staff' => $staff->map(fn($s) => $this->format($s)),
        ]);
    }

    /**
     * Create a new staff member.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:staff,email',
            'phone'        => 'nullable|string|max:15',
            'designation'  => 'required|string|max:255',
            'department'   => 'required|string|max:255',
            'status'       => 'in:active,inactive',
            'joining_date' => 'nullable|date',
        ]);

        $staff = Staff::create($validated);

        return response()->json([
            'message' => 'Staff member added successfully',
            'staff'   => $this->format($staff),
        ], 201);
    }

    /**
     * Show a single staff member with their leads.
     */
    public function show(Staff $staff)
    {
        $staff->load('leads');

        return response()->json([
            'staff'       => $this->format($staff, true),
            'leads_count' => $staff->leads->count(),
            'leads'       => $staff->leads->map(fn($l) => [
                'id'           => $l->id,
                'company_name' => $l->company_name,
                'service_type' => $l->service_type,
                'status'       => $l->status,
                'follow_up_date' => $l->follow_up_date?->toDateString(),
            ]),
        ]);
    }

    /**
     * Update staff member details.
     */
    public function update(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'name'         => 'sometimes|string|max:255',
            'email'        => 'sometimes|email|unique:staff,email,' . $staff->id,
            'phone'        => 'nullable|string|max:15',
            'designation'  => 'sometimes|string|max:255',
            'department'   => 'sometimes|string|max:255',
            'status'       => 'sometimes|in:active,inactive',
            'joining_date' => 'nullable|date',
        ]);

        $staff->update($validated);

        return response()->json([
            'message' => 'Staff member updated successfully',
            'staff'   => $this->format($staff),
        ]);
    }

    /**
     * Delete a staff member.
     */
    public function destroy(Staff $staff)
    {
        if ($staff->leads()->whereNotIn('status', ['completed', 'lost'])->exists()) {
            return response()->json([
                'message' => 'Cannot delete staff with active leads. Please reassign leads first.',
            ], 422);
        }

        $staff->delete();

        return response()->json(['message' => 'Staff member removed successfully']);
    }

    private function format(Staff $staff, bool $full = false): array
    {
        return [
            'id'           => $staff->id,
            'name'         => $staff->name,
            'email'        => $staff->email,
            'phone'        => $staff->phone,
            'designation'  => $staff->designation,
            'department'   => $staff->department,
            'status'       => $staff->status,
            'joining_date' => $staff->joining_date?->toDateString(),
            'leads_count'  => $staff->leads_count ?? $staff->leads()->count(),
            'created_at'   => $staff->created_at->toDateTimeString(),
        ];
    }
}
