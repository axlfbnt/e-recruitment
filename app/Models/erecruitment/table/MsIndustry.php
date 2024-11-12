<?php

namespace App\Models\erecruitment\table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsIndustry extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql-erec';
    public $table = "ms_industry";
    protected $fillable = [
        'id',
        "industry",
        "description",
    ];
}