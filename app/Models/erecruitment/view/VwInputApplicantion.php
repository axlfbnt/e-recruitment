<?php

namespace App\Models\erecruitment\view;

use App\Models\erecruitment\table\DtlEducation;
use App\Models\erecruitment\table\DtlInternship;
use App\Models\erecruitment\table\DtlJobExperience;
use App\Models\erecruitment\table\DtlOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VwInputApplicantion extends Model
{
    use HasFactory;
    protected $connection = 'mysql-erec';
    public $table = "vw_input_application";

    protected $fillable = [];

    public function education()
    {
        return $this->hasMany(DtlEducation::class, 'applicant_id', 'applicant_id');
    }

    public function organization()
    {
        return $this->hasMany(DtlOrganization::class, 'applicant_id', 'applicant_id');
    }

    public function internship()
    {
        return $this->hasMany(DtlInternship::class, 'applicant_id', 'applicant_id');
    }

    public function jobExperience()
    {
        return $this->hasMany(DtlJobExperience::class, 'applicant_id', 'applicant_id');
    }
}