<?php

use App\Http\Controllers\admin\AdminDashboard;
use App\Http\Controllers\admin\Course;
use App\Http\Controllers\admin\ExaminersAccount;
use App\Http\Controllers\admin\QuestionnaireController;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\examiners\ExaminationController;
use App\Http\Controllers\examiners\ExaminersInformation;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'LoginPage'])->name('loginpage');


// AUTH PAGE
Route::get('/login', [AuthController::class, 'LoginPage'])->name('loginpage');
Route::post('/loginrequest', [AuthController::class, 'ExaminersLoginRequest'])->name('examiners.login.request');
Route::get('/logout', [AuthController::class, 'ExaminersLogout'])->name('examinerslogout');
// EXAMINERS PAGE
Route::middleware(['auth:users', 'users'])->group(function () {
    // EXAMINERS LANDING PAGE
    Route::get('/examiners_landing', [ExaminersInformation::class, 'ExaminersInformationPage'])->name('examiners.landing.page');
    Route::post('/add_information', [ExaminersInformation::class, 'AddInformation'])->name('examiners.add.information');

    // EXAMINERS EXAMINATION PAGE
    Route::get('/examination_form', [ExaminationController::class, 'ExaminationPage'])->name('examiners.examination.page');
    Route::post('/examination_form_submit', [ExaminationController::class, 'SubmitExamination'])->name('examiners.examination.submit');
    Route::get('/examination/complete', [ExaminationController::class, 'ExaminationCompletePage'])->name('examiners.examination.complete');
});

// ADMIN AUTH PAGE
Route::get('/admin/admin_login', [AuthController::class, 'AdminLoginPage'])->name('adminloginpage');
Route::post('/admin/admin_login_request', [AuthController::class, 'AdminLoginRequest'])->name('adminloginrequest');
Route::get('/admin/logout', [AuthController::class, 'AdminLogout'])->name('adminlogout');

// ADMIN PAGE
Route::middleware(['auth:admin', 'admin'])->group(function () {
    // DASHBOARD MANAGEMENT
    Route::get('/admin/admin_dashboard', [AdminDashboard::class, 'AdminDashboardPage'])->name('admin.dashboard');

    // EXAMINERS ACCOUNT MANAGEMENT
    Route::get('/admin/admin_examiners_account', [ExaminersAccount::class, 'ExaminersAccountPage'])->name('admin.examiners.account');
    Route::post('/admin/add_examiners_account', [ExaminersAccount::class, 'ExaminersAccountAdd'])->name('admin.examiners.add');

    // COURSE MANAGEMENT
    Route::get('/admin/admin_course', [Course::class, 'CoursePage'])->name('admin.course');
    Route::post('/admin/add_course', [Course::class, 'AddCourse'])->name('addcourse');

    // QUESTIONNAIRE MANAGEMENT
    Route::get('/admin/admin_questionnaire', [QuestionnaireController::class, 'QuestionnairePage'])->name('admin.questionnaire');
    Route::post('/admin/add_questionnaire', [QuestionnaireController::class, 'AddQuestionnaire'])->name('admin.add.questionnaire');
    Route::get('/admin/questionnaire/edit/{id}', [QuestionnaireController::class, 'EditQuestionnaire'])->name('admin.questionnaire.edit');
    Route::put('/admin/questionnaire/update/{id}', [QuestionnaireController::class, 'UpdateQuestionnaire'])->name('admin.questionnaire.update');
    Route::delete('/admin/questionnaire/delete/{id}', [QuestionnaireController::class, 'DeleteQuestionnaire'])->name('admin.questionnaire.delete');
});
