<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeliveryOrderProductController extends Controller
{
    // To show existing products in a purchase
    public function view_items($id){

    }

    // To add an existing product to a purchase
    public function add_existing_product($id){

    }

    // To store the existing product to the purchase
    public function store_existing_product(Request $request, $id){

    }

    // To add an unexisting product to a purchase
    public function add_new_product($id){

    }

    // To store the unexisting product to the purchase also adding it to all products
    public function store_new_product(Request $request, $id){

    }

    // To remove a product from a purchase
    public function destroy($id){

    }
}
