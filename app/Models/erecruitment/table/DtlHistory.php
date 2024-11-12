<?php

namespace App\Models\erecruitment\table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DtlHistory extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql-erec';
    public $table = "dtl_history";
    protected $fillable = [
        'inputapplication_id',
        'company',
        'vacancy',
        'last_stage',
        'status',
        'applied_date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}