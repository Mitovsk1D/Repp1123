<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentData extends Model
{
    use HasFactory;

    protected $table = 'student_data'; // Explicitly defining the table name

    protected $fillable = [
        'gender',
        'birth_date',
        'school_year',
        'field_of_study',
        'current_school'
    ];

    public $timestamps = true; // Laravel will handle created_at & updated_at automatically
}
