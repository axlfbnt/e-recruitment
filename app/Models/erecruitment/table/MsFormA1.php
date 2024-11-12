<?php

namespace App\Models\erecruitment\table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MsFormA1 extends Model
{
    use SoftDeletes;
    use HasFactory;
    
    protected $connection = 'mysql-erec';
    public $table = "ms_form_a1";
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
        "a1_status",
        "rejection_statement",
        "created_by",
        "updated_by",
    ];
}