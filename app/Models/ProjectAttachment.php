<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'file_name',
        'file_path',
        'file_type',
    ];

    /**
     * The project that this attachment belongs to.
     */
    public function project()
    {
        return $this->belongsTo(\App\Models\Project::class, 'project_id', 'id');
    }
}
