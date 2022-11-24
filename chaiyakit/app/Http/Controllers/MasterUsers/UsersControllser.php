<?php

namespace App\Http\Controllers\MasterUsers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\User;
use DataTables;
use Carbon\Carbon;
class UsersControllser extends Controller
{
    public function __construct()
    {
    }
    public function index()
    {
        return view('pages.master_users.users.index');
    }

    public function add()
    {
        $roles = Role::all();
        
        return view('pages.master_users.users.create',compact('roles'));
      
    }
    
    public function create(Request $request)
    {
        $chk_data = User::where('email',$request->email)->count();

        if ($chk_data > 0) 
        {
            return response()->json(['success' => false, 'remark' => 'บัญชีผู้ใช้งาน ซ้ำกรุณาเปลี่ยนใหม่!!']);
        }
        else
        {
            $insert = new User;
            $insert->name        = $request->name;
            $insert->email        = $request->email;
            $insert->password        = bcrypt($request->password);
            $insert->created_by= $request->user()->id;
            $insert->save();
            $user = User::find($insert->id);
            $user->assignRole($request->role);
            return response()->json(['status' => true, 'message' => 'เพิ่มข้อมูลสำเร็จ']);
        }
    }
    public function getData(Request $request)
    {
        $data = User::where('status','!=','02');
        if ($request->search_key) {
            $data->where(function ($query) use ($request) {
                $query->where('name', 'LIKE', $request->search_key . '%');
                $query->orWhere('email', 'LIKE', '%' . $request->search_key . '%');
               
            });
        }
        return Datatables::of($data->get())
      

        ->addColumn('role', function ($data) 
        {
            return $data->roles->pluck('name')[0];
        })
        ->addColumn('status_text', function ($data) 
        {   $status = '';
            if ($data->status == '00') 
            {
                $status = '<span class="badge badge-pill badge-danger">ปิดใช้งาน</span>';
            }
            else if ($data->status == '01') 
            {
                $status = '<span class="badge badge-pill badge-success">เปิดใช้งาน</span>';
            } else if ($data->status == '02') 
            {
                $status = '<span class="badge badge-pill badge-secondary">ยกเลิกใช้งาน</span>';
            }
           
            return $status;
        })
        ->rawColumns(['status_text'])
        ->make(true);
    }
    public function manage($id)
    {
        $data = User::where('id',$id)->first();
        $roles = Role::all();
        return view('pages.master_users.users.update',compact('data','roles'));
    }

    public function update(Request $request)
    {
        $chk_data = User::where('email',$request->email)->where('id','!=',$request->id)->count();

        if ($chk_data > 0) 
        {
            return response()->json(['success' => false, 'remark' => 'ภาษี ซ้ำกรุณาเปลี่ยนใหม่!!']);
        }
        else
        {
          
            $data=[
                'name' =>$request->name,
                'email' =>$request->email,
                'status' =>$request->status,
                'updated_by' => $request->user()->id,
            ];
            if($request->password){
                $data['password'] = bcrypt($request->password);
            }
           
            User::where('id',$request->id)
            ->update($data);
            $user = User::find($request->id);
            if($user->roles->pluck('name')[0] !=$request->role){
                if(!empty($user->roles->pluck('name')[0])){
            $user->removeRole($user->roles->pluck('name')[0]);
                }
            $user->assignRole($request->role);
            }
           
            return response()->json(['status' => true, 'message' => 'บันทึกข้อมูลสำเร็จ']);
        }
    }
    public function delete($id)
    {
        if (isset($id)) 
        {
            $user = User::where('id',$id)
            ->update([
                'status' =>'02',
                'deleted_at' => Carbon::now('Asia/Bangkok'),
                'deleted_by' => Auth::id(),
            ]);
              
            return response()->json(['status' => true, 'message' => 'ลบข้อมูลสำเร็จ']);
        }
        else
        {
            return response()->json(['status' => false, 'message' => 'ไม่สามารถลบข้อมูลได้']);
        }
    }
}
