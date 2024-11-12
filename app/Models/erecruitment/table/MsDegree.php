<?php

namespace App\Models\erecruitment\table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsDegree extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql-erec';
    public $table = "ms_degree";
    protected $fillable = [
        'id',
        "degree",
        "description",
    ];
}