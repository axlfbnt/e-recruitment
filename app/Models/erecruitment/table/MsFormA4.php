<?php

namespace App\Models\erecruitment\table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MsFormA4 extends Model
{
    use HasFactory, SoftDeletes;

    protected $connection = 'mysql-erec'; 
    public $table = "ms_form_a4"; 
    protected $primaryKey = 'id_form_a4';

    protected $fillable = [
        'template_name',
        'status',
        'created_by',
        'updated_by',
    ];

    public function details()
    {
        return $this->hasMany(DtlFormA4::class, 'id_form_a4', 'id_form_a4');
    }
}