<?php

namespace App\Models\erecruitment\table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsInstitution extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql-erec';
    public $table = "ms_institution";
    protected $fillable = [
        'id',
        "name",
        "acronym",
    ];
}