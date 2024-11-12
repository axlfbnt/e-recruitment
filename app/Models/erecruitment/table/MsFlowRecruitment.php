<?php

namespace App\Models\erecruitment\table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MsFlowRecruitment extends Model
{
    use SoftDeletes;
    use HasFactory;
    
    protected $connection = 'mysql-erec';
    public $table = "ms_flowrecruitment";
    protected $fillable = [
        'id_flowrecruitment',
        "template_name",
        "recruitment_stage",
        "created_by",
        "updated_by",
    ];
}