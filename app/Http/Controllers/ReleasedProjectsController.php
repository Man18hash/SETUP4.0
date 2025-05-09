<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReleasedProject;
use App\Models\ReleasedProjectAttachment;
use Illuminate\Support\Str;

class ReleasedProjectsController extends Controller
{
    /**
     * Display a listing of the released projects.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = ReleasedProject::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('spin_no', 'LIKE', "%{$search}%")
                    ->orWhere('objective', 'LIKE', "%{$search}%")
                    ->orWhere('firmname', 'LIKE', "%{$search}%");
            });
        }

        $projects = $query->orderBy('released_date', 'desc')->get();

        return view('released-projects', [
            'projects'   => $projects,
            'searchTerm' => $search,
        ]);
    }

    /**
     * Update the refund status for a given project.
     */
    public function updateRefundStatus(Request $request, $id)
    {
        $request->validate([
            'refund_status' => 'required|string'
        ]);

        $project = ReleasedProject::findOrFail($id);
        $project->refund_status = $request->input('refund_status');
        $project->save();

        return redirect()->route('released-projects')->with('success', 'Refund status updated successfully.');
    }

    /**
     * Store an attachment for a given project.
     */
    public function storeAttachment(Request $request, $id)
    {
        $request->validate([
            'attachment' => 'required|file|max:10240' // limit 10MB
        ]);

        $project = ReleasedProject::findOrFail($id);

        // Create a safe folder name (slug format)
        $folderName = 'attachments/' . Str::slug($project->title . ' ' . ($project->firmname ?? 'No Firmname'));
        
        // Handle file upload
        $file = $request->file('attachment');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs($folderName, $fileName, 'public');

        // Save the attachment details in the database
        ReleasedProjectAttachment::create([
            'released_project_id' => $project->id,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_type' => $file->getClientMimeType(),
        ]);

        return redirect()->route('released-projects')->with('success', 'Attachment uploaded successfully.');
    }

    /**
     * Retrieve attachments for a given project.
     */
    public function attachments($id)
    {
        $attachments = ReleasedProjectAttachment::where('released_project_id', $id)->get();
        return response()->json($attachments);
    }

    /**
     * Delete a project and related attachments.
     */
    public function destroy($id)
    {
        $project = ReleasedProject::findOrFail($id);

        // Delete attachments from storage and database
        foreach ($project->attachments as $attachment) {
            \Storage::disk('public')->delete($attachment->file_path);
            $attachment->delete();
        }

        $project->delete();

        return redirect()->route('released-projects')->with('success', 'Project deleted successfully.');
    }
}
