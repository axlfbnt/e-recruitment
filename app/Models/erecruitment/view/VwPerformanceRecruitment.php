<?php

namespace App\Models\erecruitment\view;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VwPerformanceRecruitment extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql-erec';
    public $table = "vw_recruitment_performance";
}