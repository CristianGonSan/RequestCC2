<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Catalogs\CompanyController;
use App\Http\Controllers\Catalogs\CostCenterController;
use App\Http\Controllers\Catalogs\TypeController;
use App\Http\Controllers\Configurations\EmailNotificationsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\Lookups\CostCenterLookup;
use App\Http\Controllers\Lookups\TypeLookup;
use App\Http\Controllers\Lookups\UserLookup;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Requests\AccountingController;
use App\Http\Controllers\Requests\ManagementRequestController;
use App\Http\Controllers\Requests\ShowFileController;
use App\Http\Controllers\Requests\UserRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'))->name('root');
Route::get('/home', fn () => redirect()->route('dashboard'))->name('home');

// Inicio de Sesión
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Cierre de Sesión
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Restablecimiento de Contraseña
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset']);

// Verificación de Correo Electrónico (si está habilitada)
Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::get('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
Route::get('/email/{id}/verify', [VerificationController::class, 'sendEmailVerification'])->name('verification.send');

Route::get('disabled', fn () => view('auth.disabled'))->name('auth.disabled');

Route::middleware(['auth', 'active'])->group(function () {

    Route::get('account', [AccountController::class, 'show'])
        ->name('account');

    Route::resource('requests', UserRequestController::class)->only(['index', 'create', 'show', 'edit']);

    Route::prefix('management')->name('management.')->group(function () {
        Route::resource('requests', ManagementRequestController::class)->except(['destroy'])->middleware('permission:manage_requests');
    });

    Route::prefix('accounting/requests')->name('accounting.requests.')->middleware('permission:manage_accounting')
        ->group(function () {
            Route::get('', [AccountingController::class, 'index'])->name('index');
            Route::get('{requests}', [AccountingController::class, 'show'])->name('show');
        });

    Route::resource('users', UserController::class)->only(['index', 'create', 'show', 'edit'])->middleware('permission:manage_users');
    Route::resource('roles', RoleController::class)->only(['index', 'create', 'show', 'edit'])->middleware('permission:manage_roles');

    Route::resource('types', TypeController::class)->only(['index', 'create', 'show', 'edit'])->middleware('permission:manage_types');
    Route::resource('companies', CompanyController::class)->only(['index', 'create', 'show', 'edit'])->middleware('permission:manage_companies');
    Route::resource('cost-centers', CostCenterController::class)->only(['index', 'create', 'show', 'edit'])->middleware('permission:manage_cost_centers');

    Route::prefix('export')->middleware('permission:export')->name('export.')->group(function () {
        Route::get('', [ExportController::class, 'index'])->name('requests.index');
        Route::get('download', [ExportController::class, 'export'])->name('requests.download');

        Route::prefix('lookups')->name('lookups.')->group(function () {
            Route::get('cost-centers', [CostCenterLookup::class, 'select2'])
                ->name('cost-centers.select2');

            Route::get('users', [UserLookup::class, 'select2'])
                ->name('users.select2');
        });
    });

    Route::prefix('reports')->middleware('permission:view_summary')->group(function () {
        Route::get('', [ReportController::class, 'index'])->name('reports.index');
        Route::get('export', [ReportController::class, 'export'])->name('reports.export');
    });

    Route::get('configurations/mail-notifications', [EmailNotificationsController::class, 'index'])
        ->middleware('permission:manage_configurations')
        ->name('configurations.mailNotifications');

    Route::prefix('account')->group(function () {
        Route::get('profile', [AccountController::class, 'profile'])->name('account.profile');
    });

    Route::get('file/{id}/preview', [ShowFileController::class, 'previewFile'])->name('file.preview');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('info', fn () => view('info'))->name('info');

    /*
    |--------------------------------------------------------------------------
    | Lookups (Select2)
    |--------------------------------------------------------------------------
    */
    Route::prefix('lookups')->name('lookups.')->group(function () {
        Route::get('cost-centers/select2/auth', [CostCenterLookup::class, 'select2ByAuthUser'])
            ->name('cost-centers.select2.auth');

        Route::get('cost-centers/select2', [CostCenterLookup::class, 'select2'])
            ->name('cost-centers.select2');

        Route::get('types/select2/auth', [TypeLookup::class, 'select2ByAuthUser'])
            ->name('types.select2.auth');
    });
});
