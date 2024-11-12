<?php

namespace App\Models\erecruitment\table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DtlFormA3 extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'mysql-erec'; 
    public $table = "dtl_form_a3"; 

    protected $fillable = [
        'id_form_a3',
        'intelligence_aspect',
        'definition',
        'job_implication',
        'total_aspects_score',
    ];

    public function form()
    {
        return $this->belongsTo(MsFormA3::class, 'id_form_a3', 'id_form_a3');
    }
}