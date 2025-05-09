<?php

namespace App\Http\Controllers;

use App\Models\ProjectPlan;
use Illuminate\Http\Request;

class ProjectPlanController extends Controller
{
    // Display a listing of the project plans.
    public function index()
    {
        $plans = ProjectPlan::all();
        return view('project_plans.index', compact('plans'));
    }

    // Show the form for creating a new project plan.
    public function create()
    {
        return view('project_plans.create');
    }

    // Store a newly created project plan in storage.
    public function store(Request $request)
    {
        $request->validate([
            'plan_month' => 'required|numeric',
        ]);

        ProjectPlan::create($request->only('plan_month'));

        return redirect()->route('project-plans.index')
                         ->with('success', 'Project plan created successfully.');
    }

    // Show the form for editing the specified project plan.
    public function edit($id)
    {
        $plan = ProjectPlan::findOrFail($id);
        return view('project_plans.edit', compact('plan'));
    }

    // Update the specified project plan in storage.
    public function update(Request $request, $id)
    {
        $request->validate([
            'plan_month' => 'required|numeric',
        ]);

        $plan = ProjectPlan::findOrFail($id);
        $plan->update($request->only('plan_month'));

        return redirect()->route('project-plans.index')
                         ->with('success', 'Project plan updated successfully.');
    }

    // Remove the specified project plan from storage.
    public function destroy($id)
    {
        $plan = ProjectPlan::findOrFail($id);
        $plan->delete();

        return redirect()->route('project-plans.index')
                         ->with('success', 'Project plan deleted successfully.');
    }
}
