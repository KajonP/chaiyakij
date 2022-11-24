<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use App\Models\MasterMerchant;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Validator;

class Customer_informationController extends Controller
{
    public function index()
    {
        return view('pages.master_data.customer_information.index');
    }
    public function update($id)
    {
        return view('pages.master_data.customer_information.update', ['data' => MasterMerchant::find($id)]);
    }
    public function create()
    {
        return view('pages.master_data.customer_information.create_account');
    }
    // ค้นหา
    public function getData(Request $request)
    {
        $MasterMerchant = MasterMerchant::select('*');
        if ($request->search) {
            $MasterMerchant->where('name_merchant', 'LIKE', $request->search . '%')
                           ->orWhere('phone_number', 'LIKE', $request->search . '%')
                           ->orWhere('tax_number', 'LIKE', $request->search . '%')
                           ->orWhere('name_department', 'LIKE', $request->search . '%');

        }
        return Datatables::of($MasterMerchant)->make(true);
    }
    // insert Master Merchant
    public function insertData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_merchant'     => 'required|unique:master_merchant,name_merchant,NULL,master_merchant_id,deleted_date,NULL',
            'tax_number'     => 'nullable|unique:master_merchant,tax_number'
        ], [
            'name_merchant.required' => 'กรุณากรอกชื่อร้านค้า',
            'name_merchant.unique' => 'ชื่อนี้ถูกใช้งานแล้ว',
            'tax_number.unique' => 'เลขประจำตัวภาษีอากรนี้ถูกใช้งานแล้ว',
        ]);

        // check required parameter
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }
        $check_name = MasterMerchant::where('name_merchant', $request->name_merchant)->count();
        if (!$check_name) {
            MasterMerchant::insert([
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
                'created_by' => Auth::id(),
                'created_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
            ]);
            return response()->json(['status' => true, 'message' => 'เพิ่มข้อมูลสำเร็จ']);
        } else {
            // case duplicate
            return response()->json(['status' => false, 'message' => 'ชื่อนี้มีการใช้งานแล้ว']);
        }
    }
    //update merchant_info
    public function updateData(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name_merchant'     => $id . ',master_merchant_id'
        ]);
        // check required parameter
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }

        MasterMerchant::where('master_merchant_id', $id)->update([
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
            'updated_by' => Auth::id(),
            'updated_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
        ]);

        return response()->json(['status' => true, 'message' => 'บันทึกข้อมูลสำเร็จ']);
    }
    // ลบข้อมูลลูกค้า
    public function deleteData($id)
    {
        $usedData = MasterMerchant::find($id)->usedData()->count();
        if ($usedData > 0) {
            return response()->json(['status' => false, 'message' => 'ร้านค้านี้มีออเดอร์อยู่ ไม่สามารถลบข้อมูลได้']);
        }

        MasterMerchant::find($id)->update([
            'deleted_by' => Auth::id()
        ]);
        MasterMerchant::find($id)->delete();
        return response()->json(['status' => true, 'message' => 'ลบข้อมูลสำเร็จ']);

    }



}
