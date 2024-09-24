<?php

use App\Http\Controllers\admin\AdminDashboard;
use App\Http\Controllers\admin\AnalyticsController;
use App\Http\Controllers\admin\Course;
use App\Http\Controllers\admin\ExaminersAccount;
use App\Http\Controllers\admin\QuestionnaireController;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\examiners\ExaminationController;
use App\Http\Controllers\examiners\ExaminersDashboardController;
use App\Http\Controllers\examiners\ExaminersInformation;
use App\Http\Controllers\guest\HomeController;
use Illuminate\Support\Facades\Route;

// GUEST
Route::get('/', [HomeController::class, 'HomePage'])->name('homepage');
Route::get('/home', [HomeController::class, 'HomePage'])->name('homepage');


// FORGOT PASSWORD
Route::get('/forgot-password/form', [AuthController::class, 'ForgotPasswordForm'])->name('forgotpasswordform');
Route::post('/forgot-password/request', [AuthController::class, 'ForgotPasswordRequest'])->name('forgotpasswordrequest');
Route::get('/password/reset/{token}', [AuthController::class, 'ForgotForm'])->name('password.reset');
Route::post('/password/resetpassword', [AuthController::class, 'ResetPassword'])->name('password.resetpassword');




// EXAMINERS AUTH PAGE
Route::get('/login', [AuthController::class, 'LoginPage'])->name('loginpage');
Route::post('/loginrequest', [AuthController::class, 'ExaminersLoginRequest'])->name('examiners.login.request');
Route::get('/logout', [AuthController::class, 'ExaminersLogout'])->name('examinerslogout');
// EXAMINERS PAGE
Route::middleware(['auth:users', 'users'])->group(function () {
    // EXAMINERS DASHBOARD PAGE
    Route::get('/examiners/dashboard', [ExaminersDashboardController::class, 'ExaminersDashboardPage'])->name('examiners.dashboard.page');
    
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
    Route::delete('/admin/examiners_account/delete/{default_id}', [ExaminersAccount::class, 'ExaminersDefaultIdDelete'])->name('admin.examiners.delete');


    // COURSE MANAGEMENT
    Route::get('/admin/admin_course', [Course::class, 'CoursePage'])->name('admin.course');
    Route::post('/admin/add_course', [Course::class, 'AddCourse'])->name('addcourse');
    Route::get('/admin/edit_course/{id}', [Course::class, 'EditCourse'])->name('editcourse');
    Route::post('/admin/update_course/{id}', [Course::class, 'UpdateCourse'])->name('updatecourse');
    Route::post('/admin/delete_course/{id}', [Course::class, 'DeleteCourse'])->name('deletecourse');


    // QUESTIONNAIRE MANAGEMENT
    Route::get('/admin/admin_questionnaire', [QuestionnaireController::class, 'QuestionnairePage'])->name('admin.questionnaire');
    Route::post('/admin/add_questionnaire', [QuestionnaireController::class, 'AddQuestionnaire'])->name('admin.add.questionnaire');
    Route::get('/admin/questionnaire/edit/{id}', [QuestionnaireController::class, 'EditQuestionnaire'])->name('admin.questionnaire.edit');
    Route::put('/admin/questionnaire/update/{id}', [QuestionnaireController::class, 'UpdateQuestionnaire'])->name('admin.questionnaire.update');
    Route::delete('/admin/questionnaire/delete/{id}', [QuestionnaireController::class, 'DeleteQuestionnaire'])->name('admin.questionnaire.delete');

    // ANALYTICS MANAGEMENT
    Route::get('/admin/analytics/', [AnalyticsController::class, 'AnalyticsPage'])->name('admin.analytics.page');
    Route::get('/admin/available-courses', [AnalyticsController::class, 'GetAvailableCourses'])->name('admin.available.courses');

    // TOP NOTCHERS BY YEAR
    Route::get('/admin/top-notchers/{year}', [AnalyticsController::class, 'getTopNotchersByYear']);

});
