<?php

namespace App\Models\erecruitment\view;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VwInvitationApplicant extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql-erec';
    public $table = "vw_invitation_applicant";
}