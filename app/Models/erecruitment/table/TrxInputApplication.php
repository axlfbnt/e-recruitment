<?php

namespace App\Models\erecruitment\table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxInputApplication extends Model
{
    use HasFactory;

    protected $connection = 'mysql-erec';
    public $table = "trx_inputapplication";
    protected $primaryKey = 'applicant_id';
    protected $fillable = [
        'applicant_id',
        'full_name',
        'email',
        'birth_date',
        'gender',
        'domicile',
        'phone_number',
        'years_experience',
        'months_experience',
        'photo_path',
        'cv_path',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function vacancyApplications()
    {
        return $this->hasMany(DtlApplicantVacancy::class, 'applicant_id');
    }

    public function education()
    {
        return $this->hasMany(DtlEducation::class, 'applicant_id');
    }
    
    public function organization()
    {
        return $this->hasMany(DtlOrganization::class, 'applicant_id');
    }
    
    public function internship()
    {
        return $this->hasMany(DtlInternship::class, 'applicant_id');
    }
    
    public function jobExperience()
    {
        return $this->hasMany(DtlJobExperience::class, 'applicant_id');
    }
}