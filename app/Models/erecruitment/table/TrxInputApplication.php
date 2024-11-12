<?php

namespace App\Models\erecruitment\table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxInputApplication extends Model
{
    use HasFactory;

    protected $connection = 'mysql-erec';
    public $table = "trx_inputapplication";
    protected $fillable = [
        'company',
        'vacancy',
        'full_name',
        'email',
        'birth_date',
        'gender',
        'domicile',
        'phone_number',
        'years_experience',
        'months_experience',
        'expected_salary',
        'photo_path',
        'cv_path',
        'applicant_status',
        'last_stage',
        'administrative_status',
        'psychological_status',
        'interview_status',
        'offering_status',
        'mcu_status',
        'invite_status',
        'invite_stage',
        'invite_vacancy',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function education()
    {
        return $this->hasMany(DtlEducation::class, 'inputapplication_id');
    }
    
    public function organization()
    {
        return $this->hasMany(DtlOrganization::class, 'inputapplication_id');
    }
    
    public function internship()
    {
        return $this->hasMany(DtlInternship::class, 'inputapplication_id');
    }
    
    public function jobExperience()
    {
        return $this->hasMany(DtlJobExperience::class, 'inputapplication_id');
    }
}