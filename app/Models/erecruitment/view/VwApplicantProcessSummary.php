<?php

namespace App\Models\erecruitment\view;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VwApplicantProcessSummary extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql-erec';
    public $table = "vw_applicant_process_summary";
}