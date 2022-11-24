<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use App\Models\MasterTruckType;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Validator;

class Car_informationController extends Controller
{
    /**
     * instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    // แสดงผลหน้าเมนูจัดการข้อมูลประเภทรถ
    public function index()
    {
        return view('pages.master_data.car_type_information.index');
    }

    // แสดงผลหน้าสร้างข้อมูลประเภทรถ
    public function create()
    {
        return view('pages.master_data.car_type_information.create');
    }

    // แสดงผลหน้าแก้ไขข้อมูลประเภทรถ
    public function update($id)
    {
        return view('pages.master_data.car_type_information.update', ['data' => MasterTruckType::find($id)]);
    }

    // ดึงข้อมูลประเภทรถ
    public function getData(Request $request)
    {
        $MasterTruckType = MasterTruckType::select('*');
        $MasterTruckType->where('type','not like','%มารับเอง%');
        if ($request->search) {
            $MasterTruckType->where('type', 'LIKE', $request->search . '%');
        }
        return Datatables::of($MasterTruckType)->make(true);
    }

    // บันทึกข้อมูลประเภทรถ
    public function insertData(Request $request)
    {
        if($request->type == 'มารับเอง'){
            return response()->json(['status' => false, 'message' => 'ไม่สามารถใช้ชื่อประเภทนี้ได้']);
        }
        $validator = Validator::make($request->all(), [
            'type'     => 'required|unique:master_truck_type,type,NULL,master_truck_type_id,deleted_date,NULL'
        ], [
            'type.required' => 'กรุณากรอกประเภทรถ',
            'type.unique' => 'ประเภทรถนี้ถูกใช้งานแล้ว',
        ]);

        // check required parameter
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }

        MasterTruckType::insert([
            'type' => $request->type,
            'created_by' => Auth::id(),
            'created_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
        ]);
        return response()->json(['status' => true, 'message' => 'บันทึกข้อมูลสำเร็จ']);
    }

    // เปลี่ยนแปลงข้อมูลประเภทรถ
    public function updateData(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'type'     => 'required|unique:master_truck_type,type,' . $id . ',master_truck_type_id,deleted_date,NULL'
        ], [
            'type.required' => 'กรุณากรอกประเภทรถ',
            'type.unique' => 'ประเภทรถนี้ถูกใช้งานแล้ว',
        ]);

        // check required parameter
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }

        MasterTruckType::where('master_truck_type_id', $id)->update([
            'type' => $request->type,
            'updated_by' => Auth::id(),
            'updated_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
        ]);

        return response()->json(['status' => true, 'message' => 'บันทึกข้อมูลสำเร็จ']);
    }

    // ลบข้อมูลประเภทรถ
    public function deleteData($id)
    {
        $usedData = MasterTruckType::find($id)->usedData()->count();
        if ($usedData > 0) {
            return response()->json(['status' => false, 'message' => 'ประเภทรถนี้ถูกใช้งานอยู่ ไม่สามารถลบข้อมูลได้']);
        }

        MasterTruckType::find($id)->update([
            'deleted_by' => Auth::id()
        ]);
        MasterTruckType::find($id)->delete();
        return response()->json(['status' => true, 'message' => 'ลบข้อมูลสำเร็จ']);
    }
}
