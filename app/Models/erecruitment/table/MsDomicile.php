<?php

namespace App\Models\erecruitment\table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsDomicile extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql-erec';
    public $table = "ms_domicile";
    protected $fillable = [
        'id',
        "name",
    ];
}