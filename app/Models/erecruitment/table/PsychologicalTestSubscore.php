<?php

namespace App\Models\erecruitment\table;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsychologicalTestSubscore extends Model
{
    use HasFactory;
    protected $connection = 'mysql-erec';
    protected $table = 'psychological_test_subscores';

    protected $fillable = [
        'test_result_id',
        'subtest_name',
        'norm_score',
        'cutoff_score',
        'status',
    ];

    public function testResult()
    {
        return $this->belongsTo(PsychologicalTestResult::class, 'test_result_id');
    }
}