<?php

namespace App\Http\Controllers\Delivery;

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

class DeliveryController extends Controller
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
        return view('pages.delivery.index');
    }

    public function list()
    {
        return view('pages.delivery.list');
    }

    public function orderDetail($id)
    {
        $data['Order'] = $this->getOrder($id);

        $data['OrderItem'] = OrderItem::select(
            'order_item.order_item_id',
            'order_item.order_id',
            'order_item.price',
            'order_item.total_price',
            'order_item.qty',
            'order_item.remark',
            'product.name as product_name',
            'product_type.name as product_type_name',
            'product_size.name as product_size_name',
            'product_size.weight as product_weight',
            DB::raw('sum( case when delivery.status = "05" && delivery.delivery_type = "00" then delivery_item.qty else 0 end ) AS sum_order_delivery_item ')
        )
            ->leftjoin('master_product_main as product', 'product.master_product_main_id', '=', 'order_item.master_product_main_id')
            ->leftjoin('master_product_type as product_type', 'product_type.master_product_type_id', '=', 'order_item.master_product_type_id')
            ->leftjoin('master_product_size as product_size', 'product_size.master_product_size_id', '=', 'order_item.master_product_size_id')
            ->leftjoin('order_delivery_item as delivery_item', 'delivery_item.order_item_id', '=', 'order_item.order_item_id')
            ->leftjoin('order_delivery as delivery', 'delivery.order_delivery_id', '=', 'delivery_item.order_delivery_id')
            ->where('order_item.order_id', $id)
            ->groupBy(
                'order_item.order_item_id',
                'order_item.order_id',
                'order_item.price',
                'order_item.total_price',
                'order_item.qty',
                'order_item.remark',
                'product_name',
                'product_type_name',
                'product_size_name',
                'product_weight',
                'delivery_item.order_item_id'
            )
            ->get();

        return view('pages.delivery.order_detail', ['data' => $data]);
    }

    public function deliveryList($id)
    {
        $data['Order'] = $this->getOrder($id);
        $data['MasterTruckType'] = MasterTruckType::get();
        return view('pages.delivery.delivery_list', ['data' => $data]);
    }

    public function deliverySend(Request $request, $order_id, $delivery_id)
    {

        $data['Order'] = $this->getOrder($order_id);
        $data['MasterRound'] = MasterRound::get();
        $data['MasterTruckType'] = $this->getMasterTruckType();
        $OrderItem = OrderItem::select(
            'order_item.order_item_id',
            'order_item.order_id',
            'order_item.price',
            'order_item.total_price',
            'order_item.qty',
            'order_item.remark',
            'order_item.addition',
            'order_item.total_size',
            'order_item.size_unit',
            'product.name as product_name',
            'product_type.name as product_type_name',
            DB::raw("case when product.formula = '01' then product_size.name*0.35 else product_size.name end AS product_size_name"),
            'product_size.size_unit as product_size_unit',
            'product_size.weight as product_size_weight',
            'order_item.weight as product_weight',
            'product.formula',
            'delivery.delivery_type',
            'delivery.status',
            DB::raw(" delivery_item.qty AS sum_order_delivery_item")

        )
            ->leftjoin('master_product_main as product', 'product.master_product_main_id', '=', 'order_item.master_product_main_id')
            ->leftjoin('master_product_type as product_type', 'product_type.master_product_type_id', '=', 'order_item.master_product_type_id')
            ->leftjoin('master_product_size as product_size', 'product_size.master_product_size_id', '=', 'order_item.master_product_size_id')
            ->leftjoin('order_delivery_item as delivery_item', 'delivery_item.order_item_id', '=', 'order_item.order_item_id')
            ->leftjoin('order_delivery as delivery', 'delivery.order_delivery_id', '=', 'delivery_item.order_delivery_id')
            ->where('order_item.order_id', $order_id)
            ->get();
        $data['OrderItem'] = [];

        foreach ($OrderItem as $row) {
            if (!isset($qty)) $qty = $row->qty;
            if (!isset($sum_order_delivery_item[$row->order_item_id])) $sum_order_delivery_item[$row->order_item_id] = 0;

            $weight_data = Tools::convertdWeight($row->product_weight);
            $row->total_size_text = ($row->formula == '01') ? round($row->total_size, 4) . ' ตร.ม.' : round($row->total_size, 2) . ' ' . config('sizeunit')[$row->size_unit];
            $row->product_weight_text = $weight_data['number'] . ' ' . $weight_data['unit'];
            $row->product_size_name_text = ($row->formula == '01') ? '0.35x' . sprintf('%g', $row->product_size_name) . ' ' . config('sizeunit')[$row->size_unit] : sprintf('%g', $row->product_size_name) . ' ' . config('sizeunit')[$row->size_unit];
            $row->item_price = $row->price + $row->addition;

            if ($row->delivery_type == '01') {
                //  $sum_order_delivery_item[$row->order_item_id] -= $row->sum_order_delivery_item;
            } else if ($row->delivery_type == '00') {
                $sum_order_delivery_item[$row->order_item_id] += $row->sum_order_delivery_item;
            } else if ($row->delivery_type == '02' && $row->status != '05') {
                //$sum_order_delivery_item[$row->order_item_id] -= $row->sum_order_delivery_item;
            }
            $row->sum_order_delivery_item =  $sum_order_delivery_item[$row->order_item_id];
            $data['OrderItem'][$row->order_item_id] = $row;
        }
        $data['OrderDelivery'] = OrderDelivery::find($delivery_id);
        // dd($OrderItem);
        return view('pages.delivery.delivery_send', ['data' => $data, 'order_id' => $order_id, 'delivery_id' => $delivery_id, 'ref' => $request->ref]);
    }

    public function deliveryConfirm($order_id, $delivery_id)
    {
        $data['Order'] = $this->getOrder($order_id);
        $data['MasterRound'] = MasterRound::get();
        $data['MasterTruckType'] = $this->getMasterTruckType();
        $data['OrderDelivery'] = OrderDelivery::select(
            'order_delivery.order_delivery_id',
            'order_delivery.order_id',
            'order_delivery.order_delivery_number',
            'schedule.truck_schedule_id',
            'schedule.master_round_id',
            'schedule.date_schedule',
            'truck.master_truck_id',
            'truck.license_plate',
            'truck_type.master_truck_type_id',
            'truck_type.type',
            'order_delivery.status',
            DB::raw('sum( item.qty ) AS sum_order_delivery_item ')
        )
            ->leftjoin('truck_schedule as schedule', 'schedule.order_delivery_id', '=', 'order_delivery.order_delivery_id')
            ->leftjoin('order_delivery_item as item', 'item.order_delivery_id', '=', 'order_delivery.order_delivery_id')
            ->leftjoin('master_truck as truck', 'truck.master_truck_id', '=', 'schedule.master_truck_id')
            ->leftjoin('master_truck_type as truck_type', 'truck_type.master_truck_type_id', '=', 'truck.master_truck_type_id')
            ->where('order_delivery.order_delivery_id', $delivery_id)
            ->groupBy(
                'order_delivery.order_delivery_id',
                'order_delivery.order_id',
                'order_delivery.order_delivery_number',
                'schedule.truck_schedule_id',
                'schedule.master_round_id',
                'schedule.date_schedule',
                'truck.master_truck_id',
                'truck.license_plate',
                'truck_type.master_truck_type_id',
                'truck_type.type',
                'order_delivery.status'
            )
            ->first();

        $OrderDeliveryItem = OrderDeliveryItem::select(
            'order_delivery_item.qty as delivery_item_qty',
            'order_item.order_item_id',
            'order_item.order_id',
            'order_item.price',
            'order_item.total_price',
            'order_item.qty',
            'order_item.remark',
            'order_item.addition',
            'order_item.total_size',
            'order_item.size_unit',
            'product.name as product_name',
            'product_type.name as product_type_name',
            DB::raw("case when product.formula = '01' then product_size.name*0.35 else product_size.name end AS product_size_name"),
            'product_size.size_unit as product_size_unit',
            'product.formula',
            'order_delivery_item.weight AS product_weight'
            // DB::raw("product_size.weight * order_delivery_item.qty AS product_weight")
        )
            ->join('order_item', 'order_item.order_item_id', '=', 'order_delivery_item.order_item_id')
            ->leftjoin('master_product_main as product', 'product.master_product_main_id', '=', 'order_item.master_product_main_id')
            ->leftjoin('master_product_type as product_type', 'product_type.master_product_type_id', '=', 'order_item.master_product_type_id')
            ->leftjoin('master_product_size as product_size', 'product_size.master_product_size_id', '=', 'order_item.master_product_size_id')
            ->where('order_delivery_item.order_delivery_id', $delivery_id)
            ->get();
        $data['OrderDeliveryItem'] = [];
        foreach ($OrderDeliveryItem as $row) {
            $weight_data = Tools::convertdWeight($row->product_weight);
            $row->total_size_text = ($row->formula == '01') ? round($row->total_size, 4) . ' ตร.ม.' : round($row->total_size, 2) . ' ' . config('sizeunit')[$row->size_unit];
            $row->product_weight_text = $weight_data['number'] . ' ' . $weight_data['unit'];
            $row->product_size_name_text = ($row->formula == '01') ? '0.35x' . sprintf('%g', $row->product_size_name) . ' ' . config('sizeunit')[$row->size_unit] : sprintf('%g', $row->product_size_name) . ' ' . config('sizeunit')[$row->size_unit];
            $row->item_price = $row->price + $row->addition;
            $data['OrderDeliveryItem'][] = $row;
        }

        return view('pages.delivery.delivery_confirm', ['data' => $data, 'order_id' => $order_id, 'delivery_id' => $delivery_id]);
    }

    public function deliverySummary($order_id, $delivery_id)
    {
        $data['Order'] = $this->getOrder($order_id);
        $data['OrderDelivery'] = OrderDelivery::select(
            'order_delivery.order_delivery_id',
            'order_delivery.product_return_claim_id',
            'order_delivery.order_id',
            'order_delivery.order_delivery_number',
            'schedule.truck_schedule_id',
            'schedule.master_round_id',
            'round.name as round_name',
            'schedule.date_schedule',
            'truck.master_truck_id',
            'truck.license_plate',
            'truck_type.master_truck_type_id',
            'truck_type.type',
            'order_delivery.status',
            DB::raw('sum( item.qty ) AS sum_order_delivery_item '),
            DB::raw("SUM(product_size.weight * item.qty) AS product_weight_all")
        )
            ->leftjoin('truck_schedule as schedule', 'schedule.order_delivery_id', '=', 'order_delivery.order_delivery_id')
            ->leftjoin('order_delivery_item as item', 'item.order_delivery_id', '=', 'order_delivery.order_delivery_id')
            ->leftjoin('order_item', 'order_item.order_item_id', '=', 'item.order_item_id')
            ->leftjoin('master_product_size as product_size', 'product_size.master_product_size_id', '=', 'order_item.master_product_size_id')
            ->leftjoin('master_truck as truck', 'truck.master_truck_id', '=', 'schedule.master_truck_id')
            ->leftjoin('master_truck_type as truck_type', 'truck_type.master_truck_type_id', '=', 'truck.master_truck_type_id')
            ->leftjoin('master_round as round', 'round.master_round_id', '=', 'schedule.master_round_id')
            ->where('order_delivery.order_delivery_id', $delivery_id)
            ->groupBy(
                'order_delivery.order_delivery_id',
                'order_delivery.product_return_claim_id',
                'order_delivery.order_id',
                'order_delivery.order_delivery_number',
                'schedule.truck_schedule_id',
                'schedule.master_round_id',
                'schedule.date_schedule',
                'round_name',
                'truck.master_truck_id',
                'truck.license_plate',
                'truck_type.master_truck_type_id',
                'truck_type.type',
                'order_delivery.status'
            )
            ->first();

        $OrderDeliveryItem = OrderDeliveryItem::select(
            'order_delivery_item.qty as delivery_item_qty',
            'order_item.order_item_id',
            'order_item.order_id',
            'order_item.price',
            'order_item.total_price',
            'order_item.qty',
            'order_item.remark',
            'order_item.addition',
            'order_item.total_size',
            'order_item.size_unit',
            'product.name as product_name',
            'product_type.name as product_type_name',
            'product_size.name as product_size_name',
            'product_size.size_unit as product_size_unit',
            'product.unit_count as unit_count',
            'product.formula',
            DB::raw("product_size.weight * order_delivery_item.qty AS product_weight")

        )
            ->join('order_item', 'order_item.order_item_id', '=', 'order_delivery_item.order_item_id')
            ->leftjoin('master_product_main as product', 'product.master_product_main_id', '=', 'order_item.master_product_main_id')
            ->leftjoin('master_product_type as product_type', 'product_type.master_product_type_id', '=', 'order_item.master_product_type_id')
            ->leftjoin('master_product_size as product_size', 'product_size.master_product_size_id', '=', 'order_item.master_product_size_id')
            ->where('order_delivery_item.order_delivery_id', $delivery_id)
            ->get();
        $data['OrderDeliveryItem'] = [];
        foreach ($OrderDeliveryItem as $row) {
            $weight_data = Tools::convertdWeight($row->product_weight);
            $row->total_size_text = ($row->formula == '01') ? round($row->total_size, 4) . ' ตร.ม.' : round($row->total_size, 2) . ' ' . config('sizeunit')[$row->size_unit];
            $row->product_weight_text = $weight_data['number'] . ' ' . $weight_data['unit'];
            $row->product_size_name_text = ($row->formula == '01') ? '0.35x' . sprintf('%g', $row->product_size_name) . ' ' . config('sizeunit')[$row->size_unit] : sprintf('%g', $row->product_size_name) . ' ' . config('sizeunit')[$row->size_unit];
            $row->item_price = $row->price + $row->addition;
            $data['OrderDeliveryItem'][] = $row;
        }

        return view('pages.delivery.delivery_summary', ['data' => $data, 'order_id' => $order_id, 'delivery_id' => $delivery_id, 'countunit' => config('countunit')]);
    }

    // ----------------------------- api -----------------------------
    // ดึงข้อมูลรายการสั่งซื้อ
    public function getList(Request $request)
    {
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
            'orders.department_name',
            'orders.weight',
            'orders.qty_all',
            'orders.created_date',
            'merchant.name_merchant',
            DB::raw('sum( case when delivery.status = "05" && delivery.delivery_type = "00" then delivery_item.qty else 0 end ) AS sum_order_delivery_item ')
        )
            ->leftjoin('master_merchant as merchant', 'merchant.master_merchant_id', '=', 'orders.merchant_id')
            ->leftjoin('order_delivery as delivery', 'delivery.order_id', '=', 'orders.order_id')
            ->leftjoin('order_delivery_item as delivery_item', 'delivery_item.order_delivery_id', '=', 'delivery.order_delivery_id')
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
                'orders.qty_all',
                'orders.created_date',
                'merchant.name_merchant'
            );

        if ($request->has('search') && !empty($request->search)) {
            $Order->where('orders.order_number', 'LIKE', $request->search . '%');
            $Order->orWhere('merchant.name_merchant', 'LIKE', '%' . $request->search . '%');
            $Order->orWhere('orders.department_name', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->has('date_start') && !empty($request->date_start) && $request->has('date_end') && !empty($request->date_end)) {
            $Order->whereDate('orders.created_date', '>=', Carbon::parse($request->date_start)->format('Y-m-d'));
            $Order->whereDate('orders.created_date', '<=', Carbon::parse($request->date_end)->format('Y-m-d'));
        }

        if ($request->has('status_send') && !empty($request->status_send)) {
            $Order->where('orders.staus_send', $request->status_send);
        }

        if (\Auth::user()->hasRole('admin')) {
            $Order->where('orders.order_type', '00');
        }
        $Order->orderby('orders.updated_date', 'desc');
        $Order->orderby('orders.created_date', 'desc');

        return Datatables::of($Order)->editColumn('created_date', '{{ formatDateThat($created_date)}}')->make(true);
    } 
    // ดึงข้อมูลรถส่งสินค้า
    public function getMasterTruck($id)
    {
        $MasterTruckType = MasterTruck::where('master_truck_type_id', $id)->get();

        return response()->json(['status' => true, 'data' => $MasterTruckType]);
    }
    // ดึงข้อมูลรายการจัดส่ง
    public function getOrderDelivery($id, Request $request)
    {
        $OrderDelivery = OrderDelivery::select(
            'order_delivery.order_delivery_id',
            'order_delivery.order_id',
            'order_delivery.order_delivery_number',
            'schedule.truck_schedule_id',
            'schedule.date_schedule',
            'truck.license_plate',
            'truck_type.master_truck_type_id',
            'truck_type.type',
            'order_delivery.status',
            'order_delivery.delivery_type',
            DB::raw('sum( item.qty ) AS sum_order_delivery_item ')
        )
            ->leftjoin('order_delivery_item as item', 'item.order_delivery_id', '=', 'order_delivery.order_delivery_id')
            ->leftjoin('truck_schedule as schedule', 'schedule.order_delivery_id', '=', 'order_delivery.order_delivery_id')
            ->leftjoin('master_truck as truck', 'truck.master_truck_id', '=', 'schedule.master_truck_id')
            ->leftjoin('master_truck_type as truck_type', 'truck_type.master_truck_type_id', '=', 'truck.master_truck_type_id')
            ->where('order_delivery.order_id', $id)
            ->groupBy(
                'order_delivery.order_delivery_id',
                'order_delivery.order_id',
                'order_delivery.order_delivery_number',
                'schedule.truck_schedule_id',
                'schedule.date_schedule',
                'truck.license_plate',
                'truck_type.master_truck_type_id',
                'truck_type.type',
                'order_delivery.status',
                'order_delivery.delivery_type'
            );

        if ($request->has('search') && !empty($request->search)) {
            $OrderDelivery->where('order_delivery.order_delivery_number', 'LIKE', $request->search . '%');
            $OrderDelivery->orWhere('truck.license_plate', 'LIKE', $request->search . '%');
        }

        if ($request->has('truck_type') && !empty($request->truck_type)) {
            $OrderDelivery->where('truck_type.master_truck_type_id', $request->truck_type);
        }

        if ($request->has('status_send') && !empty($request->status_send)) {
            $OrderDelivery->where('order_delivery.status', $request->status_send);
        }

        if ($request->has('date_start') && !empty($request->date_start) && $request->has('date_end') && !empty($request->date_end)) {
            $OrderDelivery->whereRaw('((schedule.date_schedule >= ? and schedule.date_schedule <= ?) or schedule.date_schedule IS NULL)', [Carbon::parse($request->date_start)->format('Y-m-d'), Carbon::parse($request->date_end)->format('Y-m-d')]);
        }

        $OrderDelivery = $OrderDelivery->get()->each(function ($item, $key) {
            $item['date_schedule'] = $item['date_schedule'] ? formatDateThatNoTime($item['date_schedule']) : '';
            $item['delivery_type_name'] =  $item['delivery_type'] == '00' ? '' : config('status.delivery_type')[$item['delivery_type']];
        });
        // dd($OrderDelivery);
        return response()->json(['status' => true, 'data' => $OrderDelivery]);
    }

    // สร้างใบจัดส่งสินค้า
    public function createOrderDelivery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'send_ids'     => 'required',
            'qty_send'     => 'required',
            'date_send'     => 'required',
            'round'     => 'required',
        ], [
            'send_ids.required' => 'จำเป็นต้องเลือกส่งสินค้าอย่างน้อย 1 รายการ',
            'qty_send.required' => 'จำเป็นต้องใส่จำนวนสินค้าที่ต้องการส่ง',
            'date_send.required' => 'จำเป็นต้องเลือกวันที่จัดส่ง',
            'round.required' => 'จำเป็นต้องเลือกรอบจัดส่ง ',
        ]);

        // check required parameter
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }
        // check count product
        if (count($request->send_ids) > 10) {
            return response()->json(['status' => false, 'message' => 'เลือกส่งสินค้าได้ไม่เกิน 10 รายการ']);
        }

        // check truck
        // หารถที่มารับเอง
        $turk = MasterTruckType::select('master_truck_type.type', 'master_truck_type.master_truck_type_id', 'master_truck.master_truck_id')
            ->leftjoin('master_truck as master_truck', 'master_truck.master_truck_type_id', '=', 'master_truck_type.master_truck_type_id')
            ->where('master_truck_type.type', 'like', '%มารับเอง%')
            ->first();
        // หารถที่มารับเอง
        $checktruk = OrderDelivery::select('order_delivery.status', 'order_delivery.order_delivery_id', 'schedule.master_truck_id', 'schedule.master_round_id', 'schedule.date_schedule')
            ->leftjoin('truck_schedule as schedule', 'schedule.order_delivery_id', '=', 'order_delivery.order_delivery_id')
            ->where(function ($query) use ($request, $turk) {
                $query = $query->where('schedule.master_truck_id', $request->license_plate)
                    ->whereNotNull('schedule.master_truck_id')
                    ->where('schedule.master_truck_id', '!=', $turk->master_truck_id);
            })
            ->where('schedule.master_round_id', $request->round)
            ->where('schedule.date_schedule', Carbon::parse($request->date_send)->format('Y-m-d H:i:s'))
            ->whereIN('order_delivery.status', ['01', '02'])
            ->get();
        if (count($checktruk) > 0) {
            return response()->json(['status' => false, 'message' => 'รถคันนี้อยู่ระหว่างจัดส่งสินค้า']);
        }
        // check truck


        // insert truck_schedule
        TruckSchedule::insert([
            'order_delivery_id' => $request->delivery_id,
            'master_round_id' => $request->round,
            'master_truck_id' => $request->license_plate ?? null,
            'date_schedule' => Carbon::parse($request->date_send)->format('Y-m-d H:i:s'),
            'created_by' => Auth::id(),
            'created_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
        ]);

        // clear old OrderDeliveryItem
        OrderDeliveryItem::where('order_delivery_id',  $request->delivery_id)->delete();
        // insert OrderDeliveryItem
        $order_delivery_item = [];
        $qty_all = 0;
        $weight = 0;
        foreach ($request->send_ids as $key => $value) {
            $item = OrderItem::where('order_item_id', $value)->first();

            $product_data = MasterProductMain::select('formula')->where('master_product_main_id', $item['master_product_main_id'])->first();
            $size_data = MasterProductSize::select('weight')->where('master_product_main_id', $item['master_product_main_id'])
                ->where('master_product_type_id', $item['master_product_type_id'])
                ->where('master_product_size_id', $item['master_product_size_id'])
                ->first();
            $qty = $request->qty_send[$value];
            $qty_all += $qty;
            $weight += ($size_data->weight * $qty);
            $order_delivery_item[] = [
                'order_delivery_id' => $request->delivery_id,
                'order_item_id' => $value,
                'qty' => $qty,
                'weight' => ($size_data->weight * $qty)
            ];
        }
        OrderDeliveryItem::insert($order_delivery_item);

        // update OrderDelivery

        OrderDelivery::where('order_delivery_id', $request->delivery_id)->update([
            'qty_all' => $qty_all,
            'weight' => $weight,
            'remark' => '',
            'type' => '00',
            'status' => '01',
            'updated_by' => Auth::id(),
            'updated_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
        ]);
        // check ส่งสินค้าหมดหมดหรือยัง ??
        $count_delivery_send = Order::select('orders.qty_all', DB::raw('sum(delivery_item.qty) AS count_delivery_send '))
            ->leftjoin('order_delivery as delivery', 'delivery.order_id', '=', 'orders.order_id')
            ->leftjoin('order_delivery_item as delivery_item', 'delivery_item.order_delivery_id', '=', 'delivery.order_delivery_id')
            ->where('orders.order_id', $request->order_id)
            ->whereNull('delivery.product_return_claim_id')
            ->groupBy(
                'orders.order_id',
                'orders.qty_all'
            )
            ->first();
        $count_delivery_send =  $count_delivery_send->qty_all - $count_delivery_send->count_delivery_send;
        if ($count_delivery_send !== 0) {
            // สินค้ายังส่งไม่หมด สร้าง รายการอัตโนมัติ

            $this->addOrderDelivery(['order_id' => $request->order_id], []);
        }

        // update status Order ==> 01 ดำเนินการจัดส่ง
        Order::where('orders.order_id', $request->order_id)->update(['staus_send' => '01']);

        return response()->json(['status' => true, 'message' => 'สร้างรายการสำเร็จ']);
    }
    //สร้างใบจัดส่งสินค้าใหม่
    public static function addOrderDelivery($data, $data_item)
    {
        //หาเลขรายการล่าสุด
        $last_delivery = OrderDelivery::select('order_delivery_number')->where('order_id', $data['order_id'])->orderBy('order_delivery_id', 'desc')->first();
        $order_delivery_number = (!empty($last_delivery)) ? $last_delivery->order_delivery_number + 1 : 1;
        // insert OrderDelivery
        $order_delivery_id =    OrderDelivery::insertGetId([
            'order_id' => $data['order_id'],
            'order_delivery_number' => str_pad($order_delivery_number, 4, '0', STR_PAD_LEFT),
            'product_return_claim_id' => (isset($data['product_return_claim_id'])) ? $data['product_return_claim_id'] : null,
            'remark' => (isset($data['remark'])) ? $data['remark'] : null,
            'type' => '00',
            'status' => (isset($data['status'])) ? $data['status'] : '00',
            'delivery_type' => (isset($data['delivery_type'])) ? $data['delivery_type'] : '00',
            'qty_all' => (isset($data['qty_all'])) ? $data['qty_all'] : 0,
            'weight' => (isset($data['weight'])) ? $data['weight'] : 0,
            'created_date' => Auth::id(),
            'created_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
            'updated_by' => Auth::id(),
            'created_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),

        ]);
        // insert OrderDeliveryItem
        if (!empty($data_item)) {
            $order_delivery_item = [];
            foreach ($data_item as $item) {
                $size_data = MasterProductSize::select('weight')->where('master_product_main_id', $item['master_product_main_id'])
                    ->where('master_product_type_id', $item['master_product_type_id'])
                    ->where('master_product_size_id', $item['master_product_size_id'])
                    ->first();

                $order_delivery_item[] = [
                    'order_delivery_id' => $order_delivery_id,
                    'order_item_id' => $item['order_item_id'],
                    'qty' => $item['qty'],
                    'weight' => ($size_data->weight * $item['qty']),
                ];
            }
            OrderDeliveryItem::insert($order_delivery_item);
        }
        return $order_delivery_id;
    }
    // ยืนยันการจัดส่งสินค้า
    public function confirmOrderDelivery(Request $request)
    {
        // check case update address
        if ($request->edit_address) {
            MasterMerchant::where('master_merchant_id', $request->master_merchant_id)->update([
                'address'   =>  $request->address,
                'updated_by' => Auth::id(),
                'updated_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
            ]);
        }

        // update OrderDelivery
        OrderDelivery::where('order_delivery_id', $request->order_delivery_id)->update([
            'status' => '02',
            'updated_by' => Auth::id(),
            'updated_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
        ]);

        // update truck_schedule
        TruckSchedule::where('truck_schedule_id', $request->truck_schedule_id)->update([
            'master_truck_id' => $request->license_plate,
            'updated_by' => Auth::id(),
            'updated_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
        ]);

        // update status Order ==> 01 ดำเนินการจัดส่ง
        Order::where('orders.order_id', $request->order_id)->update(['staus_send' => '01']);

        return response()->json(['status' => true, 'message' => 'ยืนยันการจัดส่งสินค้าสำเร็จ']);
    }

    // ทำการเลื่อนการจัดส่ง
    public function changeOrderDelivery(Request $request)
    {
        // update OrderDelivery
        OrderDelivery::where('order_delivery_id', $request->order_delivery_id)->update([
            'remark' => $request->remark,
            'type' => '01',
            'status' => '03',
            'updated_by' => Auth::id(),
            'updated_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
        ]);

        // update truck_schedule
        TruckSchedule::where('truck_schedule_id', $request->truck_schedule_id)->update([
            'master_truck_id' => $request->license_plate,
            'updated_by' => Auth::id(),
            'updated_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
            'date_schedule' => Carbon::parse($request->date_send)->format('Y-m-d H:i:s')

        ]);

        // update status Order ==> 01 ดำเนินการจัดส่ง
        // Order::where('orders.order_id', $request->order_id)->update(['staus_send' => '01']);

        return response()->json(['status' => true, 'message' => 'ยืนยันการเลื่อนจัดส่งสินค้าสำเร็จ']);
    }


    // ยืนยันการจัดส่งสินค้าสำเร็จ
    public function sendOrderDeliverySuccess($order_id, $delivery_id)
    {
        // update OrderDelivery
        OrderDelivery::where('order_delivery_id', $delivery_id)->update([
            'status' => '05',
            'updated_by' => Auth::id(),
            'updated_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
        ]);

        $check_delivery_order = OrderDelivery::where('order_id', $order_id)->Where('status', '!=', '05')->count();
        if ($check_delivery_order == 0) {
            Order::where('order_id', $order_id)->update(['staus_send' => '03']);
        }

        $data['Order'] = $this->getOrder($order_id);
        return response()->json(['status' => true, 'message' => 'ยืนยันการจัดส่งสินค้าสำเร็จ', 'sum_order_delivery_item' => $data['Order']->sum_order_delivery_item, 'qty_all' => $data['Order']->qty_all]);
    }

    // ----------------------------- private function -----------------------------
    // ดึงประเภทรถส่งสินค้า
    private function getMasterTruckType()
    {
        return MasterTruckType::select('master_truck_type.master_truck_type_id', 'master_truck_type.type', DB::raw('count( master_truck.master_truck_type_id ) AS count '))
            ->leftJoin('master_truck', function ($leftJoin) {
                $leftJoin->on('master_truck.master_truck_type_id', '=', 'master_truck_type.master_truck_type_id')
                    ->whereNull('master_truck.deleted_date');
            })
            ->groupBy('master_truck_type.master_truck_type_id', 'master_truck_type.type')
            ->get();
    }

    // ดึงข้อมูล รายการสั่งซื้อ
    private function getOrder($id)
    {
        return Order::select(
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
            'orders.qty_all',
            'orders.created_date',
            'orders.created_date',
            'orders.department_name',
            'merchant.master_merchant_id',
            'merchant.name_merchant',
            'merchant.name_department',
            'merchant.address',
            'merchant.phone_number',
            'merchant.tax_number',
            'admin.name as admin_name',
            'orders.noteorder as noteorder',
            'orders.status_departmen',
            'orders.phone_department',
            'orders.address_department',
            DB::raw('sum( case when delivery.status = "05" && delivery.delivery_type = "00" then delivery_item.qty else 0 end ) AS sum_order_delivery_item '),
            DB::raw('sum( case when delivery.status != "05" && delivery.delivery_type = "02" then delivery_item.qty else 0 end ) AS sum_order_delivery_item_claim '),
            DB::raw('sum( case when delivery.delivery_type = "01" then delivery_item.qty else 0 end ) AS sum_order_delivery_item_return ')
        )
            ->leftjoin('master_merchant as merchant', 'merchant.master_merchant_id', '=', 'orders.merchant_id')
            ->leftjoin('order_delivery as delivery', 'delivery.order_id', '=', 'orders.order_id')
            ->leftjoin('order_delivery_item as delivery_item', 'delivery_item.order_delivery_id', '=', 'delivery.order_delivery_id')
            ->leftjoin('users as admin', 'admin.id', '=', 'orders.created_by')
            ->where('orders.order_id', $id)
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
                'orders.qty_all',
                'orders.created_date',
                'merchant.master_merchant_id',
                'merchant.name_merchant',
                'merchant.name_department',
                'merchant.address',
                'merchant.phone_number',
                'admin_name',
                'merchant.tax_number'
            )
            ->first();
    }
}
