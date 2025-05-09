<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    use HasFactory;

    protected $table = 'beneficiary';

    protected $fillable = [
        'firmname',
        'firstname',
        'middlename',
        'lastname',
        'suffix',
        'tin',
        'address',
        'province',
        'tel_no',
        'contact_no',
        'sector',
        'category',
        'email',
        'full_texts',
    ];
}
