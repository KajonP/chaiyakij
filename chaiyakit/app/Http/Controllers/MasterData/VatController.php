<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterVat;
use App\User;
use DataTables;
use Carbon\Carbon;
class VatController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        return view('pages.master_data.vat.index');
    }

    public function add()
    {
        return view('pages.master_data.vat.create');
    }
    
    public function create(Request $request)
    {
        $chk_data = MasterVat::where('vat',$request->vat)->count();

        if ($chk_data > 0) 
        {
            return response()->json(['success' => false, 'remark' => 'ภาษี ซ้ำกรุณาเปลี่ยนใหม่!!']);
        }
        else
        {
            $insert = new MasterVat;
            $insert->vat        = $request->vat;
            $insert->updated_by= $request->user()->id;
            $insert->is_default = (!empty( $request->is_default)) ? $request->is_default :'00';
            $insert->save();
            if(!empty( $request->is_default)){
                MasterVat::where('master_vat_id','!=',$insert->master_vat_id)->update(['is_default' =>'00']);
               }

            return response()->json(['status' => true, 'message' => 'เพิ่มข้อมูลสำเร็จ']);
        }
    }
    public function getData()
    {
        $data = MasterVat::all();
        return Datatables::of($data)
        ->addColumn('status_text', function ($data) 
        {   $status = '';
            if ($data->is_default == '01') 
            {
               $status = '<span class="badge badge-pill badge-success">ค่าเริ่มต้น</span>';                
            }
           
            return $status;
        })
        ->addColumn('updated_date', function ($data) 
        {           
           
            // $dt = Carbon::createFromFormat('Y-m-d H:i:s', $data->updated_date);
            // return $dt->addYears(543)->format('d/m/Y H:i:s');
            return $data->getFormattedUpdatedDate();
        })
        ->addColumn('update_name', function ($data) 
        {   $name = '';
           
              $user_data=  User::where('id',$data->updated_by)->first();
               $name = $user_data->name;                
           
           
            return $name;
        })
        ->rawColumns(['status_text'])
        ->make(true);
    }
    public function manage($id)
    {
        $data = MasterVat::where('master_vat_id',$id)->first();
        
        return view('pages.master_data.vat.update',compact('data'));
    }

    public function update(Request $request)
    {
        $chk_data = MasterVat::where('vat',$request->vat)->where('master_vat_id','!=',$request->id)->count();

        if ($chk_data > 0) 
        {
            return response()->json(['success' => false, 'remark' => 'ภาษี ซ้ำกรุณาเปลี่ยนใหม่!!']);
        }
        else
        {
          
            MasterVat::where('master_vat_id',$request->id)
            ->update(['vat'        => $request->vat,
            'updated_by'     => $request->user()->id,
            'is_default' => (!empty( $request->is_default)) ? $request->is_default :'00'
            ]);

           if(!empty( $request->is_default)){
            MasterVat::where('master_vat_id','!=',$request->id)->update(['is_default' =>'00']);
           }
            return response()->json(['status' => true, 'message' => 'เพิ่มข้อมูลสำเร็จ']);
        }
    }
    public function delete($id)
    {
        if (isset($id)) 
        {
            MasterVat::where('master_vat_id',$id)->delete();
            return response()->json(['status' => true, 'message' => 'ลบข้อมูลสำเร็จ']);
        }
        else
        {
            return response()->json(['success' => false]);
        }
    }
}
