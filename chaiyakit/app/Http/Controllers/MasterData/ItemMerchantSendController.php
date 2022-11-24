<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MasterRound;
use App\Models\MasterTruckType;
use App\Models\MasterTruck;
use App\Models\OrderDelivery;
use App\Models\OrderDeliveryItem;
use App\Models\TruckSchedule;
use App\Models\MasterMerchant;
use App\Models\MasterProductMain;
use App\Models\MasterProductSize;
use App\Models\MasterProductType;
use App\Models\Tools;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Validator;
use DataTables;
use DB;

class ItemMerchantSendController extends Controller
{
    /**
     * instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function index()
    {
        return view('pages.master_data.item_merchant_send.index');
    }


    public function getItemList(Request $request)
    {
        $Order = OrderDelivery::select(
            'merchant.name_merchant',
            'orders.department_name',
            'orders.merchant_id',
            'truck_schedule.date_schedule',
            'truck_schedule.date_schedule as date_send',
            DB::raw('sum( delivery_item.qty ) AS sum_order_delivery_item')
        )
            ->leftjoin('truck_schedule', 'truck_schedule.order_delivery_id', '=', 'order_delivery.order_delivery_id')
            ->leftjoin('orders', 'orders.order_id', '=', 'order_delivery.order_id')
            ->leftjoin('order_delivery_item as delivery_item', 'delivery_item.order_delivery_id', '=', 'order_delivery.order_delivery_id')
            ->leftjoin('master_merchant as merchant', 'merchant.master_merchant_id', '=', 'orders.merchant_id')
            ->groupBy(
                'orders.merchant_id',
                'truck_schedule.date_schedule'
            );

        if ($request->has('search') && !empty($request->search)) {
            $Order->where('merchant.name_merchant', 'LIKE', $request->search . '%');
            $Order->orWhere('orders.department_name', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->has('date_start') && !empty($request->date_start) && $request->has('date_end') && !empty($request->date_end)) {
            $Order->whereDate('truck_schedule.date_schedule', '>=', Carbon::parse($request->date_start)->format('Y-m-d'));
            $Order->whereDate('truck_schedule.date_schedule', '<=', Carbon::parse($request->date_end)->format('Y-m-d'));
        }else{
            $Order->whereDate('truck_schedule.date_schedule', '>=', Carbon::parse()->format('Y-m-d'));
            $Order->whereDate('truck_schedule.date_schedule', '<=', Carbon::parse()->format('Y-m-d'));
        }

        if(\Auth::user()->hasRole('admin')){
            $Order->where('orders.order_type', '00');
        }
        $Order->where(function($q) {
            $q->where('order_delivery.status','01')->orWhere('order_delivery.status','05');
        });
        $Order->orderby('truck_schedule.date_schedule', 'desc');

        return Datatables::of($Order)->editColumn('date_schedule', '{{ formatDateThat($date_schedule)}}')->editColumn('date_send', '{{ formatDateTime($date_send)}}')->make(true);
    }

    public function itemmerchantdate($id,$date)
    {
        $datawhere = date('Y-m-d',$date);
            $Orders = OrderDelivery::select(
                'truck_schedule.date_schedule',
                'master_product_main.name as product_name',
                'master_product_type.name as product_type_name',
                'master_product_size.name AS product_size_name',
                'master_product_main.formula',
                'master_product_size.weight as product_weight',
                'order_item.size_unit',
                'delivery_item.qty',
                DB::raw("case when master_product_main.formula = '01' then master_product_size.name*0.35 else master_product_size.name end AS product_size_name_sum"),
            )
            ->leftjoin('truck_schedule', 'truck_schedule.order_delivery_id', '=', 'order_delivery.order_delivery_id')
            ->leftjoin('orders', 'orders.order_id', '=', 'order_delivery.order_id')
            ->leftjoin('order_delivery_item as delivery_item', 'delivery_item.order_delivery_id', '=', 'order_delivery.order_delivery_id')
            ->leftjoin('master_merchant as merchant', 'merchant.master_merchant_id', '=', 'orders.merchant_id')
            ->leftjoin('order_item', 'order_item.order_item_id', '=', 'delivery_item.order_item_id')
            ->leftjoin('master_product_main', 'master_product_main.master_product_main_id', '=', 'order_item.master_product_main_id')
            ->leftjoin('master_product_type', 'master_product_type.master_product_type_id', '=', 'order_item.master_product_type_id')
            ->leftjoin('master_product_size', 'master_product_size.master_product_size_id', '=', 'order_item.master_product_size_id')
           ->whereDate('truck_schedule.date_schedule', '>=', Carbon::parse($datawhere)->format('Y-m-d'))->whereDate('truck_schedule.date_schedule', '<=', Carbon::parse($datawhere)->format('Y-m-d'))
           ->where('orders.merchant_id',$id)
           ->get();
            foreach ($Orders as $row) {
                $weight_data = Tools::convertdWeight($row->product_weight);
                $row->total_size_text = ($row->formula == '01') ? round($row->total_size, 4) . ' ตร.ม.' : round($row->total_size, 2) . ' ' . config('sizeunit')[$row->size_unit];
                $row->product_weight_text = $weight_data['number'] . ' ' . $weight_data['unit'];
                $row->product_size_name_text = ($row->formula == '01') ? '0.35x' . sprintf('%g', $row->product_size_name) . ' ' . config('sizeunit')[$row->size_unit] : sprintf('%g', $row->product_size_name) . ' ' . config('sizeunit')[$row->size_unit];
            }
            $merchant = MasterMerchant::find($id);
            $datefrom = formatDateThatNoTime($datawhere);
            // dd($Orders);
            return view('pages.master_data.item_merchant_send.list',compact('Orders','merchant','datefrom'));
    }

}
