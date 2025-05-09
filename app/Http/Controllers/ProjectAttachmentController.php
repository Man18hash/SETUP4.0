<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectAttachment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProjectAttachmentController extends Controller
{
    /**
     * Store a new attachment for a project.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'attachment' => 'required|file|max:10240', // limit file size to 10MB, adjust as needed
        ]);

        // Retrieve the project.
        $project = Project::findOrFail($validated['project_id']);

        // Determine a folder name using a slug of the project title.
        $folder = 'project_attachments/' . Str::slug($project->title);

        // Store the uploaded file into the folder.
        $file = $request->file('attachment');
        $path = $file->store($folder);

        // Create a new attachment record.
        ProjectAttachment::create([
            'project_id' => $project->id,
            'file_name'  => $file->getClientOriginalName(),
            'file_path'  => $path,
            'file_type'  => $file->getClientMimeType(),
        ]);

        return redirect()->back()->with('success', 'Attachment uploaded successfully!');
    }

    /**
     * Download the specified attachment.
     */
    public function download($id)
    {
        $attachment = ProjectAttachment::findOrFail($id);
        return Storage::download($attachment->file_path, $attachment->file_name);
    }
}
