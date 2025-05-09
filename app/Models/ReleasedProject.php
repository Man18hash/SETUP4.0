<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReleasedProject extends Model
{
    use HasFactory;

    protected $table = 'funded_project';

    protected $fillable = [
        'title',
        'spin_no',
        'project_type',
        'objective',
        'amount',
        'plan',
        'status',
        'released_date',
        'firmname',
        'firstname',
        'middlename',
        'lastname',
        'suffix',
        'tel_no',
        'contact_no',
        'tin',
        'address',
        'email',
        'province',
        'sector',
        'category',
        'full_texts'
    ];
}
