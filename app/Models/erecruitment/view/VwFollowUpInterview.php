<?php

namespace App\Models\erecruitment\view;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VwFollowUpInterview extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql-erec';
    public $table = "vw_followup_interview";
}