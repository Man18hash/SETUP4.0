<?php

namespace App\Http\Controllers;

use App\Models\ProjectPlan;
use Illuminate\Http\Request;

class SetupController extends Controller
{
    public function index()
    {
        $plans = ProjectPlan::all();
        return view('setup', compact('plans'));
    }

    public function store(Request $request)
    {
        // Validate the input
        $data = $request->validate([
            'plan_month' => 'required|numeric',
        ]);

        // Create a new ProjectPlan record
        ProjectPlan::create($data);

        // Redirect back with a success message
        return redirect()->route('project-plans.index')->with('success', 'Project plan added successfully.');
    }

    public function update(Request $request, $id)
    {
        // Validate the input
        $data = $request->validate([
            'plan_month' => 'required|numeric',
        ]);

        // Find and update the record
        $plan = ProjectPlan::findOrFail($id);
        $plan->update($data);

        return redirect()->route('project-plans.index')->with('success', 'Project plan updated successfully.');
    }

    public function destroy($id)
    {
        // Find and delete the record
        $plan = ProjectPlan::findOrFail($id);
        $plan->delete();

        return redirect()->route('project-plans.index')->with('success', 'Project plan deleted successfully.');
    }
}
