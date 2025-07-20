<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Prepay;
use App\Models\Salary;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Purchase;
use App\Models\PrepayCut;
use App\Models\Attendance;
use Illuminate\Http\Request;
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
            "products" => Product::all()
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
        $deliveryorders = DeliveryOrder::all();

        $data = [
            "deliveryorders" => $deliveryorders, // list product yang tercatat di delivery order yang ingin dicek cart-nya
        ];

        $pdf = Pdf::loadView('pdf.deliveryorder', $data)->setPaper("a4", ($mode == 1)? "landscape" : "portrait");

        return $pdf->stream('deliveryorder.pdf');
    }

    public function export_purchase($mode){
        // Targetkan delivery order semua
        $purchases = Purchase::all();

        $data = [
            "purchases" => $purchases, // list product yang tercatat di delivery order yang ingin dicek cart-nya
        ];

        $pdf = Pdf::loadView('pdf.purchase', $data)->setPaper("a4", ($mode == 1)? "landscape" : "portrait");

        return $pdf->stream('purchase.pdf');
    }

    public function export_partner($mode){
        // Targetkan delivery order semua
        $partners = Partner::all();

        $data = [
            "partners" => $partners, // list product yang tercatat di delivery order yang ingin dicek cart-nya
        ];

        $pdf = Pdf::loadView('pdf.partner', $data)->setPaper("a4", ($mode == 1)? "landscape" : "portrait");

        return $pdf->stream('partner.pdf');
    }

    public function export_project($mode){
        // Targetkan delivery order semua
        $projects = Project::all();

        $data = [
            "projects" => $projects, // list product yang tercatat di delivery order yang ingin dicek cart-nya
        ];

        $pdf = Pdf::loadView('pdf.project', $data)->setPaper("a4", ($mode == 1)? "landscape" : "portrait");

        return $pdf->stream('project.pdf');
    }

    public function export_salaries_pdf(Request $request){
        $groupedAttendances = Attendance::filter(request(['from', 'until', 'employee', 'project']))->with('project')
            ->orderBy('attendance_date', 'asc')
            ->orderBy(Employee::select('nama')
                ->whereColumn('id', 'attendances.employee_id')
                ->limit(1), 'asc')
            ->orderBy(Project::select('project_name')
                ->whereColumn('id', 'attendances.project_id')
                ->limit(1), 'asc')
            ->get()
            ->groupBy('employee_id');

        $prepaysInThisPeriod = Prepay::filter(request(['from', 'until', 'employee']))->where('enable_auto_cut', 'yes')->get()->groupBy('employee_id');

        $in_current_period = false;

        $today = Carbon::today();
        $rangeStart = Carbon::parse(request('from'));
        $rangeEnd = Carbon::parse(request('until'));

        if ($today >= $rangeStart && $today <= $rangeEnd) {
            $in_current_period = true;
        } else {
            $in_current_period = false;
        }

        $subtotals = collect(); // Use collection for better handling
        $total_all = 0;

        foreach ($groupedAttendances as $employee_id => $attendances) {
            $employee = $attendances->first()->employee; // Get the employee details

            if ($employee->kalkulasi_gaji == "on") {
                $total_salary = 0;

                foreach ($attendances as $atd) {
                    $sub_normal = $atd->normal * $employee->pokok;
                    $sub_lembur = $atd->jam_lembur * $employee->lembur;
                    $sub_lembur_panjang = $atd->index_lembur_panjang * $employee->lembur_panjang;
                    $sub_performa = $atd->performa * $employee->performa;

                    $total_salary += $sub_normal + $sub_lembur + $sub_lembur_panjang + $sub_performa;
                }

                // To show the prepay cut result on the given period
                if($in_current_period && isset($prepaysInThisPeriod[strval($employee_id)])){
                    foreach($prepaysInThisPeriod[strval($employee_id)] as $ppay){
                        if($total_salary > 0){
                            $total_salary -= $ppay->cut_amount;
                        }
                    }
                } else {
                    $prepay_cuts = PrepayCut::whereHas('prepay', function($query) use ($employee){
                        $query->where('employee_id', $employee->id);
                    })->where('start_period', request('from'))->where('end_period', request('until'))->get();

                    foreach($prepay_cuts as $ppc){
                        $total_salary -= $ppc->cut_amount;
                    }
                }

                $subtotals->put($employee_id, $total_salary);
                $total_all += $total_salary;
            }
        }

        $data = [
            "grouped_attendances" => $groupedAttendances,
            "subtotals" => $subtotals,
            "start_period" => $request->from,
            "end_period" => $request->until
        ];

        $pdf = Pdf::loadView('pdf.salary', $data)->setPaper("a4", "portrait");

        return $pdf->stream('salaries.pdf');
    }
}
