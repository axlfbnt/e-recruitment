<?php

namespace App\Models\erecruitment\table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MsJobVacancy extends Model
{
    use SoftDeletes;
    use HasFactory;
    
    protected $connection = 'mysql-erec';
    public $table = "ms_jobvacancy";
    protected $fillable = [
        'id_jobvacancy',
        "position",
        "position_status",
        "job_position",
        "join_date",
        "number_requests",
        "last_education",
        "source_submission",
        "job_desc",
        "required_skills",
        "range_ipk",
        "open_date",
        "close_date",
        "open_publication_date",
        "close_publication_date",
        "flow_recruitment",
        "vacancy_status",
        "status",
        "created_by",
        "updated_by",
    ];
}