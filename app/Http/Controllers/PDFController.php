<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Product;
use App\Models\Project;
use App\Models\Purchase;
use App\Models\DeliveryOrder;
use App\Models\PurchaseProduct;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DeliveryOrderProduct;

class PDFController extends Controller
{
    // Export data produk
    public function export_product($mode)
    {
        $data = [
            "products" => Product::where('archived', 0)->get()
        ];

        $pdf = Pdf::loadView('pdf.allproduct', $data)->setPaper("a4", ($mode == 1)? "landscape" : "portrait");

        return $pdf->stream('allproduct.pdf');
    }

    // Export data purchase product
    public function export_purchase_product($id, $mode)
    {
        // Targetkan purchase yang ingin ditampilkan cart-nya
        $purchase = Purchase::find($id);

        // Ambil data dari purchase product yang purchase_id-nya sama kayak purchase yang mau ditampilin cart-nya
        $pp = PurchaseProduct::where("purchase_id", $purchase->id)->get();

        $data = [
            "purchase" => $purchase, // data purchase yang mau ditampilkan cart-nya
            "pp"=> $pp // produk-produk yang terkait purchase tersebut
        ];

        $pdf = Pdf::loadView('pdf.purchaseproduct', $data)->setPaper("a4", ($mode == 1)? "landscape" : "portrait");

        return $pdf->stream('purchaseproduct.pdf');
    }

    // Export data delivery order product
    public function export_deliveryorder_product($id, $mode){
        // Targetkan delivery order yang dipilih yang mau dicek list produknya
        $deliveryorder = DeliveryOrder::find($id);

        // Ambil data produk yang tercatat dalam delivery order tersebut (tabel delivery_orders tidak menyimpan data produk karena relation many to many, jadi ambil data dari tabel perantara, ambil semua yang delivery_order_id-nya sama kayak delivery_order yang dipilih)
        $do = DeliveryOrderProduct::where("delivery_order_id", $deliveryorder->id)->get();

        $data = [
            "deliveryorder" => $deliveryorder, // list product yang tercatat di delivery order yang ingin dicek cart-nya
            "do"=> $do // data delivery order yang ingin dicek cart-nya
        ];

        $pdf = Pdf::loadView('pdf.deliveryorderproduct', $data)->setPaper("a4", ($mode == 1)? "landscape" : "portrait");

        return $pdf->stream('deliveryorderproduct.pdf');
    }

    // Export semua delvery order
    public function export_deliveryorder($mode){
        // Targetkan delivery order semua
        $deliveryorders = DeliveryOrder::where('archived', 0)->get();

        $data = [
            "deliveryorders" => $deliveryorders, // list product yang tercatat di delivery order yang ingin dicek cart-nya
        ];

        $pdf = Pdf::loadView('pdf.deliveryorder', $data)->setPaper("a4", ($mode == 1)? "landscape" : "portrait");

        return $pdf->stream('deliveryorder.pdf');
    }

    public function export_purchase($mode){
        // Targetkan delivery order semua
        $purchases = Purchase::where('archived', 0)->get();

        $data = [
            "purchases" => $purchases, // list product yang tercatat di delivery order yang ingin dicek cart-nya
        ];

        $pdf = Pdf::loadView('pdf.purchase', $data)->setPaper("a4", ($mode == 1)? "landscape" : "portrait");

        return $pdf->stream('purchase.pdf');
    }

    public function export_partner($mode){
        // Targetkan delivery order semua
        $partners = Partner::where('archived', 0)->get();

        $data = [
            "partners" => $partners, // list product yang tercatat di delivery order yang ingin dicek cart-nya
        ];

        $pdf = Pdf::loadView('pdf.partner', $data)->setPaper("a4", ($mode == 1)? "landscape" : "portrait");

        return $pdf->stream('partner.pdf');
    }

    public function export_project($mode){
        // Targetkan delivery order semua
        $projects = Project::where('archived', 0)->get();

        $data = [
            "projects" => $projects, // list product yang tercatat di delivery order yang ingin dicek cart-nya
        ];

        $pdf = Pdf::loadView('pdf.project', $data)->setPaper("a4", ($mode == 1)? "landscape" : "portrait");

        return $pdf->stream('project.pdf');
    }
}
