<?php

namespace App\Http\Controllers\Claim;

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
use DB;

class ClaimController extends Controller
{
    public function index()
    {
        return view('pages.claim.index');
    }

    public function create()
    {
        return view('pages.claim.create');
    }

    public function create_return()
    {
        return view('pages.claim.create_return');
    }

    public function create_claim()
    {
        return view('pages.claim.create_claim');
    }

    public function list()
    {
        return view('pages.claim.list');
    }
    // ค้นหา
    public function getData(Request $request)
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
                'orders.weight',
                'orders.qty_all',
                'orders.department_name',
                'orders.created_date',
                'merchant.name_merchant',
                DB::raw('sum( case when delivery.status = "05" && delivery.delivery_type = "00" then delivery_item.qty else 0 end ) AS sum_order_delivery_item '),
                DB::raw('sum( case when delivery.delivery_type = "01" then delivery_item.qty else 0 end ) AS count_01 '),
                // DB::raw('sum( case when delivery.delivery_type = "02" then delivery_item.qty else 0 end ) AS count_02 '),
                DB::raw("(SELECT SUM( case when order_delivery.delivery_type = '02' then item.qty else 0 end ) FROM order_delivery
                left join order_delivery_item as item on item.order_delivery_id = order_delivery.order_delivery_id
                where order_delivery.order_id = orders.order_id
                and order_delivery.product_return_claim_id IS NOT NULL ) AS count_02 "),
            )
            ->leftjoin('master_merchant as merchant', 'merchant.master_merchant_id', '=', 'orders.merchant_id')
            ->leftjoin('order_delivery as delivery', 'delivery.order_id', '=', 'orders.order_id')
            ->leftjoin('order_delivery_item as delivery_item', 'delivery_item.order_delivery_id', '=', 'delivery.order_delivery_id')
            ->where('delivery.status','05')

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
                'merchant.name_merchant',
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

        if(\Auth::user()->hasRole('admin')){
            $Order->where('orders.order_type', '00');
        }
        $Order->orderby('orders.updated_date', 'desc');
        $Order->orderby('orders.created_date', 'desc');
        // $Order->orderby('orders.created_date', 'desc');

        // dd($Order->get()[0]);

        return Datatables::of($Order->get())->editColumn('created_date', '{{ formatDateThat($created_date)}}')->make(true);
    }

    public function orderDetail($order_id)
    {
        $data['Order'] = $this->getOrder($order_id);
        $data['OrderDelivery'] =  OrderDelivery::select(
            'order_delivery.order_delivery_id',
            'order_delivery.order_id',
            'order_delivery.order_delivery_number',
            'order_delivery.product_return_claim_id',
            'order_delivery.remark',
            'order_delivery.type',
            'order_delivery.status',
            'order_delivery.created_date',
            'order_delivery.status_delivery',
            'order_delivery.delivery_type',
            'order_delivery.qty_all',
            'order_delivery.weight',
            DB::raw('SUM(item.qty) as item')
        )
            ->leftjoin('order_delivery_item as item', 'item.order_delivery_id', '=', 'order_delivery.order_delivery_id')
            ->where('order_delivery.order_id', $order_id)
            ->whereNotNull('order_delivery.product_return_claim_id')
            ->groupBy(
                'order_delivery.order_delivery_id',
                'order_delivery.order_id',
                'order_delivery.order_delivery_number',
                'order_delivery.product_return_claim_id',
                'order_delivery.remark',
                'order_delivery.type',
                'order_delivery.status',
                'order_delivery.created_date',
                'order_delivery.status_delivery',
                'order_delivery.delivery_type',
                'order_delivery.qty_all',
                'order_delivery.weight',
            )
            ->get();
        return view('pages.claim.claim_detail', ['data' => $data]);
    }

    public function orderDetailItem($order_id, $delivery_id)
    {
        $data['Order'] = $this->getOrder($order_id);
        $data['OrderDelivery'] = OrderDelivery::where('order_delivery_id', $delivery_id)->first();

        $data['OrderDeliveryItem'] = OrderDeliveryItem::select(
            'order_delivery_item.qty as delivery_item_qty',
            'order_item.order_item_id',
            'order_item.order_id',
            'order_item.price',
            'order_item.total_price',
            'order_delivery_item.qty',
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

        return view('pages.claim.claim_detail_item', ['data' => $data, 'delivery_id' => $delivery_id]);
    }

    public function deliveryList($id)
    {
        $data['Order'] = $this->getOrder($id);
        $data['MasterTruckType'] = MasterTruckType::get();
        return view('pages.claim.claim_list', ['data' => $data]);
    }

    // ----------------------------- private function -----------------------------

    // ดึงข้อมูลรถส่งสินค้า
    public function getMasterTruck($id)
    {
        $MasterTruckType = MasterTruck::where('master_truck_type_id', $id)->get();

        return response()->json(['status' => true, 'data' => $MasterTruckType]);
    }

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
            'orders.department_name',
            'merchant.master_merchant_id',
            'merchant.name_merchant',
            'merchant.name_department',
            'merchant.address',
            'merchant.phone_number',
            'merchant.tax_number',
            'admin.name as admin_name',
            'orders.status_departmen',
            'orders.phone_department',
            'orders.address_department',
            DB::raw('sum( case when delivery.status = "05" && delivery.delivery_type = "00" then delivery_item.qty else 0 end ) AS sum_order_delivery_item '),
            DB::raw('sum( case when delivery.status != "05" && delivery.delivery_type = "02" then delivery_item.qty else 0 end ) AS sum_order_delivery_item_claim '),
            DB::raw('sum( case when delivery.status = "05" && delivery.delivery_type = "01" then delivery_item.qty else 0 end ) AS sum_order_delivery_item_return ')
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
                'merchant.tax_number',
                'admin_name'
            )
            ->first();
    }

    public function getOrderDelivery($id, Request $request)
    {
        $OrderDelivery = OrderDelivery::select(
            'order_delivery.order_delivery_id',
            'order_delivery.order_delivery_number',
            'order_delivery.order_id',
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
            ->whereIn('order_delivery.status', ["05"])
            ->where('order_delivery.delivery_type',"!=",'01')
            ->groupBy(
                'order_delivery.order_delivery_id',
                'order_delivery.order_delivery_number',
                'order_delivery.order_id',
                'schedule.truck_schedule_id',
                'schedule.date_schedule',
                'truck.license_plate',
                'truck_type.master_truck_type_id',
                'truck_type.type',
                'order_delivery.status',
                'order_delivery.delivery_type',
            );
            // ->get();

            $OrderDelivery = $OrderDelivery->get()->each(function ($item, $key) {
                $item['date_schedule'] = $item['date_schedule'] ? formatDateThatNoTime($item['date_schedule']) : '';
                $item['delivery_type_name'] =  $item['delivery_type'] == '00' ? '' : config('status.delivery_type')[$item['delivery_type']];
            });

            return response()->json(['status' => true, 'data' => $OrderDelivery]);
    }

    public function Claim_Send($order_id, $delivery_id)
    {
        // dd($delivery_id,$order_id);
        $data['Order'] = $this->getOrder($order_id);
        $data['MasterRound'] = MasterRound::get();
        $data['MasterTruckType'] = $this->getMasterTruckType();
        $data['OrderItem'] = OrderItem::select(
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
            'product_size.weight as product_weight',
            DB::raw('sum(delivery_item.qty) AS sum_order_delivery_item')
            )
            ->leftjoin('master_product_main as product', 'product.master_product_main_id', '=', 'order_item.master_product_main_id')
            ->leftjoin('master_product_type as product_type', 'product_type.master_product_type_id', '=', 'order_item.master_product_type_id')
            ->leftjoin('master_product_size as product_size', 'product_size.master_product_size_id', '=', 'order_item.master_product_size_id')
            ->leftjoin('order_delivery_item as delivery_item', 'delivery_item.order_item_id', '=', 'order_item.order_item_id')
            ->leftjoin('order_delivery as delivery', 'delivery.order_delivery_id', '=', 'delivery_item.order_delivery_id')
            ->where('order_item.order_id', $order_id)
            ->where('delivery.status', "05")
            ->groupBy(
                'order_item.order_item_id',
                'order_item.order_id',
                'order_item.price',
                'order_item.total_price',
                'order_item.qty',
                'order_item.remark',
                'order_item.addition',
                'order_item.total_size',
                'order_item.size_unit',
                'product_name',
                'product_type_name',
                'product_size_name',
                'product_size_unit',
                'product_weight',
                'delivery_item.order_item_id'
            )
            ->get();

        $data['OrderDeliveryItem'] = OrderDeliveryItem::select(
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
            'order_delivery_item.order_delivery_id',
            DB::raw("case when product.formula = '01' then product_size.name*0.35 else product_size.name end AS product_size_name"),
            'product_size.size_unit as product_size_unit',
            DB::raw("product_size.weight * order_delivery_item.qty AS product_weight")
            )
            ->join('order_item', 'order_item.order_item_id', '=', 'order_delivery_item.order_item_id')
            ->leftjoin('master_product_main as product', 'product.master_product_main_id', '=', 'order_item.master_product_main_id')
            ->leftjoin('master_product_type as product_type', 'product_type.master_product_type_id', '=', 'order_item.master_product_type_id')
            ->leftjoin('master_product_size as product_size', 'product_size.master_product_size_id', '=', 'order_item.master_product_size_id')
            ->where('order_delivery_item.order_delivery_id', $delivery_id)
            ->get();

            // dd($data['OrderDeliveryItem']);
            foreach ($data['OrderDeliveryItem'] as $ke => $value) {
                $dataclaimtitem = ProductReturnClaimItem::select(
                    'product_return_claim_item.order_item_id',
                    DB::raw('sum(product_return_claim_item.qty) AS sum_product_return_qty ')
                    )
                                ->leftjoin('product_return_claim', 'product_return_claim.product_return_claim_id','product_return_claim_item.product_return_claim_id')
                                ->where('product_return_claim.order_id', $order_id)
                                ->where('product_return_claim.order_delivery_id', $delivery_id)
                                ->where('product_return_claim_item.order_item_id', $value['order_item_id'])
                                ->groupBy('product_return_claim_item.order_item_id')
                                ->first();
                $value['sum_product_return_qty'] = (!empty($dataclaimtitem) ? $dataclaimtitem->sum_product_return_qty : 0);

            }
            // dd($data['OrderDeliveryItem']);
            //back up 02/12/2020
            // $productReturnClaim = ProductReturnClaim::select('product_return_claim_item.order_item_id',DB::raw('sum(product_return_claim_item.qty) AS sum_product_return_qty'))
            // ->leftjoin('product_return_claim_item', 'product_return_claim_item.product_return_claim_id','product_return_claim.product_return_claim_id')
            // ->where('product_return_claim.order_id', $order_id)
            // ->where('product_return_claim.order_delivery_id', $delivery_id)
            // ->groupBy('order_item_id')
            // ->get();

            // if (count($productReturnClaim) > 0) {
            //   foreach ($data['OrderDeliveryItem'] as $ke => $value) {
            //     foreach ($productReturnClaim as $key2 => $value2) {
            //       if ($value['order_item_id'] == $value2['order_item_id']) {
            //         $value['sum_product_return_qty'] = $value2['sum_product_return_qty'];
            //       }else {
            //         $value['sum_product_return_qty']  = 0;
            //       }
            //     }
            //   }
            // }else {
            //   foreach ($data['OrderDeliveryItem'] as $key => $value) {
            //     $data['OrderDeliveryItem'][$key]['sum_product_return_qty'] = 0;
            //   }
            // }



        $data['OrderDelivery'] = OrderDelivery::find($delivery_id);

        return view('pages.claim.claim_send', ['data' => $data, 'order_id' => $order_id, 'delivery_id' => $delivery_id]);
    }

    public function claimget_datashow($id1, $id2)
    {
        // dd($request);
        $order_id = $id1;
        $delivery_id  = $id2;

        $data['Order'] = $this->getOrder($order_id);

        $data['MasterRound'] = MasterRound::get();

        $data['MasterTruckType'] = $this->getMasterTruckType();

        $data['OrderDeliveryItem'] = OrderDeliveryItem::select(
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
            'order_delivery_item.order_delivery_id',
            DB::raw("case when product.formula = '01' then product_size.name*0.35 else product_size.name end AS product_size_name"),
            'product_size.size_unit as product_size_unit',
            DB::raw("product_size.weight * order_delivery_item.qty AS product_weight")
            )
            ->join('order_item', 'order_item.order_item_id', '=', 'order_delivery_item.order_item_id')
            ->leftjoin('master_product_main as product', 'product.master_product_main_id', '=', 'order_item.master_product_main_id')
            ->leftjoin('master_product_type as product_type', 'product_type.master_product_type_id', '=', 'order_item.master_product_type_id')
            ->leftjoin('master_product_size as product_size', 'product_size.master_product_size_id', '=', 'order_item.master_product_size_id')
            ->where('order_delivery_item.order_delivery_id', $delivery_id)
            ->get();



        $data['OrderDelivery'] = OrderDelivery::find($delivery_id);


        return response()->json(['status' => true, 'message' => 'ยืนยันข้อมูลการเคลม']);
        // return view('pages.claim.claim_confirm', ['data' => $data, 'order_id' => $order_id, 'delivery_id' => $delivery_id]);

    }

    public function confirm_create()
    {
        return view('pages.claim.claim_confirm');
    }

    public function createOrderClaim(Request $request)
    {

        $input = $request;
        $item = [];
        // foreach ($input['data_claim_return'] as $key => $value) {
        //     $Return_id = ProductReturnClaim::where('order_delivery_id', $value['order_delivery_id'])->where('type','01')->first();
        //     // dd($Return_id);
        //     if ($Return_id) {
        //         $delivery = OrderDelivery::where('product_return_claim_id', $Return_id->product_return_claim_id)->where('delivery_type', "02")->first();
        //         // dd($delivery,$Return_id->product_return_claim_id);
        //         if ($delivery) {
        //             array_push($item, $delivery);
        //         }
        //     }
        // }

        // if (count($item) > 0) {
        //     return response()->json(['status' => false, 'message' => "สินค้าชิ้นนี้อยู่ระหว่างการเคลม"]);
        // }

        // dd(count($item),$item);
        $validator = Validator::make($request->all(), [
            'date_send'     => 'required',
            'round'     => 'required',
            // 'truck_type' => 'required',
            // 'license_plate' => 'required',
        ], [
            'date_send.required' => 'จำเป็นต้องเลือกวันที่จัดส่ง',
            'round.required' => 'จำเป็นต้องเลือกรอบจัดส่ง ',
            // 'truck_type.required' => 'จำเป็นต้องเลือกประเภทรถ ',
            // 'license_plate.required' => 'จำเป็นต้องเลือกป้ายทะเบียน ',
        ]);

        // check required parameter
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }
        // check count product
        if (count($input['data_claim_return']) > 6) {
            return response()->json(['status' => false, 'message' => 'เลือกส่งสินค้าได้ไม่เกิน 6 รายการ']);
        }
        // dd($Return_id,count($Return_id),$delivery);

        // check truck
            // หารถที่มารับเอง
                    $turk = MasterTruckType::select('master_truck_type.type','master_truck_type.master_truck_type_id','master_truck.master_truck_id')
                    ->leftjoin('master_truck as master_truck', 'master_truck.master_truck_type_id', '=', 'master_truck_type.master_truck_type_id')
                    ->where('master_truck_type.type','like','%มารับเอง%')
                    ->first();
            // หารถที่มารับเอง
            $checktruk = OrderDelivery::select('order_delivery.status','order_delivery.order_delivery_id','schedule.master_truck_id','schedule.master_round_id','schedule.date_schedule')
                        ->leftjoin('truck_schedule as schedule', 'schedule.order_delivery_id', '=', 'order_delivery.order_delivery_id')
                        ->where(function($query) use($input,$turk){
                            $query = $query->where('schedule.master_truck_id',$input['license_plate'])
                                            ->whereNotNull('schedule.master_truck_id')
                                            ->where('schedule.master_truck_id','!=',$turk->master_truck_id);
                        })
                        ->where('schedule.master_round_id',$input['round'])
                        ->where('schedule.date_schedule',Carbon::parse($input['date_send'])->format('Y-m-d H:i:s'))
                        ->whereIN('order_delivery.status',['01','02'])
                        ->get();
            if(count($checktruk) > 0)
            {
                return response()->json(['status' => false, 'message' => 'รถคันนี้อยู่ระหว่างจัดส่งสินค้า']);
            }
        // check truck

        // insert Product Claim
        $product_return_claim_id = ProductReturnClaim::insertGetId([
            'order_id' => $input['order_id'],
            'order_delivery_id' => $input['delivery_id'],
            'type' => "01",
            'created_by' => Auth::id(),
            'created_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
        ]);

        $qty_all = 0;
        $product_weight = 0;
        foreach ($input['data_claim_return'] as $key => $value) {
            // insert Product Claim
            $product_return_claim_item_id = ProductReturnClaimItem::insert([
                'product_return_claim_id' => $product_return_claim_id,
                'order_item_id' => $value['order_item_id'],
                'qty' => $value['qty'],
            ]);

            $qty_all += ($value['qty'] * 1);
            $product_weight += ($value['product_weight_int'] * 1);
        }


        //หาเลขรายการล่าสุด
        $last_delivery = OrderDelivery::select('order_delivery_number')->where('order_id', $input['order_id'])->orderBy('order_delivery_id', 'desc')->first();
        $order_delivery_number = (!empty($last_delivery)) ? $last_delivery->order_delivery_number + 1 : 1;

        // dd($last_delivery,str_pad($order_delivery_number, 4, '0', STR_PAD_LEFT));
        // insert OrderDelivery
        $order_delivery_id = OrderDelivery::insertGetId([
            'order_id' => $input['order_id'],
            'order_delivery_number' => str_pad($order_delivery_number, 4, '0', STR_PAD_LEFT),
            'product_return_claim_id' => (isset($product_return_claim_id)) ? $product_return_claim_id : null,
            'remark' => 'เคลมสินค้า',
            'type' => "00",
            'status' => '01',
            'delivery_type' => '02',
            'qty_all' => (isset($qty_all)) ? $qty_all : 0,
            'weight' => $product_weight,
            'created_date' => Auth::id(),
            'created_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
            'updated_by' => Auth::id(),
            'created_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),

        ]);

        $order_delivery_item = [];
        foreach ($input['data_claim_return'] as $ke => $val) {
            $order_delivery_item[] = [
                'order_delivery_id' => $order_delivery_id,
                'order_item_id' => $val['order_item_id'],
                'qty' => $val['qty'],
                'weight' => $val['product_weight_int'],
            ];
        }

        // insert truck_schedule
        TruckSchedule::insert([
            'order_delivery_id' => $order_delivery_id,
            'master_round_id' => $input['round'],
            'master_truck_id' => $input['license_plate'] ?? null,
            'date_schedule' => Carbon::parse($input['date_send'])->format('Y-m-d H:i:s'),
            'created_by' => Auth::id(),
            'created_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
        ]);

        OrderDeliveryItem::insert($order_delivery_item);
        Order::where('order_id',$input['order_id'])->update(['updated_date'=>Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s')]);


        return response()->json(['status' => true, 'message' => 'บันทึกข้อมูลสำเร็จ']);
    }

    public function createOrderreturn(Request $request)
    {
        // dd($request);

        $input = $request;
        $item = [];
        // foreach ($input['data_claim_return'] as $key => $value) {
        //     $Return_id = ProductReturnClaim::where('order_delivery_id', $value['order_delivery_id'])->where('type','02')->first();
        //     if ($Return_id) {
        //         $delivery = OrderDelivery::where('product_return_claim_id', $Return_id->product_return_claim_id)->where('delivery_type', "01")->first();
        //         if ($delivery) {
        //             array_push($item, $delivery);
        //         }
        //     }
        // }
        // // dd($item);
        // if (count($item) > 0) {
        //     return response()->json(['status' => false, 'message' => "สินค้าชิ้นนี้อยู่ระหว่างการเคลม"]);
        // }



        // insert Product Claim
        $product_return_claim_id = ProductReturnClaim::insertGetId([
            'order_id' => $input['order_id'],
            'order_delivery_id' => $input['delivery_id'],
            'type' => "00",
            'created_by' => Auth::id(),
            'created_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
        ]);

        $qty_all = 0;
        $product_weight = 0;
        foreach ($input['data_claim_return'] as $key => $value) {
            // insert Product Claim
            $product_return_claim_item_id = ProductReturnClaimItem::insert([
                'product_return_claim_id' => $product_return_claim_id,
                'order_item_id' => $value['order_item_id'],
                'qty' => $value['qty'],
            ]);

            $qty_all += ($value['qty'] * 1);
            $product_weight += ($value['product_weight_int'] * 1);
        }


        //หาเลขรายการล่าสุด
        $last_delivery = OrderDelivery::select('order_delivery_number')->where('order_id', $input['order_id'])->orderBy('order_delivery_id', 'desc')->first();
        $order_delivery_number = (!empty($last_delivery)) ? (int)$last_delivery->order_delivery_number + 1 : 1;
        // insert OrderDelivery
        $order_delivery_id =    OrderDelivery::insertGetId([
            'order_id' => $input['order_id'],
            'order_delivery_number' => str_pad($order_delivery_number, 4, '0', STR_PAD_LEFT),
            'product_return_claim_id' => (isset($product_return_claim_id)) ? $product_return_claim_id : null,
            'remark' => 'คืนสินค้า',
            'type' => "00",
            'status' => '05',
            'delivery_type' => '01',
            'qty_all' => (isset($qty_all)) ? $qty_all : 0,
            'weight' => $product_weight,
            'created_date' => Auth::id(),
            'created_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
            'updated_by' => Auth::id(),
            'created_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),

        ]);

        $order_delivery_item = [];
        foreach ($input['data_claim_return'] as $ke => $val) {
            $order_delivery_item[] = [
                'order_delivery_id' => $order_delivery_id,
                'order_item_id' => $val['order_item_id'],
                'qty' => $val['qty'],
                'weight' => $val['product_weight_int'],
            ];
        }
        OrderDeliveryItem::insert($order_delivery_item);


        return response()->json(['status' => true, 'message' => 'บันทึกข้อมูลสำเร็จ']);
    }
}
