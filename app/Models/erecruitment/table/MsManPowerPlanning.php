<?php

namespace App\Models\erecruitment\table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MsManPowerPlanning extends Model
{
    use SoftDeletes;
    use HasFactory;
    
    protected $connection = 'mysql-erec';
    public $table = "ms_manpowerplanning";
    protected $primaryKey = 'id_mpp';
    protected $fillable = [
        'id_mpp',
        "company",
        "department",
        "division",
        "position",
        "is_mpp",
        "position_status",
        "source_submission",
        "job_position",
        "total_man_power",
        "last_education",
        "remarks",
        "entry_od_date",
        "approved_date",
        "due_date",
        "closed_date",
        "sla",
        "progress_recruitment",
        "psikotes",
        "interview_hc",
        "interview_user",
        "interview_bod",
        "mcu",
        "offering_letter",
        "closed",
        "man_power_status",
        "a1_status",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
        "deleted_at",
    ];
    protected $guarded = ['vendor'];
}