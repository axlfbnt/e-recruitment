<?php

namespace App\Models\erecruitment\table;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class PsychologicalTestResult extends Model
{
    use HasFactory;
    protected $connection = 'mysql-erec';
    protected $table = 'psychological_test_results';

    protected $fillable = [
        'applicant_id',
        'vacancy',
        'test_date',
        'norm_grade',
        'final_result',
    ];

    public function subscores()
    {
        return $this->hasMany(PsychologicalTestSubscore::class, 'test_result_id');
    }
}