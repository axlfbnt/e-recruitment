<?php

namespace App\Models\erecruitment\table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DtlEducation extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql-erec';
    public $table = "dtl_education";
    protected $fillable = [
        'id',
        'applicant_id',
        'degree',
        'institution',
        'major',
        'start_year',
        'graduated_year',
        'gpa',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}