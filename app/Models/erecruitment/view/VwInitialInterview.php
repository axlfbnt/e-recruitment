<?php

namespace App\Models\erecruitment\view;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VwInitialInterview extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql-erec';
    public $table = "vw_initial_interview";
}