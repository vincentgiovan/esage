<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        // Allow and block
        'allow' => \App\Http\Middleware\AllowedRoles::class,
        'block' => \App\Http\Middleware\BlockedRoles::class,

        // Deprecated
        'master' => \App\Http\Middleware\MasterAccount::class,
        'accounting_admin' => \App\Http\Middleware\AccountingAdminUser::class,
        'purchasing_admin' => \App\Http\Middleware\PurchasingAdminUser::class,
        'project_manager' => \App\Http\Middleware\ProjectManagerUser::class,
        'user' => \App\Http\Middleware\NormalUser::class,

        'can_access_product' => \App\Http\Middleware\Permissions\CanAccessProduct::class,
        'can_access_partner' => \App\Http\Middleware\Permissions\CanAccessPartner::class,
        'can_access_project' => \App\Http\Middleware\Permissions\CanAccessProject::class,
        'can_access_delivery_order' => \App\Http\Middleware\Permissions\CanAccessDeliveryOrder::class,
        'can_access_purchase' => \App\Http\Middleware\Permissions\CanAccessPurchase::class,
        'can_access_request_item' => \App\Http\Middleware\Permissions\CanAccessRequestItem::class,
        'can_access_return_item' => \App\Http\Middleware\Permissions\CanAccessReturnItem::class,
        'can_access_account' => \App\Http\Middleware\Permissions\CanAccessAccount::class,
        'can_access_employee' => \App\Http\Middleware\Permissions\CanAccessEmployee::class,
        'can_access_attendance' => \App\Http\Middleware\Permissions\CanAccessAttendance::class,
        'can_access_salary' => \App\Http\Middleware\Permissions\CanAccessSalary::class,
        'can_access_visit_log' => \App\Http\Middleware\Permissions\CanAccessVisitLog::class,
        'can_access_propose_leave' => \App\Http\Middleware\Permissions\CanAccessProposeLeave::class,
        'can_access_self_attendance' => \App\Http\Middleware\Permissions\CanAccessSelfAttendance::class,
    ];
}
