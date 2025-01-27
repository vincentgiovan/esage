<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ReturnItemController;
use App\Http\Controllers\RequestItemController;
use App\Http\Controllers\DeliveryOrderController;
use App\Http\Controllers\PurchaseProductController;
use App\Http\Controllers\DeliveryOrderProductController;
use App\Http\Controllers\EmployeeProjectController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PrepayController;

Route::get('/', function(){
    return redirect("/dashboard");
})->name("home");

Route::middleware(["auth", "verified"])->group(function(){
    // Dashboard and profile
    Route::get('/dashboard', [DashboardController::class, "index"] )->name("dashboard");
    Route::post("/dashboard/add-todo", [TodoController::class, "add_todo"])->name("todo-store");
    Route::post("/dashboard/save-todo", [TodoController::class, "save_todo"])->name("todo-update");

    Route::get("/profile", [AccountController::class, "edit_profile"])->name("profile-edit");
    Route::post("/profile", [AccountController::class, "update_profile"])->name("profile-update");

    // Normal Users Can Access These Pages
    Route::get('/delivery-order', [DeliveryOrderController::class, "index"] )->name("deliveryorder-index");
    Route::get('/product', [ProductController::class, "index"] )->name("product-index");
    Route::get('/partner', [PartnerController::class, "index"] )->name("partner-index");
    Route::get('/project', [ProjectController::class, "index"] )->name("project-index");
    Route::get('/purchase', [PurchaseController::class, "index"] )->name("purchase-index");

    Route::get('/purchase-product/{id}/viewitem', [PurchaseProductController::class, "view_items"] )->name("purchaseproduct-viewitem")->whereNumber("id");
    Route::get('/delivery-order-product/{id}/viewitem', [DeliveryOrderProductController::class, "view_items"] )->name("deliveryorderproduct-viewitem")->whereNumber("id");

    Route::get('/return-item', [ReturnItemController::class, "index"] )->name("returnitem-index");
    Route::get("/request-item", [RequestItemController::class, "index"])->name("requestitem-index");


    // ===== PRODUCTS ===== //
    Route::middleware('can_access_product')->group(function(){
        Route::get('/product/create', [ProductController::class, "create"] )->name("product-create");
        Route::post('/product/store', [ProductController::class, "store"] )->name("product-store");
        Route::get("/product/import", [ProductController::class, "import_product_form"])->name("product-import");
        Route::post("/product/import", [ProductController::class, "import_product_store"])->name("product-import-store");
        Route::get('/product/{id}/edit', [ProductController::class, "edit"] )->name("product-edit")->whereNumber("id");
        Route::post('/product/{id}/edit', [ProductController::class, "update"] )->name("product-update")->whereNumber("id");
        Route::post('/product/{id}', [ProductController::class, "destroy"] )->name("product-destroy")->whereNumber("id");
        Route::get("/product/export/{mode}", [PDFController::class, "export_product"])->name("product-export")->whereNumber("mode");
        Route::get("/product/{id}/log", [ProductController::class, "view_log"])->name("product-log")->whereNumber("id");
    });

    // ===== PARTNERS ===== //
    Route::middleware('can_access_partner')->group(function(){
        Route::get('/partner/create', [PartnerController::class, "create"] )->name("partner-create");
        Route::post('/partner/store', [PartnerController::class, "store"] )->name("partner-store");
        Route::get("/partner/import", [PartnerController::class, "import_partner_form"])->name("partner-import");
        Route::post("/partner/import", [PartnerController::class, "import_partner_store"])->name("partner-import-store");
        Route::get('/partner/{id}/edit', [PartnerController::class, "edit"] )->name("partner-edit")->whereNumber("id");
        Route::post('/partner/{id}/edit', [PartnerController::class, "update"] )->name("partner-update")->whereNumber("id");
        Route::post('/partner/{id}', [PartnerController::class, "destroy"] )->name("partner-destroy")->whereNumber("id");
        Route::get("/partner/export/{mode}", [PDFController::class, "export_partner"])->name("partner-export")->whereNumber("mode");
        Route::get("/partner/{id}/log", [PartnerController::class, "view_log"])->name("partner-log")->whereNumber("id");
    });

    // ===== PROJECTS ===== //
    Route::middleware('can_access_project')->group(function(){
        Route::get('/project/create', [ProjectController::class, "create"] )->name("project-create");
        Route::post('/project/store', [ProjectController::class, "store"] )->name("project-store");
        Route::get("/project/import", [ProjectController::class, "import_project_form"])->name("project-import");
        Route::post("/project/import", [ProjectController::class, "import_project_store"])->name("project-import-store");
        Route::get('/project/{id}/manage-employee', [EmployeeProjectController::class, "index"])->name("project-manageemployee-index");
        Route::post('/project/{id}/assign-employee', [EmployeeProjectController::class, "assign_employee"])->name("project-manageemployee-assign");
        Route::post('/project/{id}/unassign-employee', [EmployeeProjectController::class, "unassign_employee"])->name("project-manageemployee-unassign");
        Route::get('/project/{id}/edit', [ProjectController::class, "edit"] )->name("project-edit")->whereNumber("id");
        Route::post('/project/{id}/edit', [ProjectController::class, "update"] )->name("project-update")->whereNumber("id");
        Route::post('/project/{id}', [ProjectController::class, "destroy"] )->name("project-destroy")->whereNumber("id");
        Route::get("/project/export/{mode}", [PDFController::class, "export_project"])->name("project-export")->whereNumber("mode");
        Route::get("/project/{id}/log", [ProjectController::class, "view_log"])->name("project-log")->whereNumber("id");
    });

    // ===== PURCHASES ===== //
    Route::middleware('can_access_purchase')->group(function(){
        // Purchase
        Route::get('/purchase/create', [PurchaseController::class, "create"] )->name("purchase-create");
        Route::post('/purchase/store', [PurchaseController::class, "store"] )->name("purchase-store");
        Route::get("/purchase/import", [PurchaseController::class, "import_purchase_form"])->name("purchase-import");
        Route::post("/purchase/import", [PurchaseController::class, "import_purchase_store"])->name("purchase-import-store");
        Route::get("/purchase/import-wp", [PurchaseController::class, "import_with_product_form"])->name("purchase-importwpform");
        Route::post("/purchase/import-wp", [PurchaseController::class, "import_with_product_store"])->name("purchase-importwpstore");
        Route::get('/purchase/{id}/edit', [PurchaseController::class, "edit"] )->name("purchase-edit")->whereNumber("id");
        Route::post('/purchase/{id}/edit', [PurchaseController::class, "update"] )->name("purchase-update")->whereNumber("id");
        Route::post('/purchase/{id}', [PurchaseController::class, "destroy"] )->name("purchase-destroy")->whereNumber("id");
        Route::get("/purchase/export/{mode}", [PDFController::class, "export_purchase"])->name("purchase-export")->whereNumber("mode");

        // Carts
        Route::get("/purchase-product/{id}/import", [PurchaseProductController::class, "import_purchaseproduct_form"])->name("purchaseproduct-import")->whereNumber("id");
        Route::post("/purchase-product/{id}/import", [PurchaseProductController::class, "import_purchaseproduct_store"])->name("purchaseproduct-import-store")->whereNumber("id");
        Route::get('/purchase-product/{id}/create1', [PurchaseProductController::class, "add_existing_product"] )->name("purchaseproduct-create1")->whereNumber("id");
        Route::post('/purchase-product/{id}/store1', [PurchaseProductController::class, "store_existing_product"] )->name("purchaseproduct-store1")->whereNumber("id");
        Route::get('/purchase-product/{id}/create2', [PurchaseProductController::class, "add_new_product"] )->name("purchaseproduct-create2")->whereNumber("id");
        Route::post('/purchase-product/{id}/store2', [PurchaseProductController::class, "store_new_product"] )->name("purchaseproduct-store2")->whereNumber("id");
        Route::post('/purchase-product/{id}/{pid}', [PurchaseProductController::class, "destroy"] )->name("purchaseproduct-destroy")->whereNumber("pid");
        Route::get("/purchase-product/{id}/export/{mode}", [PDFController::class, "export_purchase_product"])->name("purchaseproduct-export")->whereNumber("id");
    });

    // ===== DELIVERY ORDER AND CARTS ===== //
    Route::middleware('can_access_delivery_order')->group(function(){
        // Delivery Order
        Route::get('/delivery-order/create', [DeliveryOrderController::class, "create"] )->name("deliveryorder-create");
        Route::post('/delivery-order/store', [DeliveryOrderController::class, "store"] )->name("deliveryorder-store");
        Route::get("/delivery-order/import", [DeliveryOrderController::class, "import_deliveryorder_form"])->name("deliveryorder-import");
        Route::post("/delivery-order/import", [DeliveryOrderController::class, "import_deliveryorder_store"])->name("deliveryorder-import-store");
        Route::get("/delivery-order-product/import-wp", [DeliveryOrderController::class, "import_with_product_form"])->name("deliveryorder-importwpform");
        Route::post("/delivery-order-product/import-wp", [DeliveryOrderController::class, "import_with_product_store"])->name("deliveryorder-importwpstore");
        Route::get('/delivery-order/{id}/edit', [DeliveryOrderController::class, "edit"] )->name("deliveryorder-edit")->whereNumber("id");
        Route::post('/delivery-order/{id}/edit', [DeliveryOrderController::class, "update"] )->name("deliveryorder-update")->whereNumber("id");
        Route::post('/delivery-order/{id}', [DeliveryOrderController::class, "destroy"] )->name("deliveryorder-destroy")->whereNumber("id");
        Route::get("/delivery-order/export/{mode}", [PDFController::class, "export_deliveryorder"])->name("deliveryorder-export")->whereNumber("mode");

        // Carts
        Route::get("/delivery-order-product/{id}/import", [DeliveryOrderProductController::class, "import_deliveryorderproduct_form"])->name("deliveryorderproduct-import")->whereNumber("id");
        Route::post("/delivery-order-product/{id}/import", [DeliveryOrderProductController::class, "import_deliveryorderproduct_store"])->name("deliveryorderproduct-import-store")->whereNumber("id");
        Route::get('/delivery-order-product/{id}/create1', [DeliveryOrderProductController::class, "add_existing_product"] )->name("deliveryorderproduct-create1")->whereNumber("id");
        Route::post('/delivery-order-product/{id}/store1', [DeliveryOrderProductController::class, "store_existing_product"] )->name("deliveryorderproduct-store1")->whereNumber("id");
        Route::get('/delivery-order-product/{id}/create2', [DeliveryOrderProductController::class, "add_new_product"] )->name("deliveryorderproduct-create2")->whereNumber("id");
        Route::post('/delivery-order-product/{id}/store2', [DeliveryOrderProductController::class, "store_new_product"] )->name("deliveryorderproduct-store2")->whereNumber("id");
        Route::post('/delivery-order-product/{id}/{pid}', [DeliveryOrderProductController::class, "destroy"] )->name("deliveryorderproduct-destroy")->whereNumber("id")->whereNumber("pid");
        Route::get("/delivery-order-product/{id}/export/{mode}", [PDFController::class, "export_deliveryorder_product"])->name("deliveryorderproduct-export")->whereNumber("id")->whereNumber("mode");
    });

    // ===== RETURN ITEMS ===== //
    Route::middleware('can_access_return_item')->group(function(){
        Route::get('/return-item/create', [ReturnItemController::class, "create"] )->name("returnitem-create");
        Route::post('/return-item/store', [ReturnItemController::class, "store"] )->name("returnitem-store");
        Route::get('/return-item/{id}/edit', [ReturnItemController::class, "edit"] )->name("returnitem-edit")->whereNumber("id");
        Route::post('/return-item/{id}/edit', [ReturnItemController::class, "update"] )->name("returnitem-update")->whereNumber("id");
        Route::post('/return-item/{id}', [ReturnItemController::class, "destroy"] )->name("returnitem-destroy")->whereNumber("id");
    });

    // ===== REQUEST ITEMS ===== //
    Route::middleware('can_access_request_item')->group(function(){
        Route::get("/request-item/{id}", [RequestItemController::class, "show"])->name("requestitem-show")->whereNumber("id");
        Route::get('/request-item/create', [RequestItemController::class, "create"] )->name("requestitem-create");
        Route::post('/request-item/store', [RequestItemController::class, "store"] )->name("requestitem-store");
        Route::get('/request-item/{id}/edit', [RequestItemController::class, "edit"] )->name("requestitem-edit")->whereNumber("id");
        Route::post('/request-item/{id}/edit', [RequestItemController::class, "update"] )->name("requestitem-update")->whereNumber("id");
        Route::post('/request-item/{id}', [RequestItemController::class, "destroy"] )->name("requestitem-destroy")->whereNumber("id");
    });

    // ===== ACCOUNTS ===== //
    Route::middleware('can_access_account')->group(function(){
        Route::get('/account', [AccountController::class, 'index'])->name('account.index');
        Route::get('/account/create', [AccountController::class, 'create'])->name('account.create');
        Route::post('/account/create', [AccountController::class, 'store'])->name('account.store');
        Route::get("/account/import-data", [AccountController::class, "import_user_form"])->name("account.import.form");
        Route::post("/account/import-data", [AccountController::class, "import_user_store"])->name("account.import.store");
        Route::get("/account/{id}", [AccountController::class, "show"])->name("account.show")->whereNumber("id");
        Route::put('/account/{id}', [AccountController::class, 'update'])->name('account.update')->whereNumber("id");
        Route::delete('/account/{id}', [AccountController::class, 'destroy'])->name('account.destroy')->whereNumber("id");
    });

    // ===== EMPLOYEES, PREPAYS, AND LEAVES ===== //
    Route::middleware('can_access_employee')->group(function(){
        // Employees
        Route::get("/employee", [EmployeeController::class, "index"])->name("employee-index");
        Route::get("/employee/{id}", [EmployeeController::class, "show"])->name("employee-show")->whereNumber("id");
        Route::get("/employee/create", [EmployeeController::class, "create"])->name("employee-create");
        Route::post("/employee/create", [EmployeeController::class, "store"])->name("employee-store");
        Route::get("/employee/{id}/edit", [EmployeeController::class, "edit"])->name("employee-edit")->whereNumber("id");
        Route::post("/employee/{id}/edit", [EmployeeController::class, "update"])->name("employee-update")->whereNumber("id");

        Route::get("/employee/manage-form", [EmployeeController::class, "manage_form"])->name("employee-manageform");
        Route::post("/employee/manage-form/add-position", [EmployeeController::class, "manage_form_add_position"])->name("employee-manageform-addposition");
        Route::post("/employee/manage-form/add-speciality", [EmployeeController::class, "manage_form_add_speciality"])->name("employee-manageform-addspeciality");
        Route::post("/employee/manage-form/{id}/edit-position", [EmployeeController::class, "manage_form_edit_position"])->name("employee-manageform-editposition")->whereNumber("id");
        Route::post("/employee/manage-form/{id}/edit-speciality", [EmployeeController::class, "manage_form_edit_speciality"])->name("employee-manageform-editspeciality")->whereNumber("id");
        Route::post("/employee/manage-form/{id}/delete-position", [EmployeeController::class, "manage_form_delete_position"])->name("employee-manageform-deleteposition")->whereNumber("id");
        Route::post("/employee/manage-form/{id}/delete-speciality", [EmployeeController::class, "manage_form_delete_speciality"])->name("employee-manageform-deletespeciality")->whereNumber("id");

        // Prepays
        Route::post("/employee/{id}/prepay/add", [PrepayController::class, "store"])->name("prepay-store")->whereNumber('id');
        Route::post("/employee/{emp_id}/prepay/{ppay_id}/edit", [PrepayController::class, "update"])->name("prepay-update")->whereNumber('emp_id')->whereNumber('ppay_id');
        Route::post("/employee/{emp_id}/prepay/{ppay_id}/delete", [PrepayController::class, "destroy"])->name("prepay-destroy")->whereNumber('emp_id')->whereNumber('ppay_id');

        // Leaves
        Route::get("/employee/leaves", [LeaveController::class, "admin_index"])->name('leave-admin-index');
        Route::post("/employee/leaves/{id}/approve", [LeaveController::class, "admin_approve"])->name('leave-admin-approve')->whereNumber('id');
        Route::post("/employee/leaves/{id}/reject", [LeaveController::class, "admin_reject"])->name('leave-admin-reject')->whereNumber('id');
    });

    // ===== SALARIES ===== //
    Route::middleware('can_access_salary')->group(function(){
        Route::get("/salary", [SalaryController::class, "index"])->name("salary-index");
        Route::post("/salary/auto-create", [SalaryController::class, "auto_create"])->name('salary-autocreate');
        Route::get("/salary/{id}/edit", [SalaryController::class, "edit"])->name("salary-edit")->whereNumber("id");
        Route::post("/salary/{id}/edit", [SalaryController::class, "update"])->name("salary-update")->whereNumber("id");
        Route::post("/salary/export", [PDFController::class, "export_salaries"])->name("salary-export")->whereNumber("id");
    });

    // ===== ATTENDANCES ===== //
    Route::middleware('can_access_attendance')->group(function(){
        Route::get("/attendance", [AttendanceController::class, "index"])->name("attendance-index");
        Route::get("/attendance/{id}", [AttendanceController::class, "show"])->name('attendance-show')->whereNumber("id");
        Route::get("/attendance/create/admin", [AttendanceController::class, "create_admin"])->name("attendance-create-admin");
        Route::post("/attendance/create/admin", [AttendanceController::class, "store_admin"])->name("attendance-store-admin");
        Route::get("/attendance/{id}/edit", [AttendanceController::class, "edit"])->name("attendance-edit")->whereNumber("id");
        Route::post("/attendance/{id}/edit", [AttendanceController::class, "update"])->name("attendance-update")->whereNumber("id");
        Route::post("/attendance/{id}/delete", [AttendanceController::class, "destroy"])->name("attendance-destroy")->whereNumber("id");
    });

    // ===== VISIT LOGS ===== //
    Route::middleware('can_access_visit_log')->group(function(){
        Route::get("/visit-log", [AccountController::class, "visit_log"])->name("visitlog-index");
    });

    // ===== PROPOSE LEAVES ===== //
    Route::middleware('can_access_propose_leave')->group(function(){
        Route::get("/employee/leaves/mine", [LeaveController::class, "user_index"])->name("leave-user-index");
        Route::get("/employee/leaves/mine/propose", [LeaveController::class, "user_propose"])->name("leave-user-propose");
        Route::post("/employee/leaves/mine/propose", [LeaveController::class, "user_propose_store"])->name("leave-user-propose-store");
    });

    // ===== SELF ATTENDANCES ===== //
    Route::middleware('can_access_self_attendance')->group(function(){
        Route::get("/attendance/self", [AttendanceController::class, "index_self"])->name("attendance-self-index");
        Route::get("/attendance/self/create/{project_id}/checkin", [AttendanceController::class, "check_in"])->name("attendance-self-checkin")->whereNumber('project_id');
        Route::post("/attendance/self/create/{project_id}/checkin", [AttendanceController::class, "check_in_store"])->name("attendance-self-checkin-store")->whereNumber('project_id');
        Route::get("/attendance/self/create/{project_id}/checkout", [AttendanceController::class, "check_out"])->name("attendance-self-checkout")->whereNumber('project_id');
        Route::post("/attendance/self/create/{project_id}/checkout", [AttendanceController::class, "check_out_store"])->name("attendance-self-checkout-store")->whereNumber('project_id');
    });
});

Auth::routes(["verify" => true]);

require __DIR__.'/auth.php';

