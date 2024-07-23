<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\DeliveryOrder;
use App\Models\PurchaseProduct;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DeliveryOrderProduct;

class PDFController extends Controller
{
    public function export_product($mode)
    {
        $data = [
            "products" => Product::all()
        ];

        $pdf = Pdf::loadView('pdf.allproduct', $data)->setPaper("a4", ($mode == 1)? "landscape" : "portrait");

        return $pdf->stream('allproduct.pdf');
    }

    public function export_purchase_product($id, $mode)
    {
        // Targetkan purchase yang ingin ditampilkan cart-nya
        $purchase = Purchase::where("id", $id)->first();

        // Ambil data dari purchase product yang purchase_id-nya sama kayak purchase yang mau ditampilin cart-nya
        $pp = PurchaseProduct::where("purchase_id", $purchase->id)->get();

        $data = [
            "purchase" => $purchase, // data purchase yang mau ditampilkan cart-nya
            "pp"=> $pp // produk-produk yang terkait purchase tersebut
        ];

        $pdf = Pdf::loadView('pdf.purchaseproduct', $data)->setPaper("a4", ($mode == 1)? "landscape" : "portrait");

        return $pdf->stream('purchaseproduct.pdf');
    }

    public function export_deliveryorder_product($id, $mode){
        // Targetkan delivery order yang dipilih yang mau dicek list produknya
        $deliveryorder = DeliveryOrder::where("id", $id)->first();

        // Ambil data produk yang tercatat dalam delivery order tersebut (tabel delivery_orders tidak menyimpan data produk karena relation many to many, jadi ambil data dari tabel perantara, ambil semua yang delivery_order_id-nya sama kayak delivery_order yang dipilih)
        $do = DeliveryOrderProduct::where("delivery_order_id", $deliveryorder->id)->get();

        $data = [
            "deliveryorder" => $deliveryorder, // list product yang tercatat di delivery order yang ingin dicek cart-nya
            "do"=> $do // data delivery order yang ingin dicek cart-nya
        ];

        $pdf = Pdf::loadView('pdf.deliveryorderproduct', $data)->setPaper("a4", ($mode == 1)? "landscape" : "portrait");

        return $pdf->stream('deliveryorderproduct.pdf');
    }
}
