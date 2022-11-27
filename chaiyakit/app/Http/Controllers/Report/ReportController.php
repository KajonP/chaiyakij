<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Validator;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MasterRound;
use App\Models\MasterTruckType;
use App\Models\MasterTruck;
use App\Models\OrderDelivery;
use App\Models\OrderDeliveryItem;
use App\Models\TruckSchedule;
use App\Models\MasterMerchant;
use App\Models\ProductReturnClaim;
use App\Models\ProductReturnClaimItem;
use App\Models\AdminUsers;
use DB;

class ReportController extends Controller
{
    public function reportReturn()
    {
        return view('pages.report.return');
    }

    public function returnList(Request $request){
        $Order = Order::select(
            'orders.order_id',
            'orders.order_number',
            'orders.staus_send',
            'orders.status_vat',
            'orders.order_type',
            'orders.price_all',
            'orders.vat',
            'orders.vat_no',
            'orders.grand_total',
            'orders.weight',
            'delivery.qty_all',
            'orders.created_date',
            'merchant.name_merchant',
            'item.total_size',
            'delivery_item.order_delivery_id',
            'product_main.formula',
            'item.size_unit',
            'delivery.order_delivery_number',
            DB::raw(' product_main.name AS main_name '),
            DB::raw(' product_size.name AS size_name '),
            DB::raw(' product_type.name AS type_name ')
        )
            ->leftjoin('order_item as item', 'item.order_id', '=', 'orders.order_id')
            ->join('order_delivery_item as delivery_item', 'delivery_item.order_item_id', '=', 'item.order_item_id')
            ->leftjoin('master_merchant as merchant', 'merchant.master_merchant_id', '=', 'orders.merchant_id')
            ->leftjoin('order_delivery as delivery', 'delivery_item.order_delivery_id', '=', 'delivery.order_delivery_id')
            ->leftjoin('master_product_main as product_main', 'product_main.master_product_main_id', '=', 'item.master_product_main_id')
            ->leftjoin('master_product_type as product_type', 'product_type.master_product_type_id', '=', 'item.master_product_type_id')
            ->leftjoin('master_product_size as product_size', 'product_size.master_product_size_id', '=', 'item.master_product_size_id')
            ->groupBy(
                'orders.order_id',
                'orders.order_number',
                'orders.staus_send',
                'orders.status_vat',
                'orders.order_type',
                'orders.price_all',
                'orders.vat',
                'orders.vat_no',
                'orders.grand_total',
                'orders.weight',
                'delivery.qty_all',
                'orders.created_date',
                'merchant.name_merchant',
                'delivery_item.order_delivery_id',
                'main_name',
                'size_name',
                'type_name'
            );
        $Order->where('delivery.delivery_type', '01');
        if ($request->has('search') && !empty($request->search)) {
            $Order->where('orders.order_number', 'LIKE', $request->search . '%');
            $Order->orWhere('merchant.name_merchant', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->has('date_start') && !empty($request->date_start) && $request->has('date_end') && !empty($request->date_end)) {
            $Order->whereDate('orders.created_date', '>=', Carbon::parse($request->date_start)->format('Y-m-d'));
            $Order->whereDate('orders.created_date', '<=', Carbon::parse($request->date_end)->format('Y-m-d'));
        }
        $res_order = $Order->get();
        $data['order'] = [];
        foreach ($res_order as $key => $row) {
            $row->product_size_name_text = $row->product_size_name_text = ($row->formula == '01') ? '0.35x' . sprintf('%g', $row->size_name) . ' ' . config('sizeunit')[$row->size_unit] : sprintf('%g', $row->size_name) . ' ' . config('sizeunit')[$row->size_unit];
            $data['order'][$key] = $row;
        }
        
        $data = Datatables::of($data['order'])->editColumn('created_date', '{{ formatDateThat($created_date)}}')->make(true);
        return $data;
    }

    public function reportClaim()
    {
        return view('pages.report.claim');
    }

    public function claimList(Request $request){
        $Order = Order::select(
            'orders.order_id',
            'orders.order_number',
            'orders.staus_send',
            'orders.status_vat',
            'orders.order_type',
            'orders.price_all',
            'orders.vat',
            'orders.vat_no',
            'orders.grand_total',
            'orders.weight',
            'delivery.qty_all',
            'orders.created_date',
            'merchant.name_merchant',
            'delivery_item.order_delivery_id',
            'item.total_size',
            'product_main.formula',
            'delivery.order_delivery_number',
            'item.size_unit',
            DB::raw(' product_main.name AS main_name '),
            DB::raw(' product_size.name AS size_name '),
            DB::raw(' product_type.name AS type_name ')
        )
            ->leftjoin('order_item as item', 'item.order_id', '=', 'orders.order_id')
            ->join('order_delivery_item as delivery_item', 'delivery_item.order_item_id', '=', 'item.order_item_id')
            ->leftjoin('master_merchant as merchant', 'merchant.master_merchant_id', '=', 'orders.merchant_id')
            ->leftjoin('order_delivery as delivery', 'delivery_item.order_delivery_id', '=', 'delivery.order_delivery_id')
            ->leftjoin('master_product_main as product_main', 'product_main.master_product_main_id', '=', 'item.master_product_main_id')
            ->leftjoin('master_product_type as product_type', 'product_type.master_product_type_id', '=', 'item.master_product_type_id')
            ->leftjoin('master_product_size as product_size', 'product_size.master_product_size_id', '=', 'item.master_product_size_id');
        $Order->where('delivery.delivery_type', '02');
        if ($request->has('search') && !empty($request->search)) {
            $Order->where('orders.order_number', 'LIKE', $request->search . '%');
            $Order->orWhere('merchant.name_merchant', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->has('date_start') && !empty($request->date_start) && $request->has('date_end') && !empty($request->date_end)) {
            $Order->whereDate('orders.created_date', '>=', Carbon::parse($request->date_start)->format('Y-m-d'));
            $Order->whereDate('orders.created_date', '<=', Carbon::parse($request->date_end)->format('Y-m-d'));
        }
        $res_order = $Order->get();
        $data['order'] = [];
        foreach ($res_order as $key => $row) {
            $row->product_size_name_text = $row->product_size_name_text = ($row->formula == '01') ? '0.35x' . sprintf('%g', $row->size_name) . ' ' . config('sizeunit')[$row->size_unit] : sprintf('%g', $row->size_name) . ' ' . config('sizeunit')[$row->size_unit];
            $data['order'][$key] = $row;
        }
        
        $data = Datatables::of($data['order'])->editColumn('created_date', '{{ formatDateThat($created_date)}}')->make(true);
        return $data;
    }

    public function reportTotal()
    {
        return view('pages.report.total');
    }

    public function totalList(Request $request){
        $Order = Order::select(
            'orders.order_id',
            'orders.order_number',
            'orders.price_all',
            'orders.vat',
            'orders.grand_total',
            'orders.created_date',
            'merchant.name_merchant'
        )
            ->leftjoin('master_merchant as merchant', 'merchant.master_merchant_id', '=', 'orders.merchant_id')
            ->leftjoin('order_delivery as delivery', 'delivery.order_id', '=', 'orders.order_id')
            ->leftjoin('order_delivery_item as delivery_item', 'delivery_item.order_delivery_id', '=', 'delivery.order_delivery_id')
            ->leftjoin('order_item as item', 'item.order_id', '=', 'orders.order_id')
            ->groupBy(
                'orders.order_id',
                'orders.order_number',
                'orders.price_all',
                'orders.vat',
                'orders.grand_total',
                'orders.created_date',
                'merchant.name_merchant'
            );
        $Order->where('delivery.delivery_type', '00');
        if ($request->has('search') && !empty($request->search)) {
            $Order->where('orders.order_number', 'LIKE', $request->search . '%');
            $Order->orWhere('merchant.name_merchant', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->has('date_start') && !empty($request->date_start) && $request->has('date_end') && !empty($request->date_end)) {
            $Order->whereDate('orders.created_date', '>=', Carbon::parse($request->date_start)->format('Y-m-d'));
            $Order->whereDate('orders.created_date', '<=', Carbon::parse($request->date_end)->format('Y-m-d'));
        }

        return Datatables::of($Order)->editColumn('created_date', '{{ formatDateThat($created_date)}}')->make(true);
    }

    public function reportTransaction()
    {
        return view('pages.report.transaction');
    }
	
	public function reportPerSale()
    {
		$users = AdminUsers::select('users.*')->get();
		return view('pages.report.per_sale', ['users' => $users]);
    }

	public function perSaleList(Request $request){
        $Order = Order::distinct()->select(
            
            'item.order_item_id',
            'orders.order_number',
            'orders.created_by',
            'item.total_price',
            'item.price',
            'item.total_size',
            'item.qty',
            'orders.created_date',
            'merchant.name_merchant',
            'product_main.formula',
            'item.size_unit',
            DB::raw(' product_main.name AS main_name '),
            DB::raw(' product_size.name AS size_name '),
            DB::raw(' product_type.name AS type_name ')
        )
            ->leftjoin('order_item as item', 'item.order_id', '=', 'orders.order_id')
            ->leftjoin('master_merchant as merchant', 'merchant.master_merchant_id', '=', 'orders.merchant_id')
            ->leftjoin('master_product_main as product_main', 'product_main.master_product_main_id', '=', 'item.master_product_main_id')
            ->leftjoin('master_product_type as product_type', 'product_type.master_product_type_id', '=', 'item.master_product_type_id')
            ->leftjoin('master_product_size as product_size', 'product_size.master_product_size_id', '=', 'item.master_product_size_id')
            ->orderBy('orders.order_number');
            
            
        if ($request->has('search') && !empty($request->search)) {
            $Order->where('orders.created_by', 'LIKE', $request->search . '%');
            #$Order->where('orders.order_number', 'LIKE', $request->search . '%');
            #$Order->orWhere('merchant.name_merchant', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->has('date_start') && !empty($request->date_start) && $request->has('date_end') && !empty($request->date_end)) {
            $Order->whereDate('orders.created_date', '>=', Carbon::parse($request->date_start)->format('Y-m-d'));
            $Order->whereDate('orders.created_date', '<=', Carbon::parse($request->date_end)->format('Y-m-d'));
        }

        $res_order = $Order->get();
        $data['order'] = [];
        foreach ($res_order as $key => $row) {
            $row->product_size_name_text = $row->product_size_name_text = ($row->formula == '01') ? '0.35x' . sprintf('%g', $row->size_name) . ' ' . config('sizeunit')[$row->size_unit] : sprintf('%g', $row->size_name) . ' ' . config('sizeunit')[$row->size_unit];
            $data['order'][$key] = $row;
        }
        
        $data = Datatables::of($data['order'])->editColumn('created_date', '{{ formatDateThat($created_date)}}')->make(true);
        return $data;

    }

    public function transactionList(Request $request){
        $Order = Order::distinct()->select(
            
            'item.order_item_id',
            'orders.order_number',
            'item.total_price',
            'item.price',
            'item.total_size',
            'item.qty',
            'orders.created_date',
            'merchant.name_merchant',
            'product_main.formula',
            'item.size_unit',
            DB::raw(' product_main.name AS main_name '),
            DB::raw(' product_size.name AS size_name '),
            DB::raw(' product_type.name AS type_name ')
        )
            ->leftjoin('order_item as item', 'item.order_id', '=', 'orders.order_id')
            ->leftjoin('master_merchant as merchant', 'merchant.master_merchant_id', '=', 'orders.merchant_id')
            ->leftjoin('master_product_main as product_main', 'product_main.master_product_main_id', '=', 'item.master_product_main_id')
            ->leftjoin('master_product_type as product_type', 'product_type.master_product_type_id', '=', 'item.master_product_type_id')
            ->leftjoin('master_product_size as product_size', 'product_size.master_product_size_id', '=', 'item.master_product_size_id')
            ->orderBy('orders.order_number');
            
            
        if ($request->has('search') && !empty($request->search)) {
            $Order->where('orders.order_number', 'LIKE', $request->search . '%');
            $Order->orWhere('merchant.name_merchant', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->has('date_start') && !empty($request->date_start) && $request->has('date_end') && !empty($request->date_end)) {
            $Order->whereDate('orders.created_date', '>=', Carbon::parse($request->date_start)->format('Y-m-d'));
            $Order->whereDate('orders.created_date', '<=', Carbon::parse($request->date_end)->format('Y-m-d'));
        }

        $res_order = $Order->get();
        $data['order'] = [];
        foreach ($res_order as $key => $row) {
            $row->product_size_name_text = $row->product_size_name_text = ($row->formula == '01') ? '0.35x' . sprintf('%g', $row->size_name) . ' ' . config('sizeunit')[$row->size_unit] : sprintf('%g', $row->size_name) . ' ' . config('sizeunit')[$row->size_unit];
            $data['order'][$key] = $row;
        }
        
        $data = Datatables::of($data['order'])->editColumn('created_date', '{{ formatDateThat($created_date)}}')->make(true);
        return $data;

    }
        
}