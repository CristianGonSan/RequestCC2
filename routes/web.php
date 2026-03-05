<?php

use App\Http\Controllers\Auth\{
    ForgotPasswordController,
    LoginController,
    ResetPasswordController,
    VerificationController
};
use App\Http\Controllers\{
    AccountController,
    DashboardController,
    ExportController,
    ReportController
};
use App\Http\Controllers\Requests\{
    AccountingController,
    ManagementRequestController,
    ShowFileController,
    UserRequestController
};
use App\Http\Controllers\Admin\{
    CompanyController,
    CostCenterController,
    RoleController,
    UserController,
    PermissionController,
    TypeController
};
use App\Http\Controllers\Configurations\EmailNotificationsController;
use App\Http\Controllers\Lookups\CostCenterLookup;
use App\Http\Controllers\Lookups\UserLookup;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('root');

Route::get('/home', function () {
    return redirect()->route('dashboard');
})->name('home');

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

Route::get('/disabled', function () {
    return view('auth.disabled');
})->name('auth.disabled');

Route::middleware(['auth', 'enabled'])->group(function () {

    Route::prefix('account')->name('account.')->group(function () {
        Route::get('', [AccountController::class, 'index'])->name('index');
    });

    Route::resource('requests', UserRequestController::class);
    Route::get('requests/{request}/copy', [UserRequestController::class, 'copy'])->name('requests.copy');

    Route::prefix('management')->name('management.')->group(function () {
        Route::resource('requests', ManagementRequestController::class)->except(['destroy'])->middleware('permission:Gestionar Solicitudes');
    });

    Route::prefix('accounting/requests')->name('accounting.requests.')->middleware('permission:Gestionar Contabilidad')
        ->group(function () {
            Route::get('', [AccountingController::class, 'index'])->name('index');
            Route::get('{requests}', [AccountingController::class, 'show'])->name('show');
        });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class)->only(['index', 'create', 'store', 'show'])->middleware('permission:Gestionar Usuarios');
        Route::resource('roles', RoleController::class)->only(['index', 'create', 'store', 'show'])->middleware('permission:Gestionar Roles');
        Route::resource('permissions', PermissionController::class)->only(['index', 'create', 'store', 'show'])->middleware('permission:Gestionar Permisos');
        Route::resource('types', TypeController::class)->only(['index', 'create', 'store', 'show'])->middleware('permission:Gestionar Tipos');
        Route::resource('companies', CompanyController::class)->only(['index', 'create', 'store', 'show'])->middleware('permission:Gestionar Empresas');
        Route::resource('cost-centers', CostCenterController::class)->only(['index', 'create', 'store', 'show'])->middleware('permission:Gestionar Centro de Costos');
    });

    Route::prefix('export')->middleware('permission:Exportar')->name('export.')->group(function () {
        Route::get('', [ExportController::class, 'index'])->name('requests.index');
        Route::get('download', [ExportController::class, 'export'])->name('requests.download');

        Route::prefix('lookups')->name('lookups.')->group(function () {
            Route::get('cost-centers', [CostCenterLookup::class, 'select2'])
                ->name('cost-centers.select2');

            Route::get('users', [UserLookup::class, 'select2'])
                ->name('users.select2');
        });
    });


    Route::prefix('reports')->middleware('permission:Ver Resumen')->group(function () {
        Route::get('', [ReportController::class, 'index'])->name('reports.index');
        Route::get('export', [ReportController::class, 'export'])->name('reports.export');
    });

    Route::get('configurations/mail-notifications', [EmailNotificationsController::class, 'index'])
        ->middleware('permission:Gestionar Configuraciones')
        ->name('configurations.mailNotifications');

    Route::prefix('account')->group(function () {
        Route::get('profile', [AccountController::class, 'profile'])->name('account.profile');
    });

    Route::get('file/{id}/preview', [ShowFileController::class, 'previewFile'])->name('file.preview');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('info', function () {
        return view('info');
    })->name('info');
});
