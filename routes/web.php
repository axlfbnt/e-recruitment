<?php

use App\Http\Controllers\erecruitment\AdministrativeSelectionController;
use App\Http\Controllers\erecruitment\CandidatePoolingController;
use App\Http\Controllers\erecruitment\EmployeeSubmissionFormA1Controller;
use App\Http\Controllers\erecruitment\FinalInterviewController;
use App\Http\Controllers\erecruitment\FlowRecruitmentController;
use App\Http\Controllers\erecruitment\FollowUpInterviewController;
use App\Http\Controllers\erecruitment\InitialInterviewController;
use App\Http\Controllers\erecruitment\JobDescriptionController;
use App\Http\Controllers\erecruitment\JobVacancyController;
use App\Http\Controllers\erecruitment\InputApplicationController;
use App\Http\Controllers\erecruitment\InvitationApplicantController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\erecruitment\ManPowerPlanningController;
use App\Http\Controllers\erecruitment\OfferingController;
use App\Http\Controllers\erecruitment\PsychologicalTestController;
use App\Http\Controllers\erecruitment\TemplateFormA3Controller;
use App\Http\Controllers\erecruitment\TemplateFormA4Controller;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Rute untuk ManPowerPlanningController dengan prefix 'Man Power Planning'
Route::prefix('man-power-planning')->group(function () {
    Route::get('/', [ManPowerPlanningController::class, 'index'])->name('mpp.index');
    Route::get('/create', [ManPowerPlanningController::class, 'create'])->name('mpp.create');
    Route::post('/', [ManPowerPlanningController::class, 'store'])->name('mpp.store');
    Route::get('/{id}', [ManPowerPlanningController::class, 'show'])->name('mpp.show');
    Route::get('/{id}/edit', [ManPowerPlanningController::class, 'edit'])->name('mpp.edit');
    Route::put('/{id}', [ManPowerPlanningController::class, 'update'])->name('mpp.update');
    Route::delete('/{id}', [ManPowerPlanningController::class, 'destroy'])->name('mpp.destroy');
});

// Get Data Kebutuhan Man Power
Route::get('/get-companies', [ManPowerPlanningController::class, 'getCompanies'])->name('get.companies');
Route::post('/get-departments', [ManPowerPlanningController::class, 'getDepartments'])->name('get.departments');
Route::post('/get-division', [ManPowerPlanningController::class, 'getDivision'])->name('get.division');
Route::post('/get-positions', [ManPowerPlanningController::class, 'getPositions'])->name('get.positions');

// Rute untuk EmployeeSubmissionFormA1Controller dengan prefix 'Employee Submission Form A1'
Route::prefix('employee-submission-forma1')->group(function () {
    Route::get('/', [EmployeeSubmissionFormA1Controller::class, 'index'])->name('forma1.index');
    Route::get('/create', [EmployeeSubmissionFormA1Controller::class, 'create'])->name('forma1.create');
    Route::post('/', [EmployeeSubmissionFormA1Controller::class, 'store'])->name('forma1.store');
    Route::get('/{id}', [EmployeeSubmissionFormA1Controller::class, 'show'])->name('forma1.show');
    Route::get('/{id}/edit', [EmployeeSubmissionFormA1Controller::class, 'edit'])->name('forma1.edit');
    Route::get('/{id}/detail', [EmployeeSubmissionFormA1Controller::class, 'detail'])->name('forma1.detail');
    Route::put('/approvalFormA1/{id}', [EmployeeSubmissionFormA1Controller::class, 'approvalFormA1'])->name('forma1.approvalFormA1');
    Route::put('/{id}', [EmployeeSubmissionFormA1Controller::class, 'update'])->name('forma1.update');
    Route::delete('/{id}', [EmployeeSubmissionFormA1Controller::class, 'destroy'])->name('forma1.destroy');
});

// Get Data Kebutuhan Form A1
Route::get('/get-positions-forFormA1', [EmployeeSubmissionFormA1Controller::class, 'getPositions_forFormA1'])->name('get.positions_forFormA1');
Route::get('/get-position-details', [EmployeeSubmissionFormA1Controller::class, 'getPositionDetails'])->name('get.getPositionDetails');

// Rute untuk JobDescriptionController dengan prefix 'Job Description'
Route::prefix('job-description')->group(function () {
    Route::get('/', [JobDescriptionController::class, 'index'])->name('job-description.index');
    Route::get('/create', [JobDescriptionController::class, 'create'])->name('job-description.create');
    Route::post('/', [JobDescriptionController::class, 'store'])->name('job-description.store');
    Route::get('/{id}', [JobDescriptionController::class, 'show'])->name('job-description.show');
    Route::get('/{id}/edit', [JobDescriptionController::class, 'edit'])->name('job-description.edit');
    Route::put('/{id}', [JobDescriptionController::class, 'update'])->name('job-description.update');
    Route::delete('/{id}', [JobDescriptionController::class, 'destroy'])->name('job-description.destroy');
});

// Rute untuk JobVacancyController dengan prefix 'Job Vacancy'
Route::prefix('job-vacancy')->group(function () {
    Route::get('/', [JobVacancyController::class, 'index'])->name('job-vacancy.index');
    Route::get('/create', [JobVacancyController::class, 'create'])->name('job-vacancy.create');
    Route::post('/', [JobVacancyController::class, 'store'])->name('job-vacancy.store');
    Route::get('/{id}', [JobVacancyController::class, 'show'])->name('job-vacancy.show');
    Route::get('/{id}/edit', [JobVacancyController::class, 'edit'])->name('job-vacancy.edit');
    Route::put('/{id}', [JobVacancyController::class, 'update'])->name('job-vacancy.update');
    Route::delete('/{id}', [JobVacancyController::class, 'destroy'])->name('job-vacancy.destroy');
});

// Get Data Kebutuhan Job Vacancy
Route::get('/get-positions-forVacancy', [JobVacancyController::class, 'getPositions_forVacancy'])->name('get.positions_forVacancy');
Route::get('/get-flowrecruitment', [JobVacancyController::class, 'getFlowRecruitment'])->name('get.flowrecruitment');

// Rute untuk InputApplicationController dengan prefix 'Input Application'
Route::prefix('input-application')->group(function () {
    Route::get('/', [InputApplicationController::class, 'index'])->name('input-application.index');
    Route::get('/create', [InputApplicationController::class, 'create'])->name('input-application.create');
    Route::post('/', [InputApplicationController::class, 'store'])->name('input-application.store');
    Route::get('/{id}', [InputApplicationController::class, 'show'])->name('input-application.show');
    Route::get('/{id}/edit', [InputApplicationController::class, 'edit'])->name('input-application.edit');
    Route::put('/{id}', [InputApplicationController::class, 'update'])->name('input-application.update');
    Route::delete('/{id}', [InputApplicationController::class, 'destroy'])->name('input-application.destroy');
    Route::post('/import', [InputApplicationController::class, 'importStore'])->name('input-application.import');
});

// Get Data Kebutuhan Input Application
Route::get('/get-vacancy', [InputApplicationController::class, 'getVacancy'])->name('get.vacancy');
Route::get('/get-domicile', [InputApplicationController::class, 'getDomicile'])->name('get.domicile');
Route::get('/get-degree', [InputApplicationController::class, 'getDegree'])->name('get.degree');
Route::get('/get-institution', [InputApplicationController::class, 'getInstitution'])->name('get.institution');
Route::get('/get-major', [InputApplicationController::class, 'getMajor'])->name('get.major');
Route::get('/get-function', [InputApplicationController::class, 'getFunction'])->name('get.function');
Route::get('/get-industry', [InputApplicationController::class, 'getIndustry'])->name('get.industry');

// Rute untuk CandidatePoolingController dengan prefix 'Candidate Pooling'
Route::prefix('candidate-pooling')->group(function () {
    Route::get('/', [CandidatePoolingController::class, 'index'])->name('candidate-pooling.index');
    Route::get('/create', [CandidatePoolingController::class, 'create'])->name('candidate-pooling.create');
    Route::post('/', [CandidatePoolingController::class, 'store'])->name('candidate-pooling.store');
    Route::get('/{id}', [CandidatePoolingController::class, 'show'])->name('candidate-pooling.show');
    Route::get('/{id}/edit', [CandidatePoolingController::class, 'edit'])->name('candidate-pooling.edit');
    Route::put('/{id}', [CandidatePoolingController::class, 'update'])->name('candidate-pooling.update');
    Route::delete('/{id}', [CandidatePoolingController::class, 'destroy'])->name('candidate-pooling.destroy');
});

// Rute untuk InvitationApplicantController dengan prefix 'Invitation Applicant'
Route::prefix('invitation-applicant')->group(function () {
    Route::get('/', [InvitationApplicantController::class, 'index'])->name('invitation-applicant.index');
    Route::get('/create', [InvitationApplicantController::class, 'create'])->name('invitation-applicant.create');
    Route::post('/', [InvitationApplicantController::class, 'store'])->name('invitation-applicant.store');
    Route::get('/{id}', [InvitationApplicantController::class, 'show'])->name('invitation-applicant.show');
    Route::get('/{id}/edit', [InvitationApplicantController::class, 'edit'])->name('invitation-applicant.edit');
    Route::put('/{id}', [InvitationApplicantController::class, 'update'])->name('invitation-applicant.update');
    Route::delete('/{id}', [InvitationApplicantController::class, 'destroy'])->name('invitation-applicant.destroy');
    Route::get('/{id}/applicants-data', [InvitationApplicantController::class, 'getApplicantsData'])->name('invitation-applicants.data');
});

// Rute untuk AdministrativeSelectionController dengan prefix 'Administrative Selection'
Route::prefix('administrative-selection')->group(function () {
    Route::get('/', [AdministrativeSelectionController::class, 'index'])->name('administrative-selection.index');
    Route::get('/create', [AdministrativeSelectionController::class, 'create'])->name('administrative-selection.create');
    Route::post('/', [AdministrativeSelectionController::class, 'store'])->name('administrative-selection.store');
    Route::get('/{id}', [AdministrativeSelectionController::class, 'show'])->name('administrative-selection.show');
    Route::get('/{id}/edit', [AdministrativeSelectionController::class, 'edit'])->name('administrative-selection.edit');
    Route::put('/{id}', [AdministrativeSelectionController::class, 'update'])->name('administrative-selection.update');
    Route::delete('/{id}', [AdministrativeSelectionController::class, 'destroy'])->name('administrative-selection.destroy');
    Route::get('/{id}/applicants-data', [AdministrativeSelectionController::class, 'getApplicantsData'])->name('administrative-selection-applicants.data');
    Route::get('/{id}/applicant-cv', [AdministrativeSelectionController::class, 'applicantCV'])->name('administrative-selection.applicantCV');
    Route::put('/approvalAdministrative/{id}', [AdministrativeSelectionController::class, 'approvalAdministrative'])->name('administrative-selection.approvalAdministrative');
    Route::get('/{id}/history', [AdministrativeSelectionController::class, 'getApplicantHistory'])->name('history.get');
});

// Rute untuk PsychologicalTestController dengan prefix 'Psychological Test'
Route::prefix('psychological-test')->group(function () {
    Route::get('/', [PsychologicalTestController::class, 'index'])->name('psychological-test.index');
    Route::get('/create', [PsychologicalTestController::class, 'create'])->name('psychological-test.create');
    Route::post('/', [PsychologicalTestController::class, 'store'])->name('psychological-test.store');
    Route::get('/{id}', [PsychologicalTestController::class, 'show'])->name('psychological-test.show');
    Route::get('/{id}/edit', [PsychologicalTestController::class, 'edit'])->name('psychological-test.edit');
    Route::put('/{id}', [PsychologicalTestController::class, 'update'])->name('psychological-test.update');
    Route::delete('/{id}', [PsychologicalTestController::class, 'destroy'])->name('psychological-test.destroy');
    Route::get('/{id}/applicants-data', [PsychologicalTestController::class, 'getApplicantsData'])->name('psychological-test-applicants.data');
    Route::put('/approvalAdministrative/{id}', [PsychologicalTestController::class, 'approvalAdministrative'])->name('psychological-test.approvalAdministrative');
    Route::post('/import', [PsychologicalTestController::class, 'importBulkProcess'])->name('psychological-test.import');
});

// Rute untuk InitialInterviewController dengan prefix 'Initial Interview'
Route::prefix('initial-interview')->group(function () {
    Route::get('/', [InitialInterviewController::class, 'index'])->name('initial-interview.index');
    Route::get('/create', [InitialInterviewController::class, 'create'])->name('initial-interview.create');
    Route::post('/', [InitialInterviewController::class, 'store'])->name('initial-interview.store');
    Route::get('/{id}', [InitialInterviewController::class, 'show'])->name('initial-interview.show');
    Route::get('/{id}/edit', [InitialInterviewController::class, 'edit'])->name('initial-interview.edit');
    Route::put('/{id}', [InitialInterviewController::class, 'update'])->name('initial-interview.update');
    Route::delete('/{id}', [InitialInterviewController::class, 'destroy'])->name('initial-interview.destroy');
    Route::get('/{id}/applicants-data', [InitialInterviewController::class, 'getApplicantsData'])->name('initial-interview-applicants.data');
    Route::put('/approvalAdministrative/{id}', [InitialInterviewController::class, 'approvalAdministrative'])->name('initial-interview.approvalAdministrative');
    Route::post('/import', [InitialInterviewController::class, 'importBulkProcess'])->name('initial-interview.import');
});

// Rute untuk FollowUpInterviewController dengan prefix 'Follow-up Interview'
Route::prefix('followup-interview')->group(function () {
    Route::get('/', [FollowUpInterviewController::class, 'index'])->name('followup-interview.index');
    Route::get('/create', [FollowUpInterviewController::class, 'create'])->name('followup-interview.create');
    Route::post('/', [FollowUpInterviewController::class, 'store'])->name('followup-interview.store');
    Route::get('/{id}', [FollowUpInterviewController::class, 'show'])->name('followup-interview.show');
    Route::get('/{id}/edit', [FollowUpInterviewController::class, 'edit'])->name('followup-interview.edit');
    Route::put('/{id}', [FollowUpInterviewController::class, 'update'])->name('followup-interview.update');
    Route::delete('/{id}', [FollowUpInterviewController::class, 'destroy'])->name('followup-interview.destroy');
    Route::get('/{id}/applicants-data', [FollowUpInterviewController::class, 'getApplicantsData'])->name('followup-interview-applicants.data');
    Route::put('/approvalAdministrative/{id}', [FollowUpInterviewController::class, 'approvalAdministrative'])->name('followup-interview.approvalAdministrative');
    Route::post('/import', [FollowUpInterviewController::class, 'importBulkProcess'])->name('followup-interview.import');
});

// Rute untuk FinalInterviewController dengan prefix 'Final Interview'
Route::prefix('final-interview')->group(function () {
    Route::get('/', [FinalInterviewController::class, 'index'])->name('final-interview.index');
    Route::get('/create', [FinalInterviewController::class, 'create'])->name('final-interview.create');
    Route::post('/', [FinalInterviewController::class, 'store'])->name('final-interview.store');
    Route::get('/{id}', [FinalInterviewController::class, 'show'])->name('final-interview.show');
    Route::get('/{id}/edit', [FinalInterviewController::class, 'edit'])->name('final-interview.edit');
    Route::put('/{id}', [FinalInterviewController::class, 'update'])->name('final-interview.update');
    Route::delete('/{id}', [FinalInterviewController::class, 'destroy'])->name('final-interview.destroy');
    Route::get('/{id}/applicants-data', [FinalInterviewController::class, 'getApplicantsData'])->name('final-interview-applicants.data');
    Route::put('/approvalAdministrative/{id}', [FinalInterviewController::class, 'approvalAdministrative'])->name('final-interview.approvalAdministrative');
    Route::post('/import', [FinalInterviewController::class, 'importBulkProcess'])->name('final-interview.import');
});

// Rute untuk OfferingController dengan prefix 'Offering'
Route::prefix('offering')->group(function () {
    Route::get('/', [OfferingController::class, 'index'])->name('offering.index');
    Route::get('/create', [OfferingController::class, 'create'])->name('offering.create');
    Route::post('/', [OfferingController::class, 'store'])->name('offering.store');
    Route::get('/{id}', [OfferingController::class, 'show'])->name('offering.show');
    Route::get('/{id}/edit', [OfferingController::class, 'edit'])->name('offering.edit');
    Route::put('/{id}', [OfferingController::class, 'update'])->name('offering.update');
    Route::delete('/{id}', [OfferingController::class, 'destroy'])->name('offering.destroy');
    Route::get('/{id}/applicants-data', [OfferingController::class, 'getApplicantsData'])->name('offering-applicants.data');
    Route::put('/approvalAdministrative/{id}', [OfferingController::class, 'approvalAdministrative'])->name('offering.approvalAdministrative');
    Route::post('/import', [OfferingController::class, 'importBulkProcess'])->name('offering.import');
});

// Rute untuk FlowRecruitmentController dengan prefix 'Flow Recruitment'
Route::prefix('flow-recruitment')->group(function () {
    Route::get('/', [FlowRecruitmentController::class, 'index'])->name('flow-recruitment.index');
    Route::get('/create', [FlowRecruitmentController::class, 'create'])->name('flow-recruitment.create');
    Route::post('/', [FlowRecruitmentController::class, 'store'])->name('flow-recruitment.store');
    Route::get('/{id}', [FlowRecruitmentController::class, 'show'])->name('flow-recruitment.show');
    Route::get('/{id}/edit', [FlowRecruitmentController::class, 'edit'])->name('flow-recruitment.edit');
    Route::put('/{id}', [FlowRecruitmentController::class, 'update'])->name('flow-recruitment.update');
    Route::delete('/{id}', [FlowRecruitmentController::class, 'destroy'])->name('flow-recruitment.destroy');
});

// Rute untuk TemplateFormA3Controller dengan prefix 'Template Form A3'
Route::prefix('template-forma3')->group(function () {
    Route::get('/', [TemplateFormA3Controller::class, 'index'])->name('template-forma3.index');
    Route::get('/create', [TemplateFormA3Controller::class, 'create'])->name('template-forma3.create');
    Route::post('/', [TemplateFormA3Controller::class, 'store'])->name('template-forma3.store');
    Route::get('/{id}', [TemplateFormA3Controller::class, 'show'])->name('template-forma3.show');
    Route::get('/{id}/edit', [TemplateFormA3Controller::class, 'edit'])->name('template-forma3.edit');
    Route::put('/{id}', [TemplateFormA3Controller::class, 'update'])->name('template-forma3.update');
    Route::delete('/{id}', [TemplateFormA3Controller::class, 'destroy'])->name('template-forma3.destroy');
    Route::post('/update-status/{id}', [TemplateFormA3Controller::class, 'updateStatus'])->name('template-forma3.updateStatus');
});

// Rute untuk TemplateFormA4Controller dengan prefix 'Template Form A4'
Route::prefix('template-forma4')->group(function () {
    Route::get('/', [TemplateFormA4Controller::class, 'index'])->name('template-forma4.index');
    Route::get('/create', [TemplateFormA4Controller::class, 'create'])->name('template-forma4.create');
    Route::post('/', [TemplateFormA4Controller::class, 'store'])->name('template-forma4.store');
    Route::get('/{id}', [TemplateFormA4Controller::class, 'show'])->name('template-forma4.show');
    Route::get('/{id}/edit', [TemplateFormA4Controller::class, 'edit'])->name('template-forma4.edit');
    Route::put('/{id}', [TemplateFormA4Controller::class, 'update'])->name('template-forma4.update');
    Route::delete('/{id}', [TemplateFormA4Controller::class, 'destroy'])->name('template-forma4.destroy');
    Route::post('/update-status/{id}', [TemplateFormA4Controller::class, 'updateStatus'])->name('template-forma4.updateStatus');
});