<?php

namespace App\Models\erecruitment\view;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VwMPPFormA1 extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql-erec';
    public $table = "vw_mpp_form_a1";
}