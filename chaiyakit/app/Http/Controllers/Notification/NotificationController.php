<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use App\Models\MasterTruck;
use App\Models\TruckSchedule;
use App\Models\Order;
use App\Models\MasterTruckType;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Validator;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{
    /**
     * instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function update($id)
    {
        return view('pages.master_data.delivery_car.update', ['master_truck_type' => MasterTruckType::all(), 'data' => MasterTruck::find($id)]);
    }
    public function Notification()
    {
        $id_noti = Cache::get('truck_schedule');
        $collect = collect($id_noti);
        $id_noti_license = Cache::get('master_truck');
        $collec_license = collect($id_noti_license);
        $id_noti_order = Cache::get('master_order');
        $collec_order = collect($id_noti_order);

        $TruckSchedule = Cache::get('TruckSchedule');
        if (!Cache::has('TruckSchedule')) {
            $TruckSchedule = Cache::remember('TruckSchedule', 60, function () {
                return TruckSchedule::select('truck_schedule.*', 'order_id.order_id')
                    ->join('order_delivery', 'order_delivery.order_delivery_id', '=', 'truck_schedule.order_delivery_id')
                    ->leftjoin('order_delivery as order_id', 'order_id.order_delivery_id', 'order_delivery.order_id')
                    ->where(function ($query) {
                        $query->orwhere('truck_schedule.date_schedule', Carbon::now('Asia/Bangkok')->addDays(1)->format('Y-m-d'));
                        $query->Where('order_delivery.status', '=', '01');
                    })->orderby('created_date','desc')->get();
            });
        }

        foreach ($TruckSchedule as $key => $value) {
            $value->date_schedule = formatDateThatNoTime($value->date_schedule);
            $chk = $collect->where('truck_schedule_id', $value->truck_schedule_id)->first();
            if ($chk) {
                $value->status_read = true;
            } else {
                $value->status_read = false;
            }
        }

        $TruckScheduleafter = Cache::get('TruckScheduleafter');
        if (!Cache::has('TruckScheduleafter')) {
            $TruckScheduleafter = Cache::remember('TruckScheduleafter', 60, function () {
                return TruckSchedule::select('truck_schedule.*', 'order_id.order_id')
                    ->join('order_delivery', 'order_delivery.order_delivery_id', '=', 'truck_schedule.order_delivery_id')
                    ->leftjoin('order_delivery as order_id', 'order_id.order_delivery_id', 'order_delivery.order_id')
                    ->where(function ($query) {
                        $query->Where('truck_schedule.date_schedule', Carbon::now('Asia/Bangkok')->subDay(1)->format('Y-m-d'));
                        $query->Where('order_delivery.status', '=', '01');
                    })->orderby('created_date','desc')->get();
            });
        }

        foreach ($TruckScheduleafter as $key => $value) {
            $value->date_schedule = formatDateThatNoTime($value->date_schedule);
            $tsa = $collect->where('truck_schedule_id', $value->truck_schedule_id)->first();
            if ($tsa) {
                $value->status_read = true;
            } else {
                $value->status_read = false;
            }
        }

        $MasterTruck = Cache::get('MasterTruck');
        if (!Cache::has('MasterTruck')) {
            $MasterTruck = Cache::remember('MasterTruck', 60, function () {
                return  MasterTruck::select('master_truck.*', 'type.type')
                    ->leftjoin('master_truck_type as type', 'type.master_truck_type_id', 'master_truck.master_truck_type_id')
                    ->where(function ($query) {
                        $query->orWhereDate('master_truck.date_vat_expire', Carbon::now('Asia/Bangkok')->addDays(30)->format('Y-m-d'));
                    })->orderby('created_date','desc')->get();
            });
        }

        foreach ($MasterTruck as $key => $value) {
            $value->date_schedule = formatDateThatNoTime($value->date_vat_expire);
            $mst = $collec_license->where('master_truck_id', $value->master_truck_id)->first();
            if ($mst) {
                $value->status_read = true;
            } else {
                $value->status_read = false;
            }
        }

        $MasterOrder = Cache::get('MasterOrder');
        if (!Cache::has('MasterOrder')) {
            $MasterOrder = Cache::remember('MasterOrder', 60, function () {
                return  Order::select('orders.*')
                    ->where(function ($query) {
                        $query->orWhereDate('orders.datemarksend', Carbon::now('Asia/Bangkok')->addDays(1)->format('Y-m-d'));
                    })->orderby('created_date','desc')->get();
            });
        }
        foreach ($MasterOrder as $key => $value) {
            $value->datemarksend = formatDateThatNoTime($value->datemarksend);
            $or = $collec_order->where('order_id', $value->order_id)->first();
            if ($or) {
                $value->status_read = true;
            } else {
                $value->status_read = false;
            }
        }

        return response()->json(['status' => true,  'Data1' => $TruckSchedule, 'Data2' => $TruckScheduleafter, 'Data3' => $MasterTruck,'Data4' => $MasterOrder]);
    }

    public function NotificationCache(Request $request, $order_id, $order_delivery_id, $truck_schedule_id)
    {
        //Cache::flush();
        $id_noti = Cache::get('truck_schedule');
        if ($id_noti) {
            $collect = collect($id_noti);
            $collect = $collect->push(['truck_schedule_id' => $truck_schedule_id]);
            Cache::put('truck_schedule', $collect->unique('truck_schedule_id')->all(), Carbon::now()->addSeconds(3600));
        } else {
            Cache::put('truck_schedule', array(array('truck_schedule_id' => $truck_schedule_id)), Carbon::now()->addDays(1));
        }
        return redirect()->route('delivery.list.delivery.confirm', [$order_id, $order_delivery_id]);
    }

    public function NotificationCacheMastertruck(Request $request, $master_truck_id)
    {
        $id_noti_license = Cache::get('master_truck');
        if ($id_noti_license) {
            $collect = collect($id_noti_license);
            $collect = $collect->push(['master_truck_id' => $master_truck_id]);
            Cache::put('master_truck', $collect->unique('master_truck_id')->all(), Carbon::now()->addSeconds(3600));
        } else {
            Cache::put('master_truck', array(array('master_truck_id' => $master_truck_id)), Carbon::now()->addDays(1));
        }
        return redirect()->route('delivery_car.update', [$master_truck_id]);
    }

    public function NotificationCacheOrder(Request $request, $order_id)
    {
        //Cache::flush();
        $id_noti_order = Cache::get('master_order');
        if ($id_noti_order) {
            $collect = collect($id_noti_order);
            $collect = $collect->push(['order_id' => $order_id]);
            Cache::put('master_order', $collect->unique('order_id')->all(), Carbon::now()->addSeconds(3600));
        } else {
            Cache::put('master_order', array(array('order_id' => $order_id)), Carbon::now()->addDays(1));
        }
        return redirect()->route('delivery.list.delivery', [$order_id]);
    }


    public function index()
    {
        //return view('pages.master_data.delivery_car.index', ['master_truck_type' => MasterTruckType::all()]);
    }

    public function getData(Request $request)
    {
    }
}
