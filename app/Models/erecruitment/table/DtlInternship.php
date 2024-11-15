<?php

namespace App\Models\erecruitment\table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DtlInternship extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql-erec';
    public $table = "dtl_internship";
    protected $fillable = [
        'id',
        'applicant_id',
        'company_name',
        'function_role',
        'industry',
        'start_date',
        'end_date',
        'job_description',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}