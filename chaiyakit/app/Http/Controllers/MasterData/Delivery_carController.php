<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use App\Models\MasterTruck;
use App\Models\MasterTruckType;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Validator;

class Delivery_carController extends Controller
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
       $master_truck_type =  MasterTruckType::where('type','not like','%มารับเอง%')->orderby('master_truck_type_id','asc')->get();
        return view('pages.master_data.delivery_car.index', ['master_truck_type' => $master_truck_type]);
    }

    public function getData(Request $request)
    {
        $MasterTruck = MasterTruck::select('master_truck.*', 'type.type')->leftjoin('master_truck_type as type', 'type.master_truck_type_id', 'master_truck.master_truck_type_id');
        if ($request->search) {
            $MasterTruck->where(function ($query) use ($request) {
                $query->where('type.type', 'LIKE', $request->search . '%');
                $query->orWhere('master_truck.license_plate', 'LIKE', '%' . $request->search . '%');
                $query->orWhere('master_truck.weight', 'LIKE', '%' . $request->search . '%');
            });
        }

        if ($request->search_type) {
            $MasterTruck->where('master_truck.master_truck_type_id', $request->search_type);
        }
        $MasterTruck->where('type','not like','%มารับเอง%');
        return Datatables::of($MasterTruck->get())->make(true);
    }

    public function create()
    {
        $mastertruck = MasterTruckType::where('type','not like','%มารับเอง%')->orderby('master_truck_type_id','asc')->get();
        return view('pages.master_data.delivery_car.create', ['master_truck_type' => $mastertruck]);
    }

    public function update($id)
    {
        return view('pages.master_data.delivery_car.update', ['master_truck_type' => MasterTruckType::all(), 'data' => MasterTruck::find($id)]);
    }

    // บันทึกข้อมูลรถจัดส่งสินค้า
    public function insertData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'master_truck_type_id' => 'required',
            'weight' => 'required',
            'date_vat_expire' => 'required',
            'license_plate'     => 'required|unique:master_truck,license_plate,NULL,master_truck_id,deleted_date,NULL'
        ], [
            'master_truck_type_id.required' => 'กรุณากรอกประเภทรถ',
            'weight.required' => 'กรุณากรอกบรรทุกน้ำหนัก (ตัน)',
            'date_vat_expire.required' => 'กรุณากรอกวันที่หมดอายุภาษีทะเบียนรถ',
            'license_plate.required' => 'กรุณากรอกป้ายทะเบียน',
            'license_plate.unique' => 'ป้ายทะเบียนนี้ถูกใช้งานแล้ว',
        ]);

        // check required parameter
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }

        MasterTruck::insert([
            'master_truck_type_id' => $request->master_truck_type_id,
            'weight' => $request->weight,
            'date_vat_expire' => Carbon::createFromFormat('d/m/Y', $request->date_vat_expire)->format('Y-m-d H:i:s'),
            'license_plate' => $request->license_plate,
            'created_by' => Auth::id(),
            'created_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
        ]);
        return response()->json(['status' => true, 'message' => 'บันทึกข้อมูลสำเร็จ']);
    }

    // เปลี่ยนแปลงข้อมูลรถจัดส่งสินค้า
    public function updateData(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'master_truck_type_id'  => 'required',
            'weight'                => 'required',
            'date_vat_expire'       => 'required',
            'license_plate'         => 'required|unique:master_truck,license_plate,' . $id . ',master_truck_id,deleted_date,NULL'
        ], [
            'master_truck_type_id.required' => 'กรุณากรอกประเภทรถ',
            'weight.required' => 'กรุณากรอกบรรทุกน้ำหนัก (ตัน)',
            'date_vat_expire.required' => 'กรุณากรอกวันที่หมดอายุภาษีทะเบียนรถ',
            'license_plate.required' => 'กรุณากรอกป้ายทะเบียน',
            'license_plate.unique' => 'ป้ายทะเบียนนี้ถูกใช้งานแล้ว',
        ]);

        // check required parameter
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }

        MasterTruck::where('master_truck_id', $id)->update([
            'master_truck_type_id' => $request->master_truck_type_id,
            'weight' => $request->weight,
            'date_vat_expire' => Carbon::createFromFormat('d/m/Y', $request->date_vat_expire)->format('Y-m-d H:i:s'),
            'license_plate' => $request->license_plate,
            'updated_by' => Auth::id(),
            'updated_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
        ]);

        return response()->json(['status' => true, 'message' => 'บันทึกข้อมูลสำเร็จ']);
    }

    // ลบข้อมูลรถจัดส่งสินค้า
    public function deleteData($id)
    {
        MasterTruck::find($id)->update([
            'deleted_by' => Auth::id()
        ]);
        MasterTruck::find($id)->delete();
        return response()->json(['status' => true, 'message' => 'ลบข้อมูลสำเร็จ']);
    }
}
