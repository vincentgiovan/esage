<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\DeliveryOrder;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // Halaman dashboard + logic penampilan data
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

        // Jumlah project baru dalam 1 bulan terakhir
        $totalnewproject = Project::where("created_at", "like", $currentMonth . "%")->get()->count();

        // Ambil data todo list
        $todos = Todo::where("user_id", Auth::user()->id)->get();

        // Tampilkan halaman pages/dashboard.blade.php beserta jumlah produk kosong juga total delivery order dan purchase di bulan ini
        return view("pages.dashboard", [
            "totalemptyproduct" => $totalEmptyProduct,
            "totaldelivery" => $totalDelivery,
            "totalpurchase" => $totalPurchase,
            "totalnewproject" => $totalnewproject,
            "todos" => $todos
        ]);

    }

}
