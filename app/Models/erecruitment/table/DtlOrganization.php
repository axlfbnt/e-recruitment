<?php

namespace App\Models\erecruitment\table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DtlOrganization extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql-erec';
    public $table = "dtl_organization";
    protected $fillable = [
        'id',
        'applicant_id',
        'organization_name',
        'scope',
        'title',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}