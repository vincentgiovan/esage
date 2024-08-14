<?php

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveryOrderController;
use App\Http\Controllers\AccountCreationController;
use App\Http\Controllers\PurchaseProductController;
use App\Http\Controllers\DeliveryOrderProductController;
use App\Http\Controllers\TodoController;

Route::get('/', function(){
    return redirect("/dashboard");
})->name("home");

Route::middleware(["auth","verified"])->group(function(){
    Route::get('/dashboard', [DashboardController::class, "index"] )->name("dashboard");

    Route::post("/dashboard/add-todo", [TodoController::class, "add_todo"])->name("todo.store");
    Route::post("/dashboard/save-todo", [TodoController::class, "save_todo"])->name("todo.update");

    // ===== DELIVERY ORDER ===== //
    //show data
    Route::get('/delivery-order', [DeliveryOrderController::class, "index"] )->name("deliveryorder-index");

    //create new data
    Route::get('/delivery-order/create', [DeliveryOrderController::class, "create"] )->name("deliveryorder-create");
    Route::post('/delivery-order/store', [DeliveryOrderController::class, "store"] )->name("deliveryorder-store");

    //edit data
    Route::get('/delivery-order/{id}/edit', [DeliveryOrderController::class, "edit"] )->name("deliveryorder-edit");
    Route::post('/delivery-order/{id}/edit', [DeliveryOrderController::class, "update"] )->name("deliveryorder-update");

    //delete data
    Route::post('/delivery-order/{id}', [DeliveryOrderController::class, "destroy"] )->name("deliveryorder-destroy");

    // ===== Product ===== //

    //show data
    Route::get('/product', [ProductController::class, "index"] )->name("product-index");

    Route::middleware("admin")->group(function(){
        //create new data
        Route::get('/product/create', [ProductController::class, "create"] )->name("product-create");
        Route::post('/product/store', [ProductController::class, "store"] )->name("product-store");

        //edit data
        Route::get('/product/{id}/edit', [ProductController::class, "edit"] )->name("product-edit");
        Route::post('/product/{id}/edit', [ProductController::class, "update"] )->name("product-update");

        //delete data
        Route::post('/product/{id}', [ProductController::class, "destroy"] )->name("product-destroy");

        //export
        Route::get("/product/export/{mode}", [PDFController::class, "export_product"])->name("product-export");
    });

    // ===== Partner ===== //

    //show data
    Route::get('/partner', [PartnerController::class, "index"] )->name("partner-index");

    //create new data
    Route::get('/partner/create', [PartnerController::class, "create"] )->name("partner-create");
    Route::post('/partner/store', [PartnerController::class, "store"] )->name("partner-store");

    //edit data
    Route::get('/partner/{id}/edit', [PartnerController::class, "edit"] )->name("partner-edit");
    Route::post('/partner/{id}/edit', [PartnerController::class, "update"] )->name("partner-update");

    //delete data
    Route::post('/partner/{id}', [PartnerController::class, "destroy"] )->name("partner-destroy");


    // ===== Project ===== //

    //show data
    Route::get('/project', [ProjectController::class, "index"] )->name("project-index");

    //create new data
    Route::get('/project/create', [ProjectController::class, "create"] )->name("project-create");
    Route::post('/project/store', [ProjectController::class, "store"] )->name("project-store");

    //edit data
    Route::get('/project/{id}/edit', [ProjectController::class, "edit"] )->name("project-edit");
    Route::post('/project/{id}/edit', [ProjectController::class, "update"] )->name("project-update");

    //delete data
    Route::post('/project/{id}', [ProjectController::class, "destroy"] )->name("project-destroy");


    // ===== Purchase ===== //

    //show data
    Route::get('/purchase', [PurchaseController::class, "index"] )->name("purchase-index");

    //create new data
    Route::get('/purchase/create', [PurchaseController::class, "create"] )->name("purchase-create");
    Route::post('/purchase/store', [PurchaseController::class, "store"] )->name("purchase-store");

    //edit data
    Route::get('/purchase/{id}/edit', [PurchaseController::class, "edit"] )->name("purchase-edit");
    Route::post('/purchase/{id}/edit', [PurchaseController::class, "update"] )->name("purchase-update");

    //delete data
    Route::post('/purchase/{id}', [PurchaseController::class, "destroy"] )->name("purchase-destroy");


    // ===== PurchaseProduct ===== //

    //show data
    Route::get('/purchase-product/{id}/viewitem', [PurchaseProductController::class, "view_items"] )->name("purchaseproduct-viewitem");

    //add existing products to a purchase
    Route::get('/purchase-product/{id}/create1', [PurchaseProductController::class, "add_existing_product"] )->name("purchaseproduct-create1");
    Route::post('/purchase-product/{id}/store1', [PurchaseProductController::class, "store_existing_product"] )->name("purchaseproduct-store1");

    //add unexisting products to a purchase also to the product database
    Route::get('/purchase-product/{id}/create2', [PurchaseProductController::class, "add_new_product"] )->name("purchaseproduct-create2");
    Route::post('/purchase-product/{id}/store2', [PurchaseProductController::class, "store_new_product"] )->name("purchaseproduct-store2");

    //delete data
    Route::post('/purchase-product/{id}/{pid}', [PurchaseProductController::class, "destroy"] )->name("purchaseproduct-destroy");

    //export data
    Route::get("/purchase-product/{id}/export/{mode}", [PDFController::class, "export_purchase_product"])->name("purchaseproduct-export");


    // ===== DeliveryProduct ===== //
    //show data
    Route::get('/delivery-order-product/{id}/viewitem', [DeliveryOrderProductController::class, "view_items"] )->name("deliveryorderproduct-viewitem");

    //add existing products to a purchase
    Route::get('/delivery-order-product/{id}/create1', [DeliveryOrderProductController::class, "add_existing_product"] )->name("deliveryorderproduct-create1");
    Route::post('/delivery-order-product/{id}/store1', [DeliveryOrderProductController::class, "store_existing_product"] )->name("deliveryorderproduct-store1");

    //add unexisting products to a purchase also to the product database
    Route::get('/delivery-order-product/{id}/create2', [DeliveryOrderProductController::class, "add_new_product"] )->name("deliveryorderproduct-create2");
    Route::post('/delivery-order-product/{id}/store2', [DeliveryOrderProductController::class, "store_new_product"] )->name("deliveryorderproduct-store2");

    //delete data
    Route::post('/delivery-order-product/{id}/{pid}', [DeliveryOrderProductController::class, "destroy"] )->name("deliveryorderproduct-destroy");

    //export data
    Route::get("/delivery-order-product/{id}/export/{mode}", [PDFController::class, "export_deliveryorder_product"])->name("deliveryorderproduct-export");


    //account route


    // Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/account', [AccountCreationController::class, 'index'])->name('account.index');
    Route::post('/account', [AccountCreationController::class, 'store'])->name('account.store');
    Route::get("/account/{id}", [AccountCreationController::class, "show"])->name("account.show");
    Route::put('/account/{id}', [AccountCreationController::class, 'update'])->name('account.update');
    Route::delete('/account/{id}', [AccountCreationController::class, 'destroy'])->name('account.destroy');
// });

    Route::get("/request", function(){
        return view("pages.request.index", [
            "products" => Product::all()
        ]);
    })->name("request-index");

    Route::get("/return", function(){
        return view("pages.return.index", [
            "products" => Product::all()
        ]);
    })->name("return-index");


    Route::get("/delivery-order-product", function(){
        return redirect(route("deliveryorder-index"));
    });

    Route::get("/purchase-product", function(){
        return redirect(route("purchase-index"));
    });

});

Auth::routes(["verify"=>true]);
require __DIR__.'/auth.php';

