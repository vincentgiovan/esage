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

//testusgajadi

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
        "name" => "vincent",
        "email" => "vincent@gmail.com",
        "email_verified_at" => now(),
        "role" => 1,
        "password" => bcrypt("admin")]);

        User::create([
            "name" => "Vannes Theo S",
            "email" => "vannestheo@gmail.com",
            "role" => 1,
            "email_verified_at" => now(),
            "password" => bcrypt("2132-tV@pA")]);

        User::create([
            "name" => "Jemmy S",
            "email" => "jemmy@gmail.com",
            "role" => 2,
            "password" => bcrypt("jemmy123")]);

        Product::create([
            "product_name" => "Cat DuluX Interior Catylac",
            "unit" => "Pail",
            "status" => "Ready",
            "variant" => "Putih",
            "product_code" => "CatCatylac-001",
            "price" => 695000,
            "markup" => 0.0,
            "stock" => 12
        ]);
        Product::create([
            "product_name" => "Lem Fox",
            "unit" => "Bks",
            "status" => "Ready",
            "variant" => "Kuning",
            "product_code" => "LemKuning-001",
            "price" => 22000,
            "markup" => 5.0,
            "stock" => 10
        ]);
        Product::create([
            "product_name" => "Paku Beton",
            "unit" => "Dus",
            "status" => "Ready",
            "variant" => "3 inch",
            "product_code" => "PakuBeton-002",
            "price" => 15000,
            "markup" => 8.0,
            "stock" => 9
        ]);
        Product::create([
            "product_name" => "Paku Beton",
            "unit" => "Dus",
            "status" => "Ready",
            "variant" => "2 inch",
            "product_code" => "PakuBeton-001",
            "price" => 20000,
            "markup" => 10.0,
            "stock" => 35
        ]);
        Product::create([
            "product_name" => "Paku Rivet",
            "unit" => "Bh",
            "status" => "Ready",
            "variant" => "540",
            "product_code" => "PakuRivet-001",
            "price" => 145,
            "markup" => 2.0,
            "stock" => 350
        ]);
        Product::create([
            "product_name" => "Paku Kayu",
            "unit" => "Kg",
            "status" => "Ready",
            "variant" => "2 inch",
            "product_code" => "PakuKayu-001",
            "price" => 13000,
            "markup" => 7.0,
            "stock" => 5
        ]);
        Product::create([
            "product_name" => "Electrical",
            "unit" => "Bh",
            "status" => "Ready",
            "variant" => "Isolasi AC Non Lem",
            "product_code" => "Elektrical-001",
            "price" => 5000,
            "markup" => 10.5,
            "stock" => 60
        ]);

        Product::create([
            "product_name" => "Plumbing",
            "unit" => "Klg",
            "status" => "Ready",
            "variant" => "Lem Pipa",
            "product_code" => "Plumbing-215",
            "price" => 36000,
            "markup" => 0,
            "stock" => 4
        ]);

        Product::create([
            "product_name" => "Alat Perkakas",
            "unit" => "Bh",
            "status" => "Out Of Stock",
            "variant" => "Sikat Kawat",
            "product_code" => "AlatPerkakas-015",
            "price" => 4000,
            "markup" => 0,
            "stock" => 0
        ]);

        Product::create([
            "product_name" => "Dempul Tembok",
            "unit" => "Bks",
            "status" => "Out Of Stock",
            "variant" => "Attaboy",
            "product_code" => "Dempul-010",
            "price" => 30000,
            "markup" => 0,
            "stock" => 0
        ]);

        Product::create([
            "product_name" => "Aksesoris Jendela/Pintu",
            "unit" => "Bh",
            "status" => "Ready",
            "variant" => "Engsel Pintu Koboi",
            "product_code" => "AksesorisJendela/Pintu-005",
            "price" => 200000,
            "markup" => 0,
            "stock" => 12
        ]);
        Product::create([
            "product_name" => "Amplas",
            "unit" => "Mtr",
            "status" => "Ready",
            "variant" => "120",
            "product_code" => "Amplas-001",
            "price" => 5000,
            "markup" => 0,
            "stock" => 22
        ]);
        Product::create([
            "product_name" => "Cat DuluX Exterior Weathershield",
            "unit" => "Pail",
            "status" => "Ready",
            "variant" => "Base",
            "product_code" => "CatCatylac-021",
            "price" => 340000,
            "markup" => 0,
            "stock" => 2
        ]);
        Product::create([
            "product_name" => "	Batu Potong",
            "unit" => "Bh",
            "status" => "Out Of Stock",
            "variant" => "Circle",
            "product_code" => "BatuPotong-003",
            "price" => 40000,
            "markup" => 0,
            "stock" => 0
        ]);
        Product::create([
            "product_name" => "Lampu LED Brighton",
            "unit" => "Bh",
            "status" => "Ready",
            "variant" => "5 W",
            "product_code" => "Lampu-005",
            "price" => 4500,
            "markup" => 10,
            "stock" => 19
        ]);
        Product::create([
            "product_name" => "Cat Propan",
            "unit" => "Klg",
            "status" => "Ready",
            "variant" => "Ultran Lasur",
            "product_code" => "CatPropan-078",
            "price" => 94000,
            "markup" => 5,
            "stock" => 3
        ]);
        Product::create([
            "product_name" => "Elecrical",
            "unit" => "Bh",
            "status" => "Out Of Stock",
            "variant" => "Saklar AC - Clipsal",
            "product_code" => "ElectriKal-122",
            "price" => 40000,
            "markup" => 0,
            "stock" => 0
        ]);
        Product::create([
            "product_name" => "Electrical",
            "unit" => "Bh",
            "status" => "Out Of Stock",
            "variant" => "Tee Dus",
            "product_code" => "ElectriKal-051",
            "price" => 2000,
            "markup" => 0,
            "stock" => 0
        ]);
        Product::create([
            "product_name" => "Aspal Membran",
            "unit" => "Mtr",
            "status" => "Ready",
            "variant" => "Merk",
            "product_code" => "Aspal-001",
            "price" => 75000,
            "markup" => 4,
            "stock" => 20
        ]);
        Product::create([
            "product_name" => "Alat Pel",
            "unit" => "Bh",
            "status" => "Out Of Stock",
            "variant" => "Magic Mop",
            "product_code" => "AlatPel-005",
            "price" => 57000,
            "markup" => 0,
            "stock" => 0
        ]);

        //--------------------------------------------------------------------------------------------

        Partner::create([
            "role" => "supplier",
            "partner_name" => "BJ Home",
            "address" => "Jl. Jend. Ahmad Yani IBCC Blok E1. Bandung",
            "contact" => "xxxx xxxx xxxx",
            "phone" => "022 7208899",
            "fax" => "xxx xxxx xxxx",
            "email" => "data@email.com"
        ]);
        Partner::create([
            "role" => "supplier",
            "partner_name" => "Borma Toserba",
            "address" => "All Store - Bandung",
            "contact" => "xxxx xxxx xxxx",
            "phone" => "xxx xxxxx xxxx",
            "fax" => "xxx xxxx xxxx",
            "email" => "data@email.com"
        ]);

        Partner::create([
            "role" => "supplier",
            "partner_name" => "Cahaya Abadi",
            "address" => "Taman Kopo Indah II Ruko 3C No 23 Bandung",
            "contact" => "xxxx xxxx xxxx",
            "phone" => "xxx xxxxx xxxx",
            "fax" => "xxx xxxx xxxx",
            "email" => "data@email.com"
        ]);
        Partner::create([
            "role" => "supplier",
            "partner_name" => "	CV Giga Sukses Mandiri",
            "address" => "Store - Bandung",
            "contact" => "xxxx xxxx xxxx",
            "phone" => "xxx xxxx xxxx",
            "fax" => "xxx xxxx xxxx",
            "email" => "data@email.com"
        ]);
        Partner::create([
            "role" => "supplier",
            "partner_name" => "CV. Sinar Terang Sejahtera",
            "address" => "Jl. Karapitan 16-B Bandung",
            "contact" => "xxxx xxxx xxxx",
            "phone" => "xxxx xxxx xxxx",
            "fax" => "xxx xxxx xxxx",
            "email" => "data@email.com"
        ]);
        Partner::create([
            "role" => "supplier",
            "partner_name" => "Kim Hien",
            "address" => "ABC - Bandung",
            "contact" => "xxxx xxxx xxxx",
            "phone" => "xxxx xxxx xxxx",
            "fax" => "xxx xxxx xxxx",
            "email" => "data@email.com"
        ]);
        Partner::create([
            "role" => "supplier",
            "partner_name" => "Marema",
            "address" => "Jl. Suniaraja No.76 Bandung",
            "contact" => "085222222975",
            "phone" => "xxx xxxx xxxx",
            "fax" => "xxx xxxx xxxx",
            "email" => "data@email.com"
        ]);
        Partner::create([
            "role" => "supplier",
            "partner_name" => "CV. Muncul Jaya Teknik",
            "address" => "Gang Suniaraja No. 52 Bandung",
            "contact" => "xxxx xxxx xxxx",
            "phone" => "022 4263425",
            "fax" => "022 4263434",
            "email" => "data@email.com"
        ]);
        Partner::create([
            "role" => "supplier",
            "partner_name" => "CV. Karunia Hidup Teknik",
            "address" => "Jl. Banceuy No.31 Bandung",
            "contact" => "xxxx xxxx xxxx",
            "phone" => "xxxx xxxx xxxx",
            "fax" => "xxx xxxx xxxx",
            "email" => "data@email.com"
        ]);
        Project::create([
            "project_name" => "Paskal 5",
            "location" => "Bandung",
            "address" => "Jalan Pasir Kaliki no. 25-27",
            "pic" => "Joni Arif"
        ]);
        Project::create([
            "project_name" => "Hutanika",
            "location" => "Bandung",
            "address" => "Jalan Asia Afrika no. 91-97",
            "pic" => "Jemmi Agus Sugiarto "
        ]);
        Project::create([
            "project_name" => "Nara Park",
            "location" => "Bandung",
            "address" => "Jalan Rancabentang no. 28",
            "pic" => "Jemmi Agus Sugiarto "
        ]);
        Project::create([
            "project_name" => "Ceres Kanopi 94",
            "location" => "Bandung",
            "address" => "Jalan Raya Dayeuhkolot no. 94",
            "pic" => "Haryanto"
        ]);
        Project::create([
            "project_name" => "Chanaya Dago",
            "location" => "Bandung",
            "address" => "Jalan Dago Giri no. 102",
            "pic" => "Michael"
        ]);
        Project::create([
            "project_name" => "Arcamanik",
            "location" => "Bandung",
            "address" => "Jalan Cisaranten Kulon",
            "pic" => "Faisal SM"
        ]);
        Project::create([
            "project_name" => "RS Rayhan",
            "location" => "Subang",
            "address" => "Jalan Raya Sadang - Subang",
            "pic" => "Michael"
        ]);
        Project::create([
            "project_name" => "RS Intan Husada",
            "location" => "Garut",
            "address" => "Jalan Mayor Suherman no. 72",
            "pic" => "Haryanto"
        ]);
        Project::create([
            "project_name" => "Ruko Alfresco",
            "location" => "Bandung",
            "address" => "Jalan Pasir Kaliki no. 25-27",
            "pic" => "Joni Arif "
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
            "quantity" => 7,
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
            "product_id" => 3,
            "quantity" => 4
        ]);
        DeliveryOrderProduct::create([
            "delivery_order_id" => 2,
            "product_id" => 2,
            "quantity" => 1
        ]);
        DeliveryOrderProduct::create([
            "delivery_order_id" => 2,
            "product_id" => 3,
            "quantity" => 5
        ]);
    }
}
