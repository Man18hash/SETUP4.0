<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReleasedProjectAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'released_project_id',
        'file_name',
        'file_path',
        'file_type',
    ];

    public function releasedProject()
    {
        return $this->belongsTo(ReleasedProject::class);
    }
}

