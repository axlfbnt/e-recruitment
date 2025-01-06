<?php

namespace App\Models\erecruitment\view;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VwManPowerSummary extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql-erec';
    public $table = "vw_manpower_summary";
}