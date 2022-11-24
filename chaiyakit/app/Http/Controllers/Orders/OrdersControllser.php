<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterMerchant;
use App\Models\MasterMerchantPrice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MasterVat;
use App\Models\MasterProductMain;
use App\Models\MasterProductSize;
use App\Models\MasterProductType;
use App\Models\OrderDelivery;
use App\Models\Tools;
use App\Http\Controllers\Delivery\DeliveryController;
use DataTables;
use Carbon\Carbon;
use DB;
use Validator;

class OrdersControllser extends Controller
{
    public function __construct()
    {
    }
    public function index()
    {
        return view('pages.orders.index');
    }
    //สร้างใบสั่งซื้อ
    public function create(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'master_merchant_id' => 'required|numeric',
            'carts' => 'required|array',
            'total_price' => 'required|numeric',
            'vat' => 'required|numeric',
            'grand_total' => 'required|numeric',
            'qty_all' => 'required|integer',
            'order_vat_no' => 'required|numeric',
            'total_price' => 'required|numeric',
            'order_status_vat' => 'required|size:2',
            'order_type' => 'required|size:2',
        ]);
        if (!$validator->fails()) {
            //add Order
            $last_order = Order::select('order_id')->orderBy('order_id', 'desc')->first();
            $order_number = (!empty($last_order)) ? $last_order->order_id + 1 : 1;
            $insert = new Order;
            $insert->merchant_id = $request->master_merchant_id;
            $insert->order_number = str_pad($order_number, 4, '0', STR_PAD_LEFT);
            $insert->staus_send = '00';
            $insert->status_vat = $request->order_status_vat;
            $insert->order_type = $request->order_type;
            $insert->price_all = $request->total_price;
            $insert->grand_total = $request->grand_total;
            $insert->vat = $request->vat;
            $insert->weight = $request->total_weight;
            $insert->qty_all = $request->qty_all;
            $insert->vat_no = $request->order_vat_no;
            $insert->created_by = $request->user()->id;
            $insert->department_name = $request->department_name;
            $insert->datemarksend = date('Y-m-d',strtotime($request->datemarksend));
            $insert->noteorder = $request->noteorder;
            $insert->status_departmen = $request->department_status;
            $insert->phone_department = $request->phone_department;
            $insert->address_department = $request->address_department;
            $insert->save();

            // // Update new phone & address
            // if($request->department_name != ''){
            //     MasterMerchant::where('master_merchant_id', $request->master_merchant_id)
            //     ->update([
            //         'phone_number' => $request->phone_number,
            //         'address' => $request->address,
            //     ]);
            // }

            //add Order Item
            $order_item = [];
            foreach ($request->carts as $key => $item) {
                $order_item[] = [
                    'order_id' => $insert->order_id,
                    'master_product_main_id' => $item['size']['master_product_main_id'],
                    'master_product_type_id' => $item['size']['master_product_type_id'],
                    'master_product_size_id' => $item['size']['master_product_size_id'],
                    'price' => $item['price'],
                    'addition' => $item['price_add'],
                    'qty' => $item['qty'],
                    'total_price' => $item['total_price'],
                    'total_size' => $item['total_size'],
                    'size_unit' => $item['size_unit'],
                    'weight' => $item['weight_total'],
                    'remark' => $item['remark'],
                ];
                if ($item['price'] > 0) {
                    $check_price = MasterMerchantPrice::where('master_merchant_id', $request->master_merchant_id)
                        ->where('master_product_main_id', $item['size']['master_product_main_id'])
                        ->where('master_product_type_id', $item['size']['master_product_type_id'])
                        ->where('master_product_size_id', $item['size']['master_product_size_id'])->count();
                    if ($check_price == 0) {
                        MasterMerchantPrice::insert([
                            'master_merchant_id' => $request->master_merchant_id,
                            'master_product_main_id' => $item['size']['master_product_main_id'],
                            'master_product_type_id' => $item['size']['master_product_type_id'],
                            'master_product_size_id' => $item['size']['master_product_size_id'],
                            'price' => $item['price'],
                            'created_by' => $request->user()->id
                        ]);
                    }
                }
            }
            if (!empty($order_item)) {
                DB::table('order_item')->insert($order_item);
            }
            $data = [
                'order_id' => $insert->order_id,
                'product_return_claim_id' => null,
                'remark' => null,
                'type' => '00',
                'status' => '00',
                'delivery_type' => '00',
                'qty_all' => $request->qty_all,
                'price_all' => $request->total_price,
                'vat' => $request->vat,
                'grand_total' => $request->grand_total,
                'weight' => $request->total_weight
            ];

            $order_delivery_id =  DeliveryController::addOrderDelivery($data, []);
            return response()->json(['success' => true, 'order_id' => $insert->order_id, 'order_delivery_id' => $order_delivery_id]);
        } else {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }
    }
    //หน้าสร้างใบสั่งซื้อ
    public function add()
    {
        $masterVat = MasterVat::where('vat', '>', 0)->orderby('vat')->get();
        $defaultVat = MasterVat::where('is_default', '01')->first();
        $ProductMain = MasterProductMain::where('status', '01')->orderby('name')->get();
        $products = [];
        $items = [];

        foreach ($ProductMain as $row) {


            $row->spec = MasterProductType::select(DB::raw('IF(master_product_size.master_product_type_id,master_product_type.name,\'\') as type_name'), 'master_product_size.name as size_name', 'master_product_size.master_product_size_id', 'master_product_size.master_product_type_id', 'master_product_size.master_product_main_id', 'master_product_size.size_unit')

                ->leftJoin('master_product_size', function ($join) {
                    $join->on('master_product_size.master_product_type_id', '=', 'master_product_type.master_product_type_id')
                        ->orWhereNull('master_product_size.master_product_type_id');
                })
                ->where('master_product_type.status', '01')->where('master_product_size.status', '01')->where('master_product_size.master_product_main_id', $row->master_product_main_id)

                ->orderby('master_product_type.name')
                ->orderby('master_product_size.name')
                ->get();

            $products[] = $row;

        }
        $main_group_name = MasterProductMain::select('name')->where('status', '01')->where('status_special', '01')->where('name', '!=', '')->where('status_special', '01')->groupBy('name')->get();
        $type_group_name = MasterProductType::join('master_product_main', 'master_product_main.master_product_main_id', '=', 'master_product_type.master_product_main_id')
            ->select('master_product_type.name')->where('master_product_type.status', '01')->where('master_product_type.name', '!=', '')
            ->where('master_product_main.status_special', '01')->groupBy('master_product_type.name')->get();
        $size_group_name = MasterProductSize::join('master_product_main', 'master_product_main.master_product_main_id', '=', 'master_product_size.master_product_main_id')
            ->select('master_product_size.name', 'master_product_size.size_unit', 'master_product_main.formula')->where('master_product_size.status', '01')->where('master_product_size.name', '!=', '')
            ->where('master_product_main.status_special', '01')
            ->groupBy('master_product_size.name', 'master_product_size.size_unit', 'master_product_main.formula')->get();
        // $type_group_name_null = MasterProductType::select('master_product_type_id','master_product_main_id')->where('status','01')->where('name','')->get();
        // $items['type_null']=$type_group_name_null;
        $items['size_unit'] = config('sizeunit');
        foreach ($main_group_name as $row) {
            $items['name']['main'][] = $row->name;
            $items['main'][$row->name] = MasterProductMain::select('master_product_main_id', 'status_detail', 'name as product_name')->where('name', $row->name)->where('status', '01')->where('status_special', '01')->get();
        }


        foreach ($type_group_name as $row) {
            $items['name']['type'][] = $row->name;
            $items['type'][$row->name] = MasterProductType::select('master_product_type_id', 'master_product_main_id', 'name as type_name')->where('status', '01')->where('name', $row->name)->get();
        }


        foreach ($size_group_name as $row) {
            $name = ($row->formula == '01' ? '0.35x' : '') . $row->getFormattedSizeAttribute() . ' ' . $items['size_unit'][$row->size_unit];
            $items['name']['size'][] = $name;
            $items['size'][$name] = MasterProductSize::select('master_product_size.master_product_size_id', 'master_product_size.master_product_type_id', 'master_product_size.master_product_main_id', 'master_product_size.name as size', DB::raw('\'' . $name . '\' as size_name'), 'master_product_size.weight', 'master_product_size.size_unit', 'master_product_main.formula')
                ->join('master_product_main', 'master_product_main.master_product_main_id', '=', 'master_product_size.master_product_main_id')
                ->where('master_product_main.status', '01')->where('master_product_main.status_special', '01')->where('master_product_size.status', '01')
                ->where('master_product_size.name', $row->name)->where('master_product_size.size_unit', $row->size_unit)->get();
        }
        $items['count_unit'] = config('countunit');
        // print_r($items);
        // return response()->json($items);
        // exit;
        // $items['type']
        if(!isset($items['type'])){
            $items['type']=[];
        }
        return view('pages.orders.create', compact('masterVat', 'defaultVat', 'products', 'items'));
    }
    //หน้ารายการใบสั่งซื้อ
    public function getData(Request $request)
    {

        $data = Order::select('orders.*', 'master_merchant.name_merchant', 'master_merchant.name_department','admincreate.name as create_name','adminupdate.name as update_name')
        ->join('master_merchant', 'orders.merchant_id', '=', 'master_merchant.master_merchant_id')
        ->join('users as admincreate', 'admincreate.id', '=', 'orders.created_by')
        ->join('users as adminupdate', 'adminupdate.id', '=', 'orders.created_by')
        ->orderBy('updated_date', 'desc')->orderBy('created_date', 'desc');
        if ($request->search_key) {
            $data->where(function ($query) use ($request) {
                $query->where('orders.order_number', 'LIKE', $request->search_key . '%');
                $query->orWhere('orders.department_name', 'LIKE', '%' . $request->search_key . '%');
                $query->orWhere('master_merchant.name_merchant', 'LIKE', '%' . $request->search_key . '%');
            });
        }
        if ($request->search_type) {
            $data->where('orders.staus_send',  $request->search_type);
        }
        if ($request->startDate && $request->endDate) {
            // $data->whereDate('orders.created_date', '>=',  $request->startDate)->whereDate('orders.created_date', '<=',  $request->startDate);
            $data->whereBetween('orders.created_date',[date('Y-m-d H:i:s',strtotime($request->startDate .' 00:00:00')),date('Y-m-d H:i:s',strtotime($request->endDate .' 23:59:59'))]);
        } else {
            $data->whereDate('orders.created_date', '>=',  Carbon::now('Asia/Bangkok')->subMonths(6))->whereDate('orders.created_date', '<=',  Carbon::now('Asia/Bangkok'));
        }
        if(\Auth::user()->hasRole('admin')){
            $data->where('orders.order_type', '00');
        }
        if(\Auth::user()->hasRole('accounting')){
            $data->where('orders.vat_no','!=', '0.00');
        }
        // $data->where('orders.updated_date', 'desc');
        // $data->where('orders.created_date', 'desc');
        return Datatables::of($data->get())->editColumn('created_date', '{{ formatDateThat($created_date)}}')
            ->addColumn('name_department', function ($data) {


                return (!empty($data->name_department)) ? $data->name_department : '-';
            })
            ->addColumn('price_all', function ($data) {

                // $dt = Carbon::createFromFormat('Y-m-d H:i:s', $data->created_date);
                // return $dt->addYears(543)->format('d/m/Y H:i:s');
                return number_format($data->price_all, 2);
            })
            ->addColumn('created_date', function ($data) {

                // $dt = Carbon::createFromFormat('Y-m-d H:i:s', $data->created_date);
                // return $dt->addYears(543)->format('d/m/Y H:i:s');
                return $data->getFormattedCreatedDate();
            })
            ->addColumn('status_text', function ($data) {
                $status = '';
                if ($data->staus_send == '00') {
                    $status = '<span class="badge-pill text-white py-1" style="font-size:12px; background-color:#FFCC00">สั่งซื้อสินค้า</span>';
                } else if ($data->staus_send == '01') {
                    $status = '<span class="badge-pill text-white py-1" style="font-size:12px; background-color:#0099FF">ดำเนินการจัดส่ง</span>';
                } else if ($data->staus_send == '02') {
                    $status = '<span class="badge-pill text-white py-1" style="font-size:12px; background-color:#EE6C98">เคลม</span>';
                } else if ($data->staus_send == '03') {
                    $status = '<span class="badge-pill text-white py-1" style="font-size:12px; background-color:#27C671">จัดส่งสำเร็จ</span>';
                } else if ($data->staus_send == '04') {
                    $status = '<span class="badge-pill text-white py-1" style="font-size:12px; background-color:#ed2323">ยกเลิกบิล</span>';
                }

                return $status;
            })
            ->rawColumns(['status_text'])
            ->make(true);
    }
    //ค้นหาร้านค้า
    public function search(Request $request)
    {
        $search = $request->get('term');

        $result = MasterMerchant::select('master_merchant_id', 'name_merchant', 'name_department', 'tax_number', 'phone_number','phone_number2','phone_number3','phone_number4','fax', 'address', 'remark')->where('name_merchant', 'LIKE', '%' . $search . '%')->orderby('name_merchant')->limit(10)->get();

        return response()->json($result);
    }
    //ดึงราคาของร้านค้า
    public function getPrice(Request $request)
    {
        $id = $request->get('id');

        $variable = MasterMerchantPrice::select('master_product_main_id', 'master_product_type_id', 'master_product_size_id', 'price')->where('master_merchant_id',  $id)->get();
        $result = [];
        foreach ($variable as $row) {
            $result[$row->master_product_size_id] = $row;
        }
        return response()->json($result);
    }
    public function summary($order_id, $delivery_id)
    {
        $data['Order'] = $this->getOrder($order_id);
        $data['OrderDelivery'] = OrderDelivery::select(
            'order_delivery.order_delivery_id',
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

        return view('pages.orders.summary', ['data' => $data, 'order_id' => $order_id, 'delivery_id' => $delivery_id]);
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
            'orders.noteorder',
            'orders.datemarksend',
            'merchant.master_merchant_id',
            'merchant.name_merchant',
            'merchant.name_department',
            'merchant.address',
            'merchant.phone_number',
            'merchant.phone_number2',
            'merchant.phone_number3',
            'merchant.phone_number4',
            'merchant.fax',
            'merchant.tax_number',
            'admin.name as admin_name',
            'orders.status_departmen',
            'orders.phone_department',
            'orders.address_department',
            DB::raw('sum( case when delivery.status = "05" then delivery_item.qty else 0 end ) AS sum_order_delivery_item ')
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
    public function detail($id)
    {
        // dd($id, $delivery_id);
        $data['Order'] = $this->getOrder($id);
        $OrderItem = OrderItem::select(
            'order_item.order_item_id',
            'order_item.order_id',
            'order_item.price',
            'order_item.total_price',
            'order_item.qty',
            'order_item.remark',
            'order_item.total_size',
            'order_item.size_unit',
            'product.name as product_name',
            'product.unit_count as unit_count',
            'product_type.name as product_type_name',
            'product_size.name as product_size_name',
            'order_item.weight AS product_weight',
            'product.formula',
            'delivery.delivery_type',
            'order_item.addition',
            'delivery.status',
            'orders.vat_no',
            DB::raw('delivery_item.qty AS delivery_qty '),
            // DB::raw('sum( delivery_item.qty ) AS sum_order_delivery_item ')
        )
            ->leftjoin('master_product_main as product', 'product.master_product_main_id', '=', 'order_item.master_product_main_id')
            ->leftjoin('master_product_type as product_type', 'product_type.master_product_type_id', '=', 'order_item.master_product_type_id')
            ->leftjoin('master_product_size as product_size', 'product_size.master_product_size_id', '=', 'order_item.master_product_size_id')
            ->leftjoin('order_delivery_item as delivery_item', 'delivery_item.order_item_id', '=', 'order_item.order_item_id')
            ->leftjoin('order_delivery as delivery', 'delivery.order_delivery_id', '=', 'delivery_item.order_delivery_id')
            ->leftjoin('orders as orders', 'orders.order_id', '=', 'order_item.order_id')
            // ->where('delivery_item.order_delivery_id', $delivery_id)
            ->where('order_item.order_id', $id)
            ->get();
        $data['order_item'] = [];
        $data['OrderItem'] = [];
        //dd($OrderItem);
        $sumcashreturn = 0;
        foreach ($OrderItem as $row) {
            if(!isset($sum_order_delivery_item[$row->order_item_id])) $sum_order_delivery_item[$row->order_item_id] = 0;
            if(!isset($data['order_item'][$row->order_item_id]['return'])) $data['order_item'][$row->order_item_id]['return'] = 0;
            if(!isset($item_price[$row->order_item_id])) $item_price[$row->order_item_id] = 0;
            if(!isset($qty[$row->order_item_id])) $qty[$row->order_item_id] = 0;
            if(!isset($grand_total)) $grand_total = 0;

            if($row->delivery_type != '01' && $row->delivery_type != '02') $qty[$row->order_item_id]  = $row->qty;
            $weight_data = Tools::convertdWeight($row->product_weight);
            $row->total_size_text = ($row->formula == '01') ? round($row->total_size, 4) . ' ตร.ม.' : round($row->total_size, 2) . ' ' . config('sizeunit')[$row->size_unit];
            $row->total_size_number = ($row->formula == '01') ? round($row->total_size, 4)  : round($row->total_size, 2);
            $row->product_weight_text = $weight_data['number'] . ' ' . $weight_data['unit'];
            $row->product_size_name_text = ($row->formula == '01') ? '0.35x' . sprintf('%g', $row->product_size_name) . ' ' . config('sizeunit')[$row->size_unit] : sprintf('%g', $row->product_size_name) . ' ' . config('sizeunit')[$row->size_unit];
            if($row->delivery_type == '01'){
                $data['order_item'][$row->order_item_id]['return'] += $row->delivery_qty;
                $qty[$row->order_item_id]  -= $row->delivery_qty;
                // $sum_order_delivery_item[$row->order_item_id] -= $row->delivery_qty;
                $item_price[$row->order_item_id] -= ($row->total_price / $row->qty) * $row->delivery_qty;
                $grand_total -= ($row->total_price / $row->qty) * $row->delivery_qty;
                $sumpricereturmrow = (($row->addition+$row->price) * $row->total_size_number ) * $row->delivery_qty;
                $sumvatreturmrow = ($sumpricereturmrow / 100) * $row->vat_no;
                $sumpricevatreturmrow =  $sumpricereturmrow + $sumvatreturmrow;
                $sumcashreturn = $sumcashreturn + $sumpricevatreturmrow;
            }else if($row->delivery_type == '02' && $row->status != '05'){
            //    $sum_order_delivery_item[$row->order_item_id] -= $row->delivery_qty;
            //    $item_price[$row->order_item_id] -= ($row->total_price / $row->qty) * $row->delivery_qty;
            }else if($row->delivery_type != '02' && $row->status == '05'){
                $sum_order_delivery_item[$row->order_item_id] += $row->delivery_qty;
                $item_price[$row->order_item_id] += ($row->total_price / $row->qty) * $qty[$row->order_item_id];
                $grand_total += $row->total_price;
                $qty[$row->order_item_id]  = $row->qty;
            } else if($row->delivery_type != '02'){
              //  $sum_order_delivery_item[$row->order_item_id] += $row->delivery_qty;
                $item_price[$row->order_item_id] += ($row->total_price / $row->qty) * $qty[$row->order_item_id];
                $grand_total += $row->total_price;
                $qty[$row->order_item_id]  = $row->qty;
            }

            if($sum_order_delivery_item[$row->order_item_id]<0) $sum_order_delivery_item[$row->order_item_id] *=  -1;
            $data['OrderItem'][$row->order_item_id] = $row;
            $data['order_item'][$row->order_item_id]['sum_order_delivery_item'] = $sum_order_delivery_item[$row->order_item_id];
            $data['order_item'][$row->order_item_id]['qty'] = $qty[$row->order_item_id];
            $data['order_item'][$row->order_item_id]['total_price'] = $item_price[$row->order_item_id];
        }
        $data['order_item']['price_all'] = $grand_total;
        $data['order_item']['vat'] = $grand_total / 100 * $data['Order']->vat_no;
        $data['order_item']['grand_total']  = $grand_total +  $data['Order']['vat'];
        $data['OrderItem'] = array_values($data['OrderItem']);
        $data['OrderSumPriceReturn'] = $sumcashreturn;
        return view('pages.orders.order_detail', ['data' => $data, 'countunit' => config('countunit'), 'sizeunit' => config('sizeunit')]);
    }
    // insert Master Merchant
    public function insertMerchant(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_merchant'     => 'required|unique:master_merchant,name_merchant,NULL,master_merchant_id,deleted_date,NULL',

        ], [
            'name_merchant.required' => 'กรุณากรอกชื่อร้านค้า',
            'name_merchant.unique' => 'ชื่อนี้ถูกใช้งานแล้ว',

        ]);

        // check required parameter
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }
        $check_name = MasterMerchant::where('name_merchant', $request->name_merchant)->count();
        if (!$check_name) {
            $master_merchant_id =  MasterMerchant::insertGetId([
                'name_merchant' => $request->name_merchant,
                'name_department' => $request->name_department,
                'tax_number' => $request->tax_number,
                'phone_number' => $request->phone_number,
                'phone_number2' => $request->phone_number2,
                'phone_number3' => $request->phone_number3,
                'phone_number4' => $request->phone_number4,
                'fax' => $request->fax,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'link_google_map' => $request->link_google_map,
                'created_by' => $request->user()->id,
                'created_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
            ]);
            //add master_merchant_price
            $data_price = [];
            foreach ($request->u_price as $id => $value) {
                foreach ($value as $type => $value1) {
                    foreach ($value1 as $size => $value_size) {
                        if ($value_size > 0) {
                            $data_price[] = [
                                'master_merchant_id' => $master_merchant_id,
                                'master_product_main_id' => $id,
                                'master_product_type_id' => $type,
                                'master_product_size_id' => $size,
                                'price' => $value_size,
                            ];
                        }
                    }
                }
            }
            if (!empty($data_price)) {
                MasterMerchantPrice::insert($data_price);
            }
            $result = MasterMerchant::select('master_merchant_id', 'name_merchant', 'name_department', 'tax_number', 'phone_number', 'address', 'remark')->where('master_merchant_id',  $master_merchant_id)->first();

            return response()->json(['success' => true, 'message' => 'เพิ่มข้อมูลสำเร็จ', 'data' => $result]);
        } else {
            // case duplicate
            return response()->json(['success' => false, 'message' => 'ชื่อนี้มีการใช้งานแล้ว']);
        }
    }

    // insert Master Merchant
    public function updateMerchant(Request $request)
    {
        $master_merchant_id =  $request->master_merchant_id;

        //add master_merchant_price
        $data_price = [];
        foreach ($request->u_price as $id => $value) {
            foreach ($value as $type => $value1) {
                foreach ($value1 as $size => $value_size) {
                    if ($value_size > 0) {
                        if ($request->u_id[$id][$type][$size] == '0') {
                            $data_price[] = [
                                'master_merchant_id' => $master_merchant_id,
                                'master_product_main_id' => $id,
                                'master_product_type_id' => $type,
                                'master_product_size_id' => $size,
                                'price' => $value_size,
                            ];
                        } else {
                            MasterMerchantPrice::where('master_merchant_price_id', $request->u_id[$id][$type][$size])->update([
                                'price' => $value_size,
                            ]);
                        }
                    }
                }
            }
        }

        if (!empty($data_price)) {
            MasterMerchantPrice::insert($data_price);
        }
        $result = MasterMerchant::select('master_merchant_id', 'name_merchant', 'name_department', 'tax_number', 'phone_number', 'address', 'remark')->where('master_merchant_id',  $master_merchant_id)->first();

        return response()->json(['success' => true, 'message' => 'เพิ่มข้อมูลสำเร็จ', 'data' => $result]);
    }

    public function getMasterMerchantPrice($id)
    {
        return response()->json(['data' => MasterMerchantPrice::where('master_merchant_id', $id)->get()]);
    }

    //ค้นหาสินค้าอื่นๆ
    public function searchOther(Request $request)
    {
        $search = $request->get('term');

        $result = MasterProductMain::select('master_product_main_id', 'name', 'status_detail', 'formula', 'unit_count')->where('name', 'LIKE', '%' . $search . '%')->where('status_special', '00')->where('status', '01')->orderby('name')->limit(10)->get();

        return response()->json($result);
    }
    //ค้นหาประเภทสินค้าอื่นๆ
    public function searchTypeOther(Request $request)
    {
        $search = $request->get('term');
        $main_id = $request->get('main_id');

        $result = MasterProductType::select('master_product_type_id', 'master_product_main_id', 'name')->where('name', 'LIKE', '%' . trim($search) . '%')->where('master_product_main_id', $main_id)->where('status', '01')->orderby('name')->get();

        return response()->json($result);
    }
    //ค้นหาประเภทสินค้าอื่นๆ
    public function searchSizeOther(Request $request)
    {

        $main_id = $request->get('main_id');
        $type_id = $request->get('type_id');

        $result = MasterProductSize::select('master_product_size_id', 'master_product_type_id', 'master_product_main_id', 'name', 'weight', 'size_unit')->where('master_product_main_id', $main_id)
            ->where('status', '01')
            ->where(function ($query) use ($type_id) {
                if (!empty($type_id)) {
                    $query->where('master_product_type_id', $type_id);
                }
            })->orderby('name')->get();

        return response()->json($result);
    }
    //เพิ่มสินค้าอื่นๆ
    public function createOther(Request $request)
    {
        //add new product main
        $main_id = $request->main_id;
        $chk_main_name = MasterProductMain::where('name', $request->main_name)->first();
        if (empty($request->main_id) && empty($chk_main_name)) {

            $main_id = MasterProductMain::insertGetId([
                'name' => $request->main_name,
                'status_detail' => (empty($request->type_name)) ? '01' : '00',
                'status_special' => '00',
                'formula' => $request->formula,
                'unit_count' => $request->main_count_unit,
                'created_by' => $request->user()->id
            ]);
        } else if (empty($request->main_id) && !empty($chk_main_name)) {
            $main_id = $chk_main_name->master_product_main_id;
        }
        //add new product type
        $type_id = $request->type_id;
        $type_name = (empty($request->type_name)) ? '' : $request->type_name;
        $chk_type_name = MasterProductType::where('name', $type_name)->where('master_product_main_id', $main_id)->first();
        if (empty($request->type_id) && empty($chk_type_name)) {
            $type_id = MasterProductType::insertGetId([
                'name' => $type_name,
                'master_product_main_id' => $main_id,
                'created_by' => $request->user()->id
            ]);
        } else if (empty($request->type_id) && !empty($chk_type_name)) {
            $type_id = $chk_type_name->master_product_type_id;
        }
        //add new product size
        $size_id = $request->size_id;
        $chk_size_name = MasterProductSize::where('name', $request->size_name)->where('master_product_main_id', $main_id)->where('master_product_type_id', $type_id)->count();
        if (empty($request->size_id) &&  $chk_size_name == 0) {
            $size_id = MasterProductSize::insertGetId([
                'name' => $request->size_name,
                'master_product_main_id' => $main_id,
                'master_product_type_id' => $type_id,
                'weight' => $request->size_weight,
                'size_unit' => $request->size_unit,
                'created_by' => $request->user()->id
            ]);
        }
        $result =  MasterProductSize::select('master_product_size.master_product_size_id', 'master_product_size.master_product_type_id', 'master_product_size.master_product_main_id', 'master_product_size.name', 'master_product_size.name as size', 'master_product_size.weight', 'master_product_size.size_unit', 'master_product_main.formula')
            ->join('master_product_main', 'master_product_main.master_product_main_id', '=', 'master_product_size.master_product_main_id')
            ->where('master_product_main.status', '01')->where('master_product_size.status', '01')
            ->where('master_product_size.master_product_size_id', $size_id)->first();
        $result->size_name = ($result->formula == '01' ? '0.35x' : '') . $result->getFormattedSizeAttribute() . ' ' . config('sizeunit')[$result->size_unit];
        return response()->json(['success' => true, 'message' => 'เพิ่มข้อมูลสำเร็จ', 'data' => $result]);
    }

    public function Cancelorder(Request $request)
    {
        if($request->ajax())
        {
            try{
                if(\Auth::user()->hasRole('super-admin')) :
                    $order = Order::find($request->order_id);
                    if($order->staus_send != '00') :
                        return response()->json(['status' =>'error','mgs' => 'รายการนี้ได้มีการทำการจัดส่งไปแล้ว'],200);
                    endif;
                    $order->staus_send = '04';
                    $order->save();
                    return response()->json(['status' =>'success','mgs' => 'ยกเลิกใบสั่งซื้อนี้เรียบร้อยแล้ว'],200);
                else :
                    return response()->json(['status' =>'error','mgs' => 'คุณไม่มีสิทธิ์ในการทำรายการนี้'],101);
                endif;
            }catch(\Exception $e) {
                return response()->json(['status' =>'error','mgs' => 'เกิดข้อผิดพลาด','mgsserve'=> $e->getMessage()],500);
            }

            // return $request->all();
        }
    }

    public function updatedepartment()
    {
        $orders = Order::get();
        foreach($orders as $order)
        {
            $order->department_name = $order->getCustomer->name_department;
            $order->save();
        }
        dd('เสร็จแล้ว');
        exit;
        // dd($orders[0]->getCustomer->name_department);
    }
    public function ItemsSize (Request $request)
    {
        $size_group_name = MasterProductSize::join('master_product_main', 'master_product_main.master_product_main_id', '=', 'master_product_size.master_product_main_id')
            ->join('master_product_type', 'master_product_type.master_product_type_id', '=', 'master_product_size.master_product_type_id')
            ->select('master_product_size.name', 'master_product_size.size_unit', 'master_product_main.formula','master_product_type.name as name_type')->where('master_product_size.status', '01')->where('master_product_size.name', '!=', '')
            ->where('master_product_main.status_special', '01')
            ->where('master_product_size.master_product_main_id', $request->main_product_id)
            ->where('master_product_type.name', $request->curr_name)
            ->groupBy('master_product_size.name', 'master_product_size.size_unit', 'master_product_main.formula')->get();
        $items = [];
        $items['size_unit'] = config('sizeunit');
        foreach ($size_group_name as $row) {
            $name = ($row->formula == '01' ? '0.35x' : '') . $row->getFormattedSizeAttribute() . ' ' . $items['size_unit'][$row->size_unit];
            $items['name']['size'][] = $name;
            $items['size'][$name] = MasterProductSize::select('master_product_size.master_product_size_id', 'master_product_size.master_product_type_id', 'master_product_size.master_product_main_id', 'master_product_size.name as size', DB::raw('\'' . $name . '\' as size_name'), 'master_product_size.weight', 'master_product_size.size_unit', 'master_product_main.formula')
                ->join('master_product_main', 'master_product_main.master_product_main_id', '=', 'master_product_size.master_product_main_id')
                ->where('master_product_main.status', '01')->where('master_product_main.status_special', '01')->where('master_product_size.status', '01')
                ->where('master_product_size.name', $row->name)->where('master_product_size.size_unit', $row->size_unit)->get();
        }

        return response()->json($items,200);
    }

    public function ItemsSizeNonType (Request $request)
    {
        $size_group_name = MasterProductSize::join('master_product_main', 'master_product_main.master_product_main_id', '=', 'master_product_size.master_product_main_id')
            ->select('master_product_size.name', 'master_product_size.size_unit', 'master_product_main.formula')->where('master_product_size.status', '01')->where('master_product_size.name', '!=', '')
            ->where('master_product_main.status_special', '01')
            ->where('master_product_size.master_product_main_id', $request->curr_id)
            ->groupBy('master_product_size.name', 'master_product_size.size_unit', 'master_product_main.formula')->get();
        $items = [];
        $items['size_unit'] = config('sizeunit');
        foreach ($size_group_name as $row) {
            $name = ($row->formula == '01' ? '0.35x' : '') . $row->getFormattedSizeAttribute() . ' ' . $items['size_unit'][$row->size_unit];
            $items['name']['size'][] = $name;
            $items['size'][$name] = MasterProductSize::select('master_product_size.master_product_size_id', 'master_product_size.master_product_type_id', 'master_product_size.master_product_main_id', 'master_product_size.name as size', DB::raw('\'' . $name . '\' as size_name'), 'master_product_size.weight', 'master_product_size.size_unit', 'master_product_main.formula')
                ->join('master_product_main', 'master_product_main.master_product_main_id', '=', 'master_product_size.master_product_main_id')
                ->where('master_product_main.status', '01')->where('master_product_main.status_special', '01')->where('master_product_size.status', '01')
                ->where('master_product_size.name', $row->name)->where('master_product_size.size_unit', $row->size_unit)->get();
        }

        return response()->json($items,200);
    }

    public function edit($id)
    {
        $masterVat = MasterVat::where('vat', '>', 0)->orderby('vat')->get();
        $defaultVat = MasterVat::where('is_default', '01')->first();
        $ProductMain = MasterProductMain::where('status', '01')->orderby('name')->get();
        $products = [];
        $items = [];
        $carts = [];
        $carts_price = [];
        $qtys = 0;
        $total_weight = 0;

        foreach ($ProductMain as $row) {


            $row->spec = MasterProductType::select(DB::raw('IF(master_product_size.master_product_type_id,master_product_type.name,\'\') as type_name'), 'master_product_size.name as size_name', 'master_product_size.master_product_size_id', 'master_product_size.master_product_type_id', 'master_product_size.master_product_main_id', 'master_product_size.size_unit')

                ->leftJoin('master_product_size', function ($join) {
                    $join->on('master_product_size.master_product_type_id', '=', 'master_product_type.master_product_type_id')
                        ->orWhereNull('master_product_size.master_product_type_id');
                })
                ->where('master_product_type.status', '01')->where('master_product_size.status', '01')->where('master_product_size.master_product_main_id', $row->master_product_main_id)

                ->orderby('master_product_type.name')
                ->orderby('master_product_size.name')
                ->get();

            $products[] = $row;

        }
        $main_group_name = MasterProductMain::select('name')->where('status', '01')->where('status_special', '01')->where('name', '!=', '')->where('status_special', '01')->groupBy('name')->get();
        $type_group_name = MasterProductType::join('master_product_main', 'master_product_main.master_product_main_id', '=', 'master_product_type.master_product_main_id')
            ->select('master_product_type.name')->where('master_product_type.status', '01')->where('master_product_type.name', '!=', '')
            ->where('master_product_main.status_special', '01')->groupBy('master_product_type.name')->get();
        $size_group_name = MasterProductSize::join('master_product_main', 'master_product_main.master_product_main_id', '=', 'master_product_size.master_product_main_id')
            ->select('master_product_size.name', 'master_product_size.size_unit', 'master_product_main.formula')->where('master_product_size.status', '01')->where('master_product_size.name', '!=', '')
            ->where('master_product_main.status_special', '01')
            ->groupBy('master_product_size.name', 'master_product_size.size_unit', 'master_product_main.formula')->get();
        // $type_group_name_null = MasterProductType::select('master_product_type_id','master_product_main_id')->where('status','01')->where('name','')->get();
        // $items['type_null']=$type_group_name_null;
        $items['size_unit'] = config('sizeunit');
        foreach ($main_group_name as $row) {
            $items['name']['main'][] = $row->name;
            $items['main'][$row->name] = MasterProductMain::select('master_product_main_id', 'status_detail', 'name as product_name')->where('name', $row->name)->where('status', '01')->where('status_special', '01')->get();
        }


        foreach ($type_group_name as $row) {
            $items['name']['type'][] = $row->name;
            $items['type'][$row->name] = MasterProductType::select('master_product_type_id', 'master_product_main_id', 'name as type_name')->where('status', '01')->where('name', $row->name)->get();
        }


        foreach ($size_group_name as $row) {
            $name = ($row->formula == '01' ? '0.35x' : '') . $row->getFormattedSizeAttribute() . ' ' . $items['size_unit'][$row->size_unit];
            $items['name']['size'][] = $name;
            $items['size'][$name] = MasterProductSize::select('master_product_size.master_product_size_id', 'master_product_size.master_product_type_id', 'master_product_size.master_product_main_id', 'master_product_size.name as size', DB::raw('\'' . $name . '\' as size_name'), 'master_product_size.weight', 'master_product_size.size_unit', 'master_product_main.formula')
                ->join('master_product_main', 'master_product_main.master_product_main_id', '=', 'master_product_size.master_product_main_id')
                ->where('master_product_main.status', '01')->where('master_product_main.status_special', '01')->where('master_product_size.status', '01')
                ->where('master_product_size.name', $row->name)->where('master_product_size.size_unit', $row->size_unit)->get();
        }
        $items['count_unit'] = config('countunit');
        // print_r($items);
        // return response()->json($items);
        // exit;
        // $items['type']
        if(!isset($items['type'])){
            $items['type']=[];
        }
        $data['Order'] = $this->getOrder($id);
        $OrderItem = OrderItem::select(
            'order_item.order_item_id',
            'order_item.order_id',
            'order_item.price',
            'order_item.total_price',
            'order_item.qty',
            'order_item.remark',
            'order_item.total_size',
            'order_item.size_unit',
            'product.name as product_name',
            'product.master_product_main_id as master_product_main_id',
            'product.unit_count as unit_count',
            'product_type.master_product_type_id as master_product_type_id',
            'product_type.name as product_type_name',
            'product_size.master_product_size_id as master_product_size_id',
            'product_size.name as product_size_name',
            'order_item.weight AS product_weight',
            'product.formula',
            'delivery.delivery_type',
            'order_item.addition',
            'delivery.status',
            'orders.vat_no',
            DB::raw('delivery_item.qty AS delivery_qty '),
            // DB::raw('sum( delivery_item.qty ) AS sum_order_delivery_item ')
        )
            ->leftjoin('master_product_main as product', 'product.master_product_main_id', '=', 'order_item.master_product_main_id')
            ->leftjoin('master_product_type as product_type', 'product_type.master_product_type_id', '=', 'order_item.master_product_type_id')
            ->leftjoin('master_product_size as product_size', 'product_size.master_product_size_id', '=', 'order_item.master_product_size_id')
            ->leftjoin('order_delivery_item as delivery_item', 'delivery_item.order_item_id', '=', 'order_item.order_item_id')
            ->leftjoin('order_delivery as delivery', 'delivery.order_delivery_id', '=', 'delivery_item.order_delivery_id')
            ->leftjoin('orders as orders', 'orders.order_id', '=', 'order_item.order_id')
            ->where('order_item.order_id', $id)
            ->get();
            foreach ($OrderItem as $row) {
                if(!isset($sum_order_delivery_item[$row->order_item_id])) $sum_order_delivery_item[$row->order_item_id] = 0;
                if(!isset($data['order_item'][$row->order_item_id]['return'])) $data['order_item'][$row->order_item_id]['return'] = 0;
                if(!isset($item_price[$row->order_item_id])) $item_price[$row->order_item_id] = 0;
                if(!isset($qty[$row->order_item_id])) $qty[$row->order_item_id] = 0;
                if(!isset($grand_total)) $grand_total = 0;

                if($row->delivery_type != '01' && $row->delivery_type != '02') $qty[$row->order_item_id]  = $row->qty;
                $weight_data = Tools::convertdWeight($row->product_weight);
                $row->total_size_text = ($row->formula == '01') ? round($row->total_size, 4) . ' ตร.ม.' : round($row->total_size, 2) . ' ' . config('sizeunit')[$row->size_unit];
                $row->total_size_number = ($row->formula == '01') ? round($row->total_size, 4)  : round($row->total_size, 2);
                $row->product_weight_text = $weight_data['number'] . ' ' . $weight_data['unit'];
                $row->product_size_name_text = ($row->formula == '01') ? '0.35x' . sprintf('%g', $row->product_size_name) . ' ' . config('sizeunit')[$row->size_unit] : sprintf('%g', $row->product_size_name) . ' ' . config('sizeunit')[$row->size_unit];
                if($row->delivery_type == '01'){
                    $data['order_item'][$row->order_item_id]['return'] += $row->delivery_qty;
                    $qty[$row->order_item_id]  -= $row->delivery_qty;
                    // $sum_order_delivery_item[$row->order_item_id] -= $row->delivery_qty;
                    $item_price[$row->order_item_id] -= ($row->total_price / $row->qty) * $row->delivery_qty;
                    $grand_total -= ($row->total_price / $row->qty) * $row->delivery_qty;
                    $sumpricereturmrow = (($row->addition+$row->price) * $row->total_size_number ) * $row->delivery_qty;
                    $sumvatreturmrow = ($sumpricereturmrow / 100) * $row->vat_no;
                    $sumpricevatreturmrow =  $sumpricereturmrow + $sumvatreturmrow;
                    // $sumcashreturn = $sumcashreturn + $sumpricevatreturmrow;
                }else if($row->delivery_type == '02' && $row->status != '05'){
                //    $sum_order_delivery_item[$row->order_item_id] -= $row->delivery_qty;
                //    $item_price[$row->order_item_id] -= ($row->total_price / $row->qty) * $row->delivery_qty;
                }else if($row->delivery_type != '02' && $row->status == '05'){
                    $sum_order_delivery_item[$row->order_item_id] += $row->delivery_qty;
                    $item_price[$row->order_item_id] += ($row->total_price / $row->qty) * $qty[$row->order_item_id];
                    $grand_total += $row->total_price;
                    $qty[$row->order_item_id]  = $row->qty;
                } else if($row->delivery_type != '02'){
                  //  $sum_order_delivery_item[$row->order_item_id] += $row->delivery_qty;
                    $item_price[$row->order_item_id] += ($row->total_price / $row->qty) * $qty[$row->order_item_id];
                    $grand_total += $row->total_price;
                    $qty[$row->order_item_id]  = $row->qty;
                }

                if($sum_order_delivery_item[$row->order_item_id]<0) $sum_order_delivery_item[$row->order_item_id] *=  -1;
                $data['OrderItem'][$row->order_item_id] = $row;
                $data['order_item'][$row->order_item_id]['sum_order_delivery_item'] = $sum_order_delivery_item[$row->order_item_id];
                $data['order_item'][$row->order_item_id]['qty'] = $qty[$row->order_item_id];
                $data['order_item'][$row->order_item_id]['total_price'] = $item_price[$row->order_item_id];

                $carts[$row->order_item_id]['main'] = ['master_product_main_id'=>$row->master_product_main_id,'product_name'=>$row->product_name,'status_detail'=>'00'];
                $carts[$row->order_item_id]['price'] = str_replace(",","",number_format($row->price));
                $carts[$row->order_item_id]['price_add'] = intval(str_replace(",","",number_format($row->addition)));
                $carts[$row->order_item_id]['qty'] = str_replace(",","",number_format($row->qty));
                $carts[$row->order_item_id]['remark'] = $row->remark;
                $carts[$row->order_item_id]['size'] = ['formula'=>$row->formula,'master_product_main_id'=>$row->master_product_main_id,'master_product_type_id'=>$row->master_product_type_id,'master_product_size_id'=>$row->master_product_size_id,'size'=>$row->total_size,'size_name'=>$row->total_size_text,'size_unit'=>$row->size_unit,'weight'=>$row->product_weight];
                $carts[$row->order_item_id]['size_unit'] = $row->size_unit;
                $carts[$row->order_item_id]['total_price'] = $row->total_price;
                $carts[$row->order_item_id]['total_size'] =  str_replace(",","",number_format($row->total_size,2));
                if($row->product_type_name)
                {
                    $carts[$row->order_item_id]['type'] =  [
                        'master_product_main_id'=>$row->master_product_main_id,
                        'master_product_type_id'=>$row->master_product_type_id,
                        'type_name'=>$row->product_type_name,
                    ];
                }else{
                    $carts[$row->order_item_id]['type'] = null;
                }
                $carts[$row->order_item_id]['weight_total'] = intval(str_replace(",","",number_format($row->product_weight)));

                $qtys = $qtys+$row->qty;
                $total_weight = $total_weight+intval(str_replace(",","",number_format($row->product_weight)));

            }

            $data['order_item']['price_all'] = $grand_total;
            $data['order_item']['vat'] = $grand_total / 100 * $data['Order']->vat_no;
            $data['order_item']['grand_total']  = $grand_total +  $data['Order']['vat'];
            $data['OrderItem'] = array_values($data['OrderItem']);
            $merchant = MasterMerchant::select('master_merchant_id', 'name_merchant', 'name_department', 'tax_number', 'phone_number','phone_number2','phone_number3','phone_number4','fax', 'address', 'remark')->where('master_merchant_id',$data['Order']->master_merchant_id)->first();

            $data['merchant'] = $merchant;
            $carts = array_values($carts);
            $carts_price = [
                'grand_total' => $grand_total +  $data['Order']['vat'],
                'qty_all' => $qtys,
                'total_item' => count($data['OrderItem']),
                'total_price' => $grand_total,
                'total_weight' => $total_weight,
                'vat' => $grand_total / 100 * $data['Order']->vat_no,
            ];
            // $carts = json_encode($carts);
            // dd($data['Order']);
            // master_merchant_id
        return view('pages.orders.edit', compact('masterVat', 'defaultVat', 'products', 'items','data','OrderItem','carts','carts_price'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'master_merchant_id' => 'required|numeric',
            'carts' => 'required|array',
            'total_price' => 'required|numeric',
            'vat' => 'required|numeric',
            'grand_total' => 'required|numeric',
            'qty_all' => 'required|integer',
            'order_vat_no' => 'required|numeric',
            'total_price' => 'required|numeric',
            'order_status_vat' => 'required|size:2',
            'order_type' => 'required|size:2',
        ]);
        if (!$validator->fails()) {
            //update Order
            $update = Order::where('order_id',$request->order_id)->first();
            if($update->staus_send == 00)
            {
                $update->merchant_id = $request->master_merchant_id;
                $update->staus_send = '00';
                $update->status_vat = $request->order_status_vat;
                $update->order_type = $request->order_type;
                $update->price_all = $request->total_price;
                $update->grand_total = $request->grand_total;
                $update->vat = $request->vat;
                $update->weight = $request->total_weight;
                $update->qty_all = $request->qty_all;
                $update->vat_no = $request->order_vat_no;
                $update->created_by = $request->user()->id;
                $update->department_name = $request->department_name;
                $update->datemarksend = date('Y-m-d',strtotime($request->datemarksend));
                $update->noteorder = $request->noteorder;
                $update->save();

                //delete Order Item
                OrderItem::where('order_id',$update->order_id)->delete();
                //add Order Item
                $order_item = [];
                foreach ($request->carts as $key => $item) {
                    $order_item[] = [
                        'order_id' => $update->order_id,
                        'master_product_main_id' => $item['size']['master_product_main_id'],
                        'master_product_type_id' => $item['size']['master_product_type_id'],
                        'master_product_size_id' => $item['size']['master_product_size_id'],
                        'price' => $item['price'],
                        'addition' => $item['price_add'],
                        'qty' => $item['qty'],
                        'total_price' => $item['total_price'],
                        'total_size' => $item['total_size'],
                        'size_unit' => $item['size_unit'],
                        'weight' => $item['weight_total'],
                        'remark' => $item['remark'],
                    ];
                    if ($item['price'] > 0) {
                        $check_price = MasterMerchantPrice::where('master_merchant_id', $request->master_merchant_id)
                            ->where('master_product_main_id', $item['size']['master_product_main_id'])
                            ->where('master_product_type_id', $item['size']['master_product_type_id'])
                            ->where('master_product_size_id', $item['size']['master_product_size_id'])->count();
                        if ($check_price == 0) {
                            MasterMerchantPrice::insert([
                                'master_merchant_id' => $request->master_merchant_id,
                                'master_product_main_id' => $item['size']['master_product_main_id'],
                                'master_product_type_id' => $item['size']['master_product_type_id'],
                                'master_product_size_id' => $item['size']['master_product_size_id'],
                                'price' => $item['price'],
                                'created_by' => $request->user()->id
                            ]);
                        }
                    }
                }
                if (!empty($order_item)) {
                    DB::table('order_item')->insert($order_item);
                }
                //delete OrderDelivery
                    OrderDelivery::where('order_id',$update->order_id)->delete();

                //add OrderDelivery
                $data = [
                    'order_id' => $update->order_id,
                    'product_return_claim_id' => null,
                    'remark' => null,
                    'type' => '00',
                    'status' => '00',
                    'delivery_type' => '00',
                    'qty_all' => $request->qty_all,
                    'price_all' => $request->total_price,
                    'vat' => $request->vat,
                    'grand_total' => $request->grand_total,
                    'weight' => $request->total_weight
                ];

                $order_delivery_id =  DeliveryController::addOrderDelivery($data, []);
                return response()->json(['success' => true, 'order_id' => $update->order_id, 'order_delivery_id' => $order_delivery_id]);
            }else{
                return response()->json(['success' => false, 'errors' => ['0'=>'ไม่สามารถแก้ไขได้ เนื่องจากมีการจัดส่งแล้ว']]);
            }
        } else {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }


    }
}
