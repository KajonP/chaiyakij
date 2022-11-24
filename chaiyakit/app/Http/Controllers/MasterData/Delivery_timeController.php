<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use App\Models\MasterRound;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Validator;
use Illuminate\Validation\Rule;

class Delivery_timeController extends Controller
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
        return view('pages.master_data.delivery_time.index');
    }

    // ดึงข้อมูลเวลาจัดส่ง
    public function getData(Request $request)
    {
        $MasterRound = MasterRound::select('*');
        if ($request->search) {
            $MasterRound->where('name', 'LIKE', $request->search . '%')
                        ->orWhere('round_time', 'LIKE', $request->search . '%');
        }
        return Datatables::of($MasterRound)->make(true);
    }

    // insert data
    public function insertData(Request $request)
    {
       $validator = Validator::make($request->all(), [
            'name'     => 'required|unique:master_round,name,NULL,master_round_id,deleted_date,NULL',
            'round_time_h'     => 'required',
            'round_time_m'     => 'required',
            'time'     => 'unique:master_round,round_time,NULL,master_round_id,deleted_date,NULL'
        ], [
            'name.required' => 'กรุณากรอกชื่อรอบจัดส่ง',
            'round_time_h.required' => 'กรุณากรอกกเวลาจัดส่ง',
            'round_time_m.required' => 'กรุณากรอกกเวลาจัดส่ง',
            'name.unique' => 'ชื่อรอบจัดส่งนี้ถูกใช้งานแล้ว',
            'time.unique' => 'เวลารอบจัดส่งนี้ถูกใช้งานแล้ว'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }
        MasterRound::insert([
            'name' => $request->name,
            'round_time' => $request->time,
            'created_by' => Auth::id(),
            'created_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),

            //แปลงค่าเป็น Fromtime
            //Carbon::createFromTime($request->round_time_h, $request->round_time_m, 'Asia/Bangkok')
        ]);
        return response()->json(['status' => true, 'message' => 'บันทึกข้อมูลสำเร็จ']);
    }

    // edit update data
    public function updateData(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|unique:master_round,name,' . $id . ',master_round_id',
            'round_time_h'     => 'required',
            'round_time_m'     => 'required',
            'time'     => 'unique:master_round,round_time,' . $id . ',master_round_id',
            
        ], [
            'name.required' => 'กรุณากรอกชื่อรอบจัดส่ง',
            'round_time_h.required' => 'กรุณากรอกกเวลาจัดส่ง',
            'round_time_m.required' => 'กรุณากรอกกเวลาจัดส่ง',
            'time.unique' => 'เวลารอบจัดส่งนี้ถูกใช้งานแล้ว',
            'name.unique' => 'ชื่อรอบจัดส่งนี้ถูกใช้งานแล้ว'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }

        MasterRound::where('master_round_id', $id)->update([
            'name' => $request->name,
            'round_time' => $request->time,
            'updated_by' => Auth::id(),
            'updated_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
        ]);
        return response()->json(['status' => true, 'message' => 'บันทึกข้อมูลสำเร็จ']);
    }

        // deleta data
    public function deleteData($id)
    {
        MasterRound::find($id)->update([
            'deleted_by' => Auth::id()
        ]);
        MasterRound::find($id)->delete();
        return response()->json(['status' => true, 'message' => 'ลบข้อมูลสำเร็จ']);
    }

    public function create()
    {
        return view('pages.master_data.delivery_time.create');
    }

    public function update($id)
    {
        return view('pages.master_data.delivery_time.update', ['data' => MasterRound::find($id)]);
    }
}
