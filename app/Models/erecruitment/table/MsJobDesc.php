<?php

namespace App\Models\erecruitment\table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MsJobDesc extends Model
{
    use SoftDeletes;
    use HasFactory;
    
    protected $connection = 'mysql-erec';
    public $table = "ms_jobdesc";
    protected $primaryKey = 'id_jobdesc';
    protected $fillable = [
        'id_jobdesc',
        "company",
        "department",
        "division",
        "position",
        "job_desc",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
        "deleted_at",
    ];
}