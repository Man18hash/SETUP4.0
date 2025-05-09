<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Beneficiary;
use App\Models\FundedProject;
use Illuminate\Support\Facades\DB;

class ProjectsController extends Controller
{
    /**
     * Display a listing of projects with search and filter options.
     */
    public function index(Request $request)
    {
        $query = Project::query();

        // Search by title, spin number, or objective.
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('spin_no', 'LIKE', "%{$search}%")
                  ->orWhere('objective', 'LIKE', "%{$search}%");
            });
        }

        // Filter by plan.
        if ($request->filled('filter_plan')) {
            $query->where('plan', $request->input('filter_plan'));
        }

        // Optionally filter by minimum and maximum amount.
        if ($request->filled('min_amount')) {
            $query->where('amount', '>=', $request->input('min_amount'));
        }
        if ($request->filled('max_amount')) {
            $query->where('amount', '<=', $request->input('max_amount'));
        }

        $projects = $query->get();
        $beneficiaries = Beneficiary::all();

        // Retrieve distinct plan_month values from the project_plan table
        $project_plans = DB::table('project_plan')
            ->select('plan_month')
            ->distinct()
            ->orderBy('plan_month', 'asc')
            ->get();

        return view('projects', compact('projects', 'beneficiaries', 'project_plans'));
    }

    /**
     * Store a new project record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'spin_no'         => 'required|string|max:255',
            'beneficiary_id'  => 'required|exists:beneficiary,id',
            'project_type'    => 'required|string',
            'objective'       => 'required|string',
            'amount'          => 'required|numeric',
            'plan'            => 'required|numeric',
            'status'          => 'required|string|in:Checking,Checked,Approved,Denied,Released',
            'released_date'   => 'nullable|date'
        ]);

        // If status is Released, use provided released_date or default to today.
        if ($validated['status'] === 'Released') {
            if (empty($validated['released_date'])) {
                $validated['released_date'] = now()->toDateString();
            }
        } else {
            $validated['released_date'] = null;
        }

        // Create the project
        $project = Project::create($validated);

        // If status is Released, save the project details in the funded_project table.
        $this->saveFundedProject($project);

        return redirect()->route('projects')->with('success', 'Project added successfully!');
    }

    /**
     * Show the form for editing a project.
     */
    public function edit(Project $project)
    {
        $beneficiaries = Beneficiary::all();
        return view('edit_project', compact('project', 'beneficiaries'));
    }

    /**
     * Update the specified project.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'spin_no'         => 'required|string|max:255',
            'beneficiary_id'  => 'required|exists:beneficiary,id',
            'project_type'    => 'required|string',
            'objective'       => 'required|string',
            'amount'          => 'required|numeric',
            'plan'            => 'required|numeric',
            'status'          => 'required|string|in:Checking,Checked,Approved,Denied,Released',
            'released_date'   => 'nullable|date'
        ]);

        // If status is Released, use provided released_date or default to today.
        if ($validated['status'] === 'Released') {
            if (empty($validated['released_date'])) {
                $validated['released_date'] = now()->toDateString();
            }
        } else {
            $validated['released_date'] = null;
        }

        // Update the project
        $project->update($validated);

        // If status is Released, update or create the funded project record.
        $this->saveFundedProject($project);

        return redirect()->route('projects')->with('success', 'Project updated successfully!');
    }

    /**
     * Save or update a record in the funded_project table if project status is Released.
     *
     * @param  \App\Models\Project  $project
     * @return void
     */
    protected function saveFundedProject(Project $project)
    {
        if ($project->status === 'Released') {
            // Get beneficiary details using the beneficiary_id.
            $beneficiary = Beneficiary::find($project->beneficiary_id);

            // Prepare data merging project and beneficiary details.
            $fundedData = [
                'title'         => $project->title,
                'spin_no'       => $project->spin_no,
                'project_type'  => $project->project_type,
                'objective'     => $project->objective,
                'amount'        => $project->amount,
                'plan'          => $project->plan,
                'status'        => $project->status,
                'released_date' => $project->released_date,
                // Beneficiary fields.
                'firmname'      => $beneficiary->firmname,
                'firstname'     => $beneficiary->firstname,
                'middlename'    => $beneficiary->middlename,
                'lastname'      => $beneficiary->lastname,
                'suffix'        => $beneficiary->suffix,
                'tel_no'        => $beneficiary->tel_no,
                'contact_no'    => $beneficiary->contact_no,
                'tin'           => $beneficiary->tin,
                'address'       => $beneficiary->address,
                'email'         => $beneficiary->email,
                'province'      => $beneficiary->province,
                'sector'        => $beneficiary->sector,
                'category'      => $beneficiary->category,
                'full_texts'    => $beneficiary->full_texts,
            ];

            // Use spin_no as a unique identifier to update or create the funded project record.
            $existing = FundedProject::where('spin_no', $project->spin_no)->first();
            if ($existing) {
                $existing->update($fundedData);
            } else {
                FundedProject::create($fundedData);
            }
        } else {
            // Optionally, if the status is no longer Released, you might want to remove the record.
            FundedProject::where('spin_no', $project->spin_no)->delete();
        }
    }
}
