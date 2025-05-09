<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function upload(Request $request)
    {
        // Validate file input
        $request->validate([
            'project_id' => 'required',
            'attachment_file' => 'required|file',
        ]);

        // In a real app, fetch the project to get its title
        // For demonstration, assume we have the project title from input or lookup:
        $projectTitle = 'Project_' . $request->project_id; // Replace with actual lookup
        
        // Create a folder based on the project title
        $folder = 'attachments/' . $projectTitle;
        if (!Storage::exists($folder)) {
            Storage::makeDirectory($folder);
        }

        // Store the uploaded file in the folder
        $filePath = $request->file('attachment_file')->store($folder);

        // Save attachment details to the attachments table if needed

        return redirect()->back()->with('success', 'Attachment uploaded successfully.');
    }
}
