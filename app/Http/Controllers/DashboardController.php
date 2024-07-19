<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\DeliveryOrder;

class DashboardController extends Controller
{
    public function index(){
        $currentMonth = date('Y-m-');

        return view("pages.dashboard", [
            "totalemptyproduct" => Product::where("stock", 0)->get()->count(),
            "totaldelivery" => DeliveryOrder::where("delivery_date", "like", $currentMonth . "%")->get()->count(),
            "totalpurchase" => Purchase::where("purchase_date", "like", $currentMonth . "%")->get()->count(),
        ]);

    }
}
