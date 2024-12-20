<?php

namespace App\Models\erecruitment\table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsVendor extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql-erec';
    public $table = "ms_vendor";
    protected $fillable = [
        "id",
        "vendor",
        "status",
        "position",
    ];
}