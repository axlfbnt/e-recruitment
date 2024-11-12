<?php

namespace App\Models\erecruitment\table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsFunction extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql-erec';
    public $table = "ms_function";
    protected $fillable = [
        'id',
        "function",
        "description",
    ];
}