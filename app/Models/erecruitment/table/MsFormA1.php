<?php

namespace App\Models\erecruitment\table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MsFormA1 extends Model
{
    use SoftDeletes, HasFactory;

    protected $connection = 'mysql-erec';
    public $table = "ms_form_a1";

    protected $primaryKey = 'id_form_a1';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_form_a1',
        "department",
        "division",
        "due_date",
        "position",
        "direct_supervisor",
        "position_status",
        "job_position",
        "join_date",
        "number_requests",
        "source_submission",
        "job_desc",
        "last_education",
        "major",
        "gender",
        "marital_status",
        "personality_traits",
        "required_skills",
        "sla",
        "approved_dept_id",
        "approved_dept_name",
        "approved_dept_date",
        "approved_div_id",
        "approved_div_name",
        "approved_div_date",
        "approved_hr_id",
        "approved_hr_name",
        "approved_hr_date",
        "a1_status",
        "progress_recruitment",
        "psikotes",
        "interview_hc",
        "interview_user",
        "interview_bod",
        "mcu",
        "offering_letter",
        "closed",
        "closed_date",
        "rejection_statement",
        "created_by",
        "updated_by",
    ];
}