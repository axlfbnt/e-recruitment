<?php

namespace App\Models\erecruitment\table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DtlApplicantVacancy extends Model
{
    use HasFactory;

    protected $connection = 'mysql-erec';
    protected $table = "dtl_applicantvacancy";

    protected $fillable = [
        'id',
        'applicant_id',
        'company',
        'vacancy',
        'application_date',
        'expected_salary',
        'status',
        'last_stage',
        'administrative_status',
        'psychological_status',
        'interview_status',
        'offering_status',
        'mcu_status',
        'invite_status',
        'invite_stage',
        'invite_vacancy',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}