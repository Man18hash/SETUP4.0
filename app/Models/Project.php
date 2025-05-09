<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    
    protected $table = 'projects';

    protected $fillable = [
        'title',
        'spin_no',
        'beneficiary_id',
        'project_type',
        'objective',
        'amount',
        'plan',
        'status',
        'released_date',
    ];
    
    /**
     * Get the beneficiary details associated with the project.
     */
    public function beneficiaryDetail()
    {
        return $this->belongsTo(\App\Models\Beneficiary::class, 'beneficiary_id', 'id');
    }
    public function attachments()
{
    return $this->hasMany(\App\Models\ProjectAttachment::class, 'project_id', 'id');
}
}
