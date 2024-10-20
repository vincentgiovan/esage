<?php

use App\Http\Controllers\AccountController;
use App\Models\Product;
use App\Models\DeliveryOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReturnItemController;
use App\Http\Controllers\DeliveryOrderController;
use App\Http\Controllers\PurchaseProductController;
use App\Http\Controllers\DeliveryOrderProductController;
use App\Http\Controllers\EmployeeController;

Route::get('/', function(){
    return redirect("/dashboard");
})->name("home");

Route::middleware(["auth", "verified"])->group(function(){
    Route::get('/dashboard', [DashboardController::class, "index"] )->name("dashboard");

    Route::post("/dashboard/add-todo", [TodoController::class, "add_todo"])->name("todo-store");
    Route::post("/dashboard/save-todo", [TodoController::class, "save_todo"])->name("todo-update");

    Route::get("/profile", [AccountController::class, "edit_profile"])->name("profile-edit");
    Route::post("/profile", [AccountController::class, "update_profile"])->name("profile-update");

    // ===== DELIVERY ORDER ===== //

    //show data
    Route::get('/delivery-order', [DeliveryOrderController::class, "index"] )->name("deliveryorder-index");

    Route::middleware("admin")->group(function(){
        //create new data
        Route::get('/delivery-order/create', [DeliveryOrderController::class, "create"] )->name("deliveryorder-create");
        Route::post('/delivery-order/store', [DeliveryOrderController::class, "store"] )->name("deliveryorder-store");

        //import
        Route::get("/delivery-order/import", [DeliveryOrderController::class, "import_deliveryorder_form"])->name("deliveryorder-import");
        Route::post("/delivery-order/import", [DeliveryOrderController::class, "import_deliveryorder_store"])->name("deliveryorder-import-store");

        //edit data
        Route::get('/delivery-order/{id}/edit', [DeliveryOrderController::class, "edit"] )->name("deliveryorder-edit")->whereNumber("id");
        Route::post('/delivery-order/{id}/edit', [DeliveryOrderController::class, "update"] )->name("deliveryorder-update")->whereNumber("id");

        //delete data
        Route::post('/delivery-order/{id}', [DeliveryOrderController::class, "destroy"] )->name("deliveryorder-destroy")->whereNumber("id");

        //export
        Route::get("/delivery-order/export/{mode}", [PDFController::class, "export_deliveryorder"])->name("deliveryorder-export")->whereNumber("mode");
    });


    // ===== Product ===== //

    //show data
    Route::get('/product', [ProductController::class, "index"] )->name("product-index");

    Route::middleware("admin")->group(function(){
        //create new data
        Route::get('/product/create', [ProductController::class, "create"] )->name("product-create");
        Route::post('/product/store', [ProductController::class, "store"] )->name("product-store");

        //import
        Route::get("/product/import", [ProductController::class, "import_product_form"])->name("product-import");
        Route::post("/product/import", [ProductController::class, "import_product_store"])->name("product-import-store");

        //edit data
        Route::get('/product/{id}/edit', [ProductController::class, "edit"] )->name("product-edit")->whereNumber("id");
        Route::post('/product/{id}/edit', [ProductController::class, "update"] )->name("product-update")->whereNumber("id");

        //delete data
        Route::post('/product/{id}', [ProductController::class, "destroy"] )->name("product-destroy")->whereNumber("id");

        //export
        Route::get("/product/export/{mode}", [PDFController::class, "export_product"])->name("product-export")->whereNumber("mode");

        //purchase log
        Route::get("/product/{id}/log", [ProductController::class, "view_log"])->name("product-log")->whereNumber("id");
    });


    // ===== Partner ===== //

    //show data
    Route::get('/partner', [PartnerController::class, "index"] )->name("partner-index");

    Route::middleware("admin")->group(function(){
        //create new data
        Route::get('/partner/create', [PartnerController::class, "create"] )->name("partner-create");
        Route::post('/partner/store', [PartnerController::class, "store"] )->name("partner-store");

        //import
        Route::get("/partner/import", [PartnerController::class, "import_partner_form"])->name("partner-import");
        Route::post("/partner/import", [PartnerController::class, "import_partner_store"])->name("partner-import-store");

        //edit data
        Route::get('/partner/{id}/edit', [PartnerController::class, "edit"] )->name("partner-edit")->whereNumber("id");
        Route::post('/partner/{id}/edit', [PartnerController::class, "update"] )->name("partner-update")->whereNumber("id");

        //delete data
        Route::post('/partner/{id}', [PartnerController::class, "destroy"] )->name("partner-destroy")->whereNumber("id");

        //export
        Route::get("/partner/export/{mode}", [PDFController::class, "export_partner"])->name("partner-export")->whereNumber("mode");

        //partner log
        Route::get("/partner/{id}/log", [PartnerController::class, "view_log"])->name("partner-log")->whereNumber("id");
    });


    // ===== Project ===== //

    //show data
    Route::get('/project', [ProjectController::class, "index"] )->name("project-index");

    Route::middleware("admin")->group(function(){
        //create new data
        Route::get('/project/create', [ProjectController::class, "create"] )->name("project-create");
        Route::post('/project/store', [ProjectController::class, "store"] )->name("project-store");

        //import
        Route::get("/project/import", [ProjectController::class, "import_project_form"])->name("project-import");
        Route::post("/project/import", [ProjectController::class, "import_project_store"])->name("project-import-store");

        //edit data
        Route::get('/project/{id}/edit', [ProjectController::class, "edit"] )->name("project-edit")->whereNumber("id");
        Route::post('/project/{id}/edit', [ProjectController::class, "update"] )->name("project-update")->whereNumber("id");

        //delete data
        Route::post('/project/{id}', [ProjectController::class, "destroy"] )->name("project-destroy")->whereNumber("id");

        //export
        Route::get("/project/export/{mode}", [PDFController::class, "export_project"])->name("project-export")->whereNumber("mode");

        //project log
        Route::get("/project/{id}/log", [ProjectController::class, "view_log"])->name("project-log")->whereNumber("id");
    });


    // ===== Purchase ===== //

    //show data
    Route::get('/purchase', [PurchaseController::class, "index"] )->name("purchase-index");

    Route::middleware("admin")->group(function(){
        //create new data
        Route::get('/purchase/create', [PurchaseController::class, "create"] )->name("purchase-create");
        Route::post('/purchase/store', [PurchaseController::class, "store"] )->name("purchase-store");

        //import
        Route::get("/purchase/import", [PurchaseController::class, "import_purchase_form"])->name("purchase-import");
        Route::post("/purchase/import", [PurchaseController::class, "import_purchase_store"])->name("purchase-import-store");

        //edit data
        Route::get('/purchase/{id}/edit', [PurchaseController::class, "edit"] )->name("purchase-edit")->whereNumber("id");
        Route::post('/purchase/{id}/edit', [PurchaseController::class, "update"] )->name("purchase-update")->whereNumber("id");

        //delete data
        Route::post('/purchase/{id}', [PurchaseController::class, "destroy"] )->name("purchase-destroy")->whereNumber("id");

        //export
        Route::get("/purchase/export/{mode}", [PDFController::class, "export_purchase"])->name("purchase-export")->whereNumber("mode");
    });


    // ===== PurchaseProduct ===== //

    //show data
    Route::get('/purchase-product/{id}/viewitem', [PurchaseProductController::class, "view_items"] )->name("purchaseproduct-viewitem")->whereNumber("id");

    Route::middleware("admin")->group(function(){
        //import
        Route::get("/purchase-product/{id}/import", [PurchaseProductController::class, "import_purchaseproduct_form"])->name("purchaseproduct-import")->whereNumber("id");
        Route::post("/purchase-product/{id}/import", [PurchaseProductController::class, "import_purchaseproduct_store"])->name("purchaseproduct-import-store")->whereNumber("id");

        //add existing products to a purchase
        Route::get('/purchase-product/{id}/create1', [PurchaseProductController::class, "add_existing_product"] )->name("purchaseproduct-create1")->whereNumber("id");
        Route::post('/purchase-product/{id}/store1', [PurchaseProductController::class, "store_existing_product"] )->name("purchaseproduct-store1")->whereNumber("id");

        //add unexisting products to a purchase also to the product database
        Route::get('/purchase-product/{id}/create2', [PurchaseProductController::class, "add_new_product"] )->name("purchaseproduct-create2")->whereNumber("id");
        Route::post('/purchase-product/{id}/store2', [PurchaseProductController::class, "store_new_product"] )->name("purchaseproduct-store2")->whereNumber("id");

        //delete data
        Route::post('/purchase-product/{id}/{pid}', [PurchaseProductController::class, "destroy"] )->name("purchaseproduct-destroy")->whereNumber("pid");

        //export data
        Route::get("/purchase-product/{id}/export/{mode}", [PDFController::class, "export_purchase_product"])->name("purchaseproduct-export")->whereNumber("id");
    });


    // ===== DeliveryProduct ===== //

    //show data
    Route::get('/delivery-order-product/{id}/viewitem', [DeliveryOrderProductController::class, "view_items"] )->name("deliveryorderproduct-viewitem")->whereNumber("id");

    Route::middleware("admin")->group(function(){
        //import
        Route::get("/delivery-order-product/{id}/import", [DeliveryOrderProductController::class, "import_deliveryorderproduct_form"])->name("deliveryorderproduct-import")->whereNumber("id");
        Route::post("/delivery-order-product/{id}/import", [DeliveryOrderProductController::class, "import_deliveryorderproduct_store"])->name("deliveryorderproduct-import-store")->whereNumber("id");

        //add existing products to a purchase
        Route::get('/delivery-order-product/{id}/create1', [DeliveryOrderProductController::class, "add_existing_product"] )->name("deliveryorderproduct-create1")->whereNumber("id");
        Route::post('/delivery-order-product/{id}/store1', [DeliveryOrderProductController::class, "store_existing_product"] )->name("deliveryorderproduct-store1")->whereNumber("id");

        //add unexisting products to a purchase also to the product database
        Route::get('/delivery-order-product/{id}/create2', [DeliveryOrderProductController::class, "add_new_product"] )->name("deliveryorderproduct-create2")->whereNumber("id");
        Route::post('/delivery-order-product/{id}/store2', [DeliveryOrderProductController::class, "store_new_product"] )->name("deliveryorderproduct-store2")->whereNumber("id");

        //delete data
        Route::post('/delivery-order-product/{id}/{pid}', [DeliveryOrderProductController::class, "destroy"] )->name("deliveryorderproduct-destroy")->whereNumber("id")->whereNumber("pid");

        //export data
        Route::get("/delivery-order-product/{id}/export/{mode}", [PDFController::class, "export_deliveryorder_product"])->name("deliveryorderproduct-export")->whereNumber("id")->whereNumber("mode");
    });


    // ===== RETURN ITEM ===== //

    //show data
    Route::get('/return-item', [ReturnItemController::class, "index"] )->name("return-item-index");

    Route::middleware("admin")->group(function(){
        //create new data
        Route::get('/return-item/create', [ReturnItemController::class, "create"] )->name("returnitem-create");
        Route::post('/return-item/store', [ReturnItemController::class, "store"] )->name("returnitem-store");

        //import
        Route::get("/return-item/import", [ReturnItemController::class, "import_returnitem_form"])->name("returnitem-import");
        Route::post("/return-item/import", [ReturnItemController::class, "import_returnitem_store"])->name("returnitem-import-store");

        //edit data
        Route::get('/return-item/{id}/edit', [ReturnItemController::class, "edit"] )->name("return-item-edit")->whereNumber("id");
        Route::post('/return-item/{id}/edit', [ReturnItemController::class, "update"] )->name("return-item-update")->whereNumber("id");

        //delete data
        Route::post('/return-item/{id}', [ReturnItemController::class, "destroy"] )->name("return-item-destroy")->whereNumber("id");

        //export
        Route::get("/return-item/export/{mode}", [PDFController::class, "export_returnitem"])->name("return-item-export")->whereNumber("mode");
    });


    Route::middleware("admin")->group(function(){
        // ===== ACCOUNTS ===== //

        Route::get('/account', [AccountController::class, 'index'])->name('account.index');
        Route::post('/account', [AccountController::class, 'store'])->name('account.store');
        Route::get("/account/import-data", [AccountController::class, "import_user_form"])->name("account.import.form");
        Route::post("/account/import-data", [AccountController::class, "import_user_store"])->name("account.import.store");
        Route::get("/account/{id}", [AccountController::class, "show"])->name("account.show")->whereNumber("id");
        Route::put('/account/{id}', [AccountController::class, 'update'])->name('account.update')->whereNumber("id");
        Route::delete('/account/{id}', [AccountController::class, 'destroy'])->name('account.destroy')->whereNumber("id");

        // ===== EMPLOYEES ===== //
        Route::get("/employee", [EmployeeController::class, "index"])->name("employee-index");
        Route::get("/employee/{id}", [EmployeeController::class, "show"])->name("employee-show")->whereNumber("id");
        Route::get("/employee/{id}/edit", [EmployeeController::class, "edit"])->name("employee-edit")->whereNumber("id");
        Route::post("/employee/{id}/edit", [EmployeeController::class, "update"])->name("employee-update")->whereNumber("id");

        Route::get("/employee/manage-form", [EmployeeController::class, "manage_form"])->name("employee-manageform");
        Route::post("/employee/manage-form/add-position", [EmployeeController::class, "manage_form_add_position"])->name("employee-manageform-addposition");
        Route::post("/employee/manage-form/add-speciality", [EmployeeController::class, "manage_form_add_speciality"])->name("employee-manageform-addspeciality");
        Route::post("/employee/manage-form/{id}/edit-position", [EmployeeController::class, "manage_form_edit_position"])->name("employee-manageform-editposition")->whereNumber("id");
        Route::post("/employee/manage-form/{id}/edit-speciality", [EmployeeController::class, "manage_form_edit_speciality"])->name("employee-manageform-editspeciality")->whereNumber("id");
        Route::post("/employee/manage-form/{id}/delete-position", [EmployeeController::class, "manage_form_delete_position"])->name("employee-manageform-deleteposition")->whereNumber("id");
        Route::post("/employee/manage-form/{id}/delete-speciality", [EmployeeController::class, "manage_form_delete_speciality"])->name("employee-manageform-deletespeciality")->whereNumber("id");
    });


    Route::get("/request", function(){
        return view("pages.request.index", [
            "products" => Product::all()
        ]);
    })->name("request-index");

});

Auth::routes(["verify"=>true]);
require __DIR__.'/auth.php';

