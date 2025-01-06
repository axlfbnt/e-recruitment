<?php

namespace App\Http\Controllers;

use App\Models\erecruitment\view\VwAchievementByDivision;
use App\Models\erecruitment\view\VwApplicantProcessSummary;
use App\Models\erecruitment\view\VwManPowerApprovedByDivision;
use App\Models\erecruitment\view\VwManpowerBySource;
use App\Models\erecruitment\view\VwManPowerSummary;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('erecruitment.dashboard.content');
    }

    public function getManPowerSummary()
    {
        $manPowerSummary = VwManPowerSummary::get();
        
        return response()->json($manPowerSummary);
    }

    public function getManPowerApprovedDivision()
    {
        $manPowerApprovedDivision = VwManPowerApprovedByDivision::get();
        
        return response()->json($manPowerApprovedDivision);
    }

    public function getApplicantProcessSummary()
    {
        $applicantProcessSummary = VwApplicantProcessSummary::get();
        
        return response()->json($applicantProcessSummary);
    }

    public function getAchievementByDivision()
    {
        $achievementByDivision = VwAchievementByDivision::get();
        
        return response()->json($achievementByDivision);
    }

    public function getManPowerBySource()
    {
        $manPowerBySource = VwManpowerBySource::get();
        
        return response()->json($manPowerBySource);
    }
}