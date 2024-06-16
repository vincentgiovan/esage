<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Project;
use App\Models\Purchase;
use App\Models\DeliveryOrder;
use App\Models\DeliveryOrderProduct;
use App\Models\PurchaseProduct;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
        "name" => "admin",
        "email" => "admin@gmail.com",
        "password" => bcrypt("admin")]);

        Product::create([
            "product_name" => "papan kayu",
            "unit" => "batang",
            "status" => "active",
            "variant" => "papan panjang",
            "product_code" => "PapanKayu-001",
            "price" => 40000,
            "markup" => 0.0,
            "stock" => 50
        ]);
        Product::create([
            "product_name" => "paku",
            "unit" => "box",
            "status" => "active",
            "variant" => "paku payung",
            "product_code" => "PakuPayung-001",
            "price" => 30000,
            "markup" => 5.0,
            "stock" => 40
        ]);
        Product::create([
            "product_name" => "papan kayu",
            "unit" => "batang",
            "status" => "active",
            "variant" => "papan sedang",
            "product_code" => "PapanKayu-002",
            "price" => 20000,
            "markup" => 10.0,
            "stock" => 55
        ]);
        Product::create([
            "product_name" => "papan kayu",
            "unit" => "batang",
            "status" => "active",
            "variant" => "papan pendek",
            "product_code" => "PapanKayu-003",
            "price" => 15000,
            "markup" => 20.5,
            "stock" => 60
        ]);
        Product::create([
            "product_name" => "seng",
            "unit" => "buah",
            "status" => "active",
            "variant" => "zinc alum",
            "product_code" => "Seng-001",
            "price" => 70000,
            "markup" => 5.5,
            "stock" => 80
        ]);

        Partner::create([
            "role" => "supplier",
            "partner_name" => "Toko Jaya Agung",
            "address" => "Di bumi",
            "contact" => "0869-4646-xxxx",
            "phone" => "(069) 4646 xxxx",
            "fax" => "(04646) 6969 xxxx",
            "email" => "jayaagungshop@email.com"
        ]);
        Partner::create([
            "role" => "supplier",
            "partner_name" => "Toko Sinar Abadi",
            "address" => "Di bumi",
            "contact" => "0869-4646-xxxx",
            "phone" => "(069) 4646 xxxx",
            "fax" => "(04646) 6969 xxxx",
            "email" => "sinarabadishop@email.com"
        ]);

        Project::create([
            "project_name" => "Paskal 5",
            "location" => "Bandung",
            "address" => "Jalan Pasir Kaliki nomor sekian gatau lah",
            "pic" => "Peonang"
        ]);
        Project::create([
            "project_name" => "Ceres",
            "location" => "Bandung",
            "address" => "Jalan Moh Toha nomor sekian gatau lah",
            "pic" => "Peonita-channn uwuuu"
        ]);

        Purchase::create([
            "partner_id" => 1,
            "register" => "PU/25052024/1",
            "purchase_deadline" => "2024-05-28",
            "purchase_date" => "2024-05-25",
            "purchase_status" => "ordered",
        ]);
        Purchase::create([
            "partner_id" => 1,
            "register" => "PU/25052024/2",
            "purchase_deadline" => "2024-05-28",
            "purchase_date" => "2024-05-26",
            "purchase_status" => "retreived",
        ]);
        PurchaseProduct::create([
            "purchase_id" => 1,
            "product_id" => 2,
            "discount"=> 20,
            "quantity" => 10,
            "price" => 69000
        ]);
        PurchaseProduct::create([
            "purchase_id" => 1,
            "product_id" => 4,
            "discount"=> 30,
            "quantity" => 10,
            "price" => 69000
        ]);
        PurchaseProduct::create([
            "purchase_id" => 1,
            "product_id" => 1,
            "discount"=> 20,
            "quantity" => 80,
            "price" => 69000

        ]);
        PurchaseProduct::create([
            "purchase_id" => 2,
            "product_id" => 2,
            "discount"=> 20,
            "quantity" => 10,
            "price" => 69000
        ]);
        PurchaseProduct::create([
            "purchase_id" => 2,
            "product_id" => 3,
            "discount"=> 20,
            "quantity" => 10,
            "price" => 69000
        ]);

        DeliveryOrder::create([
            "delivery_date" => "2024-05-29",
            "register" => "DO/29052024/1",
            "delivery_status" => "incomplete",
            "project_id" => 1,
        ]);
        DeliveryOrder::create([
            "delivery_date" => "2024-05-31",
            "register" => "DO/29052024/2",
            "delivery_status" => "incomplete",
            "project_id" => 1,
        ]);

        DeliveryOrderProduct::create([
            "delivery_order_id" => 1,
            "product_id" => 3
        ]);
        DeliveryOrderProduct::create([
            "delivery_order_id" => 2,
            "product_id" => 2
        ]);
        DeliveryOrderProduct::create([
            "delivery_order_id" => 2,
            "product_id" => 3
        ]);
    }
}
