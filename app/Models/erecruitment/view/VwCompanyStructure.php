<?php

namespace App\Models\erecruitment\view;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VwCompanyStructure extends Model
{
    use HasFactory;
    protected $connection = 'mysql-erec';
    public $table = "vw_company_structure";

    protected $fillable = [
        'companyid',
        'company_name',
        'division',
        'department',
        'titles',
    ];
}