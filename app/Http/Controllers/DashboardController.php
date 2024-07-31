<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\DeliveryOrder;

class DashboardController extends Controller
{
    // Halaman dashboard
    public function index()
    {
        // Ambil data bulan dan tahun lalu format menjadi yyyy-mm-
        $currentMonth = date('Y-m-');

        // Jumlah produk yang kosong (ambil semua data dari tabel products yang stoknya 0, lalu ambil jumlahnya)
        $totalEmptyProduct = Product::where("stock", 0)->get()->count();

        // Jumlah delivery order yang terjadi di bulan ini (ambil semua data dari tabel delivery_orders yang delivery_date-nya punya tahun dan bulan yang sama seperti tanggal hari ini, lalu ambil jumlahnya)
        $totalDelivery = DeliveryOrder::where("delivery_date", "like", $currentMonth . "%")->get()->count();

        // Jumlah purchase yang terjadi di bulan ini (ambil semua data dari tabel purchases yang purchase_date-nya punya tahun dan bulan yang sama seperti tanggal hari ini, lalu ambil jumlahnya)
        $totalPurchase = Purchase::where("purchase_date", "like", $currentMonth . "%")->get()->count();

        // Tampilkan halaman pages/dashboard.blade.php beserta jumlah produk kosong juga total delivery order dan purchase di bulan ini
        return view("pages.dashboard", [
            "totalemptyproduct" => $totalEmptyProduct,
            "totaldelivery" => $totalDelivery,
            "totalpurchase" => $totalPurchase
        ]);

    }

}
