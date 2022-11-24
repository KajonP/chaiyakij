<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\MasterProductMain;
use App\Models\MasterProductSize;
use App\Models\MasterProductType;
use App\Models\OrderItem;
use Validator;
use DataTables;

class ProductController extends Controller
{
    public function index()
    {
        return view('pages.master_data.product_info.index');
    }

    public function create()
    {
        return view('pages.master_data.product_info.create');
    }

    // ดึงข้อมูลสินค้า
    public function getData(Request $request)
    {
      // dd($request['product_name']);
      if ($request['product_name']) {
        $ProductMain = MasterProductMain::select('master_product_main.name AS product_name','master_product_type.name AS product_type','master_product_main.master_product_main_id AS id','master_product_main.master_product_main_id AS id_product_main')
                                          ->leftJoin('master_product_type','master_product_type.master_product_main_id','master_product_main.master_product_main_id')
                                          ->where('master_product_main.name',$request['product_name'])
                                          ->where('master_product_main.status','01')
                                          ->where('master_product_type.status','01')
                                          // ->orderBy('master_product_main.master_product_main_id',"DESC")
                                          ->get();
      }else {
        $ProductMain = MasterProductMain::select('master_product_main.name AS product_name','master_product_type.name AS product_type','master_product_main.master_product_main_id AS id','master_product_main.master_product_main_id AS id_product_main')
                                          ->leftJoin('master_product_type','master_product_type.master_product_main_id','master_product_main.master_product_main_id')
                                          ->where('master_product_main.status','01')
                                          ->where('master_product_type.status','01')
                                          // ->orderBy('master_product_main.master_product_main_id',"DESC")
                                          ->get();
      }

        // dd($ProductMain);
      $data = collect();
      foreach ($ProductMain->groupBy('id') as $key => $value) {
        $product_type = '';
        foreach ($value as $ke => $val) {
          if ($val->product_type != "") {
            $product_type.= $val->product_type." , " ;
          }
        }
        $data->push(array(
         'id' => $key,
         'product_name' => $value[0]['product_name'],
         'product_type' => $product_type,
         'id_product_main' => $value[0]['id_product_main'],
       ));
      }


      return Datatables::of($data->all())->make(true);
    }

    public function insertData(Request $request)
    {

        // $validator = Validator::make($request->all(), [
        //     'type'     => 'required|unique:master_truck_type,type'
        // ], [
        //     'type.required' => 'กรุณากรอกประเภทรถ',
        //     'type.unique' => 'ประเภทรถนี้ถูกใช้งานแล้ว',
        // ]);
        //
        // // check required parameter
        // if ($validator->fails()) {
        //     return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        // }

        $input =  $request['data'];
        // dd($input);

        $id_productMain = MasterProductMain::insertGetId([
            'name' => $input[0]['product_name'],
            'formula' => $input[0]['formula'],
            'status_special' => '01',
            'created_by' => Auth::id(),
            'created_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
        ]);

        foreach ($input as $key => $value) {
            if ($value['product_type_a'] == "") {
              MasterProductMain::where('master_product_main_id', $id_productMain)->update([
                  'status_detail' => "01",
              ]);
            }
            $product_type_a = $value['product_type_a'] == "" ? "":$value['product_type_a'];
            $id_ProductType = MasterProductType::insertGetId([
                'master_product_main_id' => $id_productMain,
                'name' => $product_type_a,
                'created_by' => Auth::id(),
                'created_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
            ]);
            foreach ($value['product_type_b'] as $key_type => $value_product_type) {
              $weight = str_replace(',','', $value_product_type[1]);
              $id_ProductSize = MasterProductSize::insertGetId([
                  'master_product_main_id' => $id_productMain,
                  'master_product_type_id' => $id_ProductType,
                  'name' => $value_product_type[0],
                  'weight' => $weight,
                  'size_unit' => $value_product_type[2],
                  'created_by' => Auth::id(),
                  'created_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
              ]);
            }
        }

        return response()->json(['status' => true, 'message' => 'บันทึกข้อมูลสำเร็จ']);

    }

    // แสดงผลหน้าแก้ไขข้อมูลสินค้า
    public function update($id)
    {
      return view('pages.master_data.product_info.update',['data' => $id]);
    }

    public function get_update($id)
    {
      $Main = MasterProductMain::where('master_product_main_id',$id)->get();
      $Type = MasterProductType::where('master_product_main_id',$id)->where('status','01')->get();
      //
      $data = [];

      $sizes  = ['product_type' => ''];
      foreach ($Main as $key_Main => $value_Main) {
        foreach ($Type as $key_Type => $value_Type) {

          $data_order_item = OrderItem::where('master_product_main_id',$value_Main['master_product_main_id'])->where('master_product_type_id',$value_Type['master_product_type_id'])->get();

          if (count($data_order_item)) { // เช็คว่ามีข้อมูลหรือเปล่า
            $order_item = true; //ลบไม่ได้ มีข้อมูล
          }else {
            $order_item = false; //ไม่มีลบได้
          }
          // dd($order_item);
          $data_product = array(
            'product_id' => $value_Main['master_product_main_id'],
            'product_name' => $value_Main['name'],
            'product_type_id' => $value_Type['master_product_type_id'],
            'product_type' => $value_Type['name'],
            'product_formula' => $value_Main['formula'],
            'order_item' => $order_item,
          );
          array_push($data, $data_product);

          $Size_type = [];
          $Size = MasterProductSize::where('master_product_main_id',$value_Main['master_product_main_id'])->where('master_product_type_id',$value_Type['master_product_type_id'])->where('status','01')->get();
          // dd($Size);
          foreach ($Size as $key_Size => $value_Size) {
            $data_order_item2 = OrderItem::where('master_product_main_id',$value_Size['master_product_main_id'])
            ->where('master_product_type_id',$value_Size['master_product_type_id'])
            ->where('master_product_size_id',$value_Size['master_product_size_id'])
            ->get();
            if (count($data_order_item2)) { // เช็คว่ามีข้อมูลหรือเปล่า
              $order_item2 = 1; //ลบไม่ได้ มีข้อมูล
            }else {
              $order_item2 = 0; //ไม่มีลบได้
            }

            $Size_data = [$value_Size['master_product_size_id'],$value_Size['name'], $value_Size['weight'],$order_item2,0,$value_Size['size_unit']];
            array_push($Size_type, $Size_data);
          }
          array_push($data[$key_Type], $Size_type);
        }
      }

      return $data ;
    }

    public function check_product_name($name)
    {
      $Main = MasterProductMain::where('name',$name)->get();
      return $Main ;
    }

    // update หน้าแก้ไขข้อมูลสินค้า
    public function updateData(Request $request, $id)
    {
      $input =  $request['data'];
      // dd($input);

     foreach ($input as $key => $value) {
       $ProductMain = MasterProductMain::where('master_product_main_id',$value['product_type_id'])->where('status','01')->update([
           'formula' => $value['product_formula'],
           'updated_by' => Auth::id(),
           'updated_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
       ]);
       $Type = MasterProductType::where('master_product_type_id',$value['product_type_id'])->where('status','01')->get();

       if (count($Type) > 0) {
         if ($value['del'] == 0) {
           // print_r('Update ');

           MasterProductType::where('master_product_type_id', $value['product_type_id'])->update([
               'name' => $Type[0]['name'],
               'updated_by' => Auth::id(),
               'updated_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
           ]);
         }else {
           // print_r('del ');

           MasterProductType::where('master_product_main_id', $value['product_id'])->where('master_product_type_id', $value['product_type_id'])->update([
               'deleted_by' => Auth::id(),
               'deleted_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
               'status' => '00',
           ]);

           MasterProductSize::where('master_product_main_id', $value['product_id'])->where('master_product_type_id', $value['product_type_id'])->update([
               'deleted_by' => Auth::id(),
               'deleted_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
               'status' => '00',
           ]);


         }
       }else {
         if ($value['del'] == 0) {
           // print_r('Insert ');
           $product_type_a = $value['product_type_a'] == "" ? "":$value['product_type_a'];
           $id_ProductType = MasterProductType::insertGetId([
               'master_product_main_id' => $value['product_id'],
               'name' => $product_type_a,
               'created_by' => Auth::id(),
               'created_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
           ]);
         }
       }


       foreach ($value['product_type_b'] as $key_product_type_b => $value_product_type_b) {
         if (!isset($value_product_type_b[0])) {
           $Size = [];
         }else {
           $Size = MasterProductSize::where('master_product_size_id',$value_product_type_b[0])->where('status','01')->get();
         }
         // print_r($Size);
         if (count($Size) > 0) {
           if ($value['del'] == 0) {
             if ($value_product_type_b[4] == 0) {
               // print_r('Update ');

               $weight = str_replace(',','', $value_product_type_b[2]);
                 MasterProductSize::where('master_product_size_id',$value_product_type_b[0])->update([
                     'name' => $value_product_type_b[1],
                     'size_unit' => $value_product_type_b[5],
                     'weight' => $weight,
                     'updated_by' => Auth::id(),
                     'updated_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
                 ]);
             }else {
               // print_r('del ');

               MasterProductSize::where('master_product_size_id', $value_product_type_b[0])->update([
                   'deleted_by' => Auth::id(),
                   'deleted_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
                   'status' => '00',
               ]);
             }

           }else {
             // print_r('del ');

             MasterProductSize::where('master_product_size_id', $value_product_type_b[0])->update([
                 'deleted_by' => Auth::id(),
                 'deleted_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
                 'status' => '00',
             ]);
           }

         }else {
           if ($value['del'] == 0) {
             if ($value_product_type_b[4] == 0) {
               // print_r('Insert');
               if ($value['product_type_id']) {
                 $product_type_id = $value['product_type_id'];
               }else {
                 $product_type_id = $id_ProductType;
               }
               $weight = str_replace(',','', $value_product_type_b[2]);
                 $id_ProductSize = MasterProductSize::insertGetId([
                     'master_product_main_id' => $value['product_id'],
                     'master_product_type_id' => $product_type_id,
                     'name' => $value_product_type_b[1],
                     'size_unit' => $value_product_type_b[5],
                     'weight' => $weight,
                     'created_by' => Auth::id(),
                     'created_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
                 ]);
             }
           }

         }
       }

     }


      // dd($input);
      return response()->json(['status' => true, 'message' => 'บันทึกข้อมูลสำเร็จ']);

    }

    // ลบข้อมูลประเภทรถ
    public function deleteData($id)
    {
        // $usedData = MasterProductMain::find($id)->usedData()->count();
        // $usedData = MasterProductType::find($id)->usedData()->count();
        // $usedData = MasterProductSize::find($id)->usedData()->count();
        // if ($usedData > 0) {
        //     return response()->json(['status' => false, 'message' => 'ประเภทรถนี้ถูกใช้งานอยู่ ไม่สามารถลบข้อมูลได้']);
        // }

        MasterProductMain::where('master_product_main_id', $id)->update([
            'deleted_by' => Auth::id(),
            'deleted_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
            'status' => '00',
        ]);
        MasterProductType::where('master_product_main_id', $id)->update([
            'deleted_by' => Auth::id(),
            'deleted_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
            'status' => '00',
        ]);
        MasterProductSize::where('master_product_main_id', $id)->update([
            'deleted_by' => Auth::id(),
            'deleted_date' => Carbon::now('Asia/Bangkok')->format('Y-m-d H:i:s'),
            'status' => '00',
        ]);
        // MasterProductMain::find($id)->delete();
        return response()->json(['status' => true, 'message' => 'ลบข้อมูลสำเร็จ']);
    }

}
