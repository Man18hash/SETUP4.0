<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // For creating folder names
use Illuminate\Support\Facades\Storage;

class FundedProjectsController extends Controller
{
    public function index(Request $request)
    {
        // Capture the search term (if any)
        $searchTerm = $request->input('search');

        // Query only projects with status = 'Released'
        // and eager load the beneficiary relationship
        $projects = Project::with('beneficiaryDetail')
            ->where('status', 'Released')
            ->when($searchTerm, function ($query, $searchTerm) {
                $query->where(function($query) use ($searchTerm) {
                    // Search across project fields
                    $query->where('title', 'like', '%'.$searchTerm.'%')
                          ->orWhere('spin_no', 'like', '%'.$searchTerm.'%')
                          ->orWhere('project_type', 'like', '%'.$searchTerm.'%')
                          ->orWhere('objective', 'like', '%'.$searchTerm.'%')
                          // If you want to search in the 'amount' field, you can convert or just skip it
                          // ->orWhere('amount', 'like', '%'.$searchTerm.'%')

                          // Search across beneficiary fields using whereHas
                          ->orWhereHas('beneficiaryDetail', function($query) use ($searchTerm) {
                              $query->where('firmname', 'like', '%'.$searchTerm.'%')
                                    ->orWhere('firstname', 'like', '%'.$searchTerm.'%')
                                    ->orWhere('middlename', 'like', '%'.$searchTerm.'%')
                                    ->orWhere('lastname', 'like', '%'.$searchTerm.'%')
                                    ->orWhere('suffix', 'like', '%'.$searchTerm.'%')
                                    ->orWhere('tin', 'like', '%'.$searchTerm.'%')
                                    ->orWhere('address', 'like', '%'.$searchTerm.'%')
                                    ->orWhere('province', 'like', '%'.$searchTerm.'%')
                                    ->orWhere('tel_no', 'like', '%'.$searchTerm.'%')
                                    ->orWhere('contact_no', 'like', '%'.$searchTerm.'%')
                                    ->orWhere('sector', 'like', '%'.$searchTerm.'%')
                                    ->orWhere('category', 'like', '%'.$searchTerm.'%')
                                    ->orWhere('email', 'like', '%'.$searchTerm.'%')
                                    ->orWhere('full_texts', 'like', '%'.$searchTerm.'%');
                          });
                });
            })
            ->get();

        // Pass both the projects and the current search term to the view
        return view('funded-projects', [
            'projects' => $projects,
            'searchTerm' => $searchTerm,
        ]);
    }

    /**
     * Update the refund status of a project.
     */
    public function updateRefundStatus(Request $request, Project $project)
    {
        // Validate the new status
        $request->validate([
            'refund_status' => 'required|string|max:255',
        ]);

        // Suppose your 'projects' table has a 'refund_status' column
        // If not, create one or store it in some other place
        $project->refund_status = $request->input('refund_status');
        $project->save();

        return redirect()->back()->with('success', 'Refund status updated successfully!');
    }

    /**
     * Display a page or modal content for managing attachments.
     * For a full-page approach, you might return a dedicated view here.
     * If you're only using modals, you can skip this method or return partial data.
     */
    public function manageAttachments(Project $project)
    {
        // Return a dedicated "manage-attachments" view or partial
        // Example: resources/views/manage-attachments.blade.php (not included in this snippet)
        return view('manage-attachments', compact('project'));
    }

    /**
     * Store an uploaded attachment for a project in a folder named after the project.
     */
    public function storeAttachment(Request $request, Project $project)
    {
        // Validate the uploaded file
        $request->validate([
            'attachment' => 'required|file|max:5120', // max 5MB
        ]);

        // Create a folder name based on project title & ID
        // e.g. "project_24_castanedas_roofing"
        $folderName = Str::slug($project->title, '_') . '_' . $project->id;

        // Store the file in storage/app/public/projects/<folderName>
        // Make sure you have a "projects" folder in storage/app/public
        $path = $request->file('attachment')->store("projects/$folderName", 'public');

        // You can save the file info in your attachments table if you like
        // Example: ProjectAttachment::create([...])

        return redirect()->back()->with('success', 'File uploaded successfully!');
    }
}
