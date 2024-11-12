<?php

namespace App\Models\erecruitment\table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DtlFormA4 extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'mysql-erec'; 
    public $table = "dtl_form_a4"; 

    protected $fillable = [
        'id_form_a4',
        'dimension',
        'key_explanation',
        'total_aspects_score',
    ];

    public function form()
    {
        return $this->belongsTo(MsFormA4::class, 'id_form_a4', 'id_form_a4');
    }
}