<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TruckSchedule;
use DB;
use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Support\Facades\URL;

class CalendarController extends Controller
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
        return view('pages.delivery.calendar');
    }

    public function getCalendar(Request $request)
    {
        $url = env('APP_URL');
        $data = TruckSchedule::select(
            DB::raw("CONCAT('เลขที่ใบสั่งซื้อ ',orders.order_number,'/',order_delivery.order_delivery_number) AS title"),
            DB::raw("CONCAT(DATE_FORMAT(truck_schedule.date_schedule, '%Y-%m-%d'),' ',master_round.round_time) AS start"),
            DB::raw("DATE_FORMAT(truck_schedule.date_schedule, '%Y-%m-%d') AS calendar_start"),
            DB::raw(
                "case
                    when order_delivery.status = '01' then CONCAT('$url','delivery\/list\/delivery\/',orders.order_id,'\/confirm\/',order_delivery.order_delivery_id)
                    when order_delivery.status = '03' then CONCAT('$url','delivery\/list\/delivery\/',orders.order_id,'\/confirm\/',order_delivery.order_delivery_id)
                    else ''
                    end AS url"
            ),
            DB::raw("case
                    when order_delivery.status = '01' then '#FF871E'
                    when order_delivery.status = '02' then '#FFC627'
                    when order_delivery.status = '03' then '#EE6C98'
                    when order_delivery.status = '04' then '#EE6C98'
                    when order_delivery.status = '05' then '#27C671'
                    else '#495057'
                    end AS color"),
            'master_merchant.name_merchant as namestore',
            'master_truck.license_plate as licenseplate',
            'master_round.round_time as roundtime',
            'master_truck_type.type as trucktype',
            'orders.department_name as name_department',

        )

            ->join('order_delivery', 'order_delivery.order_delivery_id', '=', 'truck_schedule.order_delivery_id')
            ->join('orders', 'orders.order_id', '=', 'order_delivery.order_id')
            ->join('master_round', 'master_round.master_round_id', '=', 'truck_schedule.master_round_id')
            ->join('master_merchant', 'master_merchant.master_merchant_id', '=', 'orders.merchant_id')
            ->leftJoin('master_truck', 'master_truck.master_truck_id', '=', 'truck_schedule.master_truck_id')
            ->leftJoin('master_truck_type', 'master_truck_type.master_truck_type_id', '=', 'master_truck.master_truck_type_id')
            ->where('orders.staus_send', '!=', '04')
            ->whereDate('truck_schedule.date_schedule', '>=', Carbon::parse($request->start)->format('Y-m-d'))
            ->whereDate('truck_schedule.date_schedule', '<=', Carbon::parse($request->end)->format('Y-m-d'))
            ->get();

        // dd($ordermark);
        // $data = [
        //     [
        //         "title" => "All Day Event",
        //         "description" => 'description for All Day Event',
        //         "start" => "2020-06-01",
        //         "color" => "#27C671"
        //     ],
        //     [
        //         "title" => "Long Event",
        //         "start" => "2020-02-03 00:00:00",
        //         "description" => 'description for All Day Event',
        //         "end" => "2020-06-04 23:59:59",
        //         "color" => "#0095DD"
        //     ],
        //     [
        //         "groupId" => 999,
        //         "title" => "Repeating Event",
        //         "description" => 'description for All Day Event',
        //         "start" => "2020-06-07",
        //         "color" => "#FFC627"
        //     ],
        //     [
        //         "groupId" => 999,
        //         "title" => "Repeating Event",
        //         "description" => 'description for All Day Event',
        //         "start" => "2020-06-09",
        //         "color" => "#D01717"
        //     ],
        //     [
        //         "title" => "Click for Google",
        //         "description" => 'description for All Day Event',
        //         "url" => "http://google.com/",
        //         "start" => "2020-06-11"
        //     ]
       // ];
        $result = [];
        foreach($data as $key => $value){
            // if($value->url && (int)(str_replace(':','',$value->roundtime)) > 12){
            //     continue;
            // }
            $result[$key]['title'] = $value->namestore.'/'.$value->name_department;
            $result[$key]['title_popup'] = $value->title;
            $result[$key]['description'] = $value->name_department;
            $result[$key]['namestore'] = $value->namestore;
            $result[$key]['url'] = $value->url;
            $result[$key]['start'] = $value->calendar_start;
            $result[$key]['roundtime'] = $value->roundtime;
            $result[$key]['trucktype'] = $value->trucktype;
            $result[$key]['licenseplate'] = $value->licenseplate;
            $result[$key]['color'] = $value->color;
            //roundtime
        }


        $result = array_values($result);
        $dataorder = Order::select('orders.*','master_merchant.name_merchant')->where('staus_send','00')->join('master_merchant', 'master_merchant.master_merchant_id', '=', 'orders.merchant_id')->get();
        if(!empty($dataorder))
        {
            $ordermark = [];
            foreach($dataorder as $key => $order)
            {
                $ordermark['title'] = $order->name_merchant.'/'.$order->department_name;
                $ordermark['title_popup'] = 'เลขที่ใบสั่งซื้อ'.$order->order_number;
                $ordermark['description'] = $order->department_name;
                $ordermark['namestore'] = $order->name_merchant;
                $ordermark['url'] = url('/delivery/list/delivery').'/'.$order->order_id;
                $ordermark['start'] = date('Y-m-d',strtotime($order->datemarksend));
                $ordermark['roundtime'] = 'ยังไม่ได้กำหนด';
                $ordermark['trucktype'] = '';
                $ordermark['licenseplate'] = '';
                $ordermark['color'] = '#495057';
                array_push($result,$ordermark);
            }
        }


        return response()->json($result);
    }
}
