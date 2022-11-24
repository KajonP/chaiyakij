@extends('layouts.app')

@section('content')
<div class="form-main" id="delivery_car">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-6">
                <h2>แก้ไขข้อมูลผู้ดูแลระบบ</h2>
            </div>
            <div class="col-12 col-md-6 text-right">
            @if ($data->id  > 1)
                <h5><a href="" id="delete" class="text-danger border-bottom border-danger">
                        <i class="fa fa-trash"></i>
                        ต้องการลบข้อมูลนี้
                    </a></h5>
                    @endif
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a onclick="window.history.go(-1); return false;">จัดการข้อมูลผู้ดูแลระบบ</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">แก้ไขข้อมูลผู้ดูแลระบบ</li>
                    </ol>
                </nav>
            </div>
        </div>
        <hr>
        <div class="form mt-5" id="form_users">
            <div class="row">
                <div class="form-group col-4">
                    <label for="role">สิทธ์ใช้งาน <span class="text-danger">*</span></label>
                    <select class="form-control" id="role" {{ $data->id == 1 ? 'disabled' : '' }}>
                    <option value="">-- เลือก --</option>
										@foreach($roles as $value)
										<option value="{{ $value->name }}" {{ $value->name == $data->roles->pluck('name')[0] ? 'selected' : '' }} >{{ $value->name }}</option>
										@endforeach
									</select>
                </div>
                <div class="form-group col-4">
                    <label for="role">สถานะ <span class="text-danger">*</span></label>
                    <select class="form-control" id="status" {{ $data->id == 1 ? 'disabled' : '' }}>
										<option value="">-- เลือก --</option>
										<option value="01" {{  $data->status == '01' ? 'selected' : '' }} >เปิดใช้งาน</option>
										<option value="00" {{  $data->status == '00' ? 'selected' : '' }} >ปิดการใช้งาน</option>
									</select>
                </div>
            </div>
            <div class="row ">
                <div class="form-group col-4">
                    <label for="name">ชื่อ <span class="text-danger">*</span></label>
                    <input type="text" class="form-control"  id="name" value="{{$data->name}}">
                    <input type="hidden" class="form-control" id="id" name="id" value="{{ $data->id }}">
                </div>
                <div class="form-group col-4">
                    <label for="email">บัญชีผู้ใช้งาน (Email) <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" placeholder="name@example.com" value="{{$data->email}}" {{ $data->id == 1 ? 'readonly' : '' }}>
                </div>
                </div>
               
                <div class="row ">
                <div class="col-12 breadcrumb">
									<b>เปลี่ยนรหัสผ่าน</b>
								</div>
                <div class="form-group col-4">
                    <label for="password">รหัสผ่าน :</label>
                    <input type="text" class="form-control" id="password">
                </div>
                <div class="form-group col-4">
                    <label for="password2">ยืนยันรหัสผ่าน :</label>
                    <input type="text" class="form-control" id="re_password">
                </div>
                <div class="col-12 text-center mt-5">
                    <a onclick="window.history.go(-1); return false;" type="button" class="btn btn-danger text-white">ยกเลิก</a>
                    <button type="button" class="btn btn-success" id="btn_save">ยืนยันทำรายการ</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')

<script type="text/javascript">
	$('#btn_save').click(function()
    	{
    		reset_error_input();

    		var role 		  = $('#role').val();
            var id 	  		  = $('#id').val();
    		var name 		  = $('#name').val();
    		var email 	  = $('#email').val();
    		var password 	  = $('#password').val();
    		var re_password   = $('#re_password').val();
            var status 		  = $('#status').val();
    		if(role == '')
    		{
    			$('#role').addClass('input-error');
    			alertError('กรุณาเลือกสิทธ์ใช้งาน');
    		}
            else if(status == '')
    		{
    			$('#status').addClass('input-error');
    			alertError('กรุณากรอกสถานะ');
    		}
    		else if(name == '')
    		{
    			$('#name').addClass('input-error');
    			alertError('กรุณากรอกชื่อ');
    		}
    		else if(email == '')
    		{
    			$('#email').addClass('input-error');
    			alertError('กรุณากรอกบัญชีผู้ใช้งาน (Email)');
    		}
            else if(emailIsValid(email) == false)
    		{
    			$('#email').addClass('input-error');
    			alertError('บัญชีผู้ใช้งาน ต้องใส่ในรูปแบบ Email');
    		}
    		
    		else if(re_password == '' && password.length >0)
    		{
    			$('#re_password').addClass('input-error');
    			alertError('กรุณากรอกยืนยันรหัสผ่าน');
    		}
    		else if(password != re_password && password.length >0)
    		{
    			$('#password').addClass('input-error');
    			$('#re_password').addClass('input-error');
    			alertError('รหัสผ่านยืนยันไม่ตรงกัน');
    		}
    		else
    		{
    			$.ajax({
    				url: '{{ url("master-users/users/update") }}',
    				type: 'POST',
    				data: { _token			: '{{ csrf_token() }}',
    					    role			: role,
    					    name			: name,
    					    email 		: email,
    					    password 		: password,
                            id 				: id,
                            status          : status
                            }
    			})
    			.done(function(res) 
    			{
    				if (res.success == true) 
    				{
    					Swal.fire({ type: 'success', title: 'สำเร็จ', text: 'แก้ไขข้อมูลสำเร็จ' }).then(
			    		() => window.location = "/master-users/users")
    				}
    				else
    				{
    					$('#'+res.id_error+'').addClass('input-error');
    					alertError(res.remark);
    				}
    			});
    		}
    	});
        $( "#delete" ).click(function() {
        Swal.fire({
        title: 'ต้องการลบข้อมูลนี้ ?',
        text: "",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'ยกเลิก',
        confirmButtonText: 'ยืนยันการลบ',
        reverseButtons: true
        }).then((result) => {
            var id 	  = $('#id').val();
        if (result.value) {
            $.ajax({
    				url: '{{ url("master-users/users/delete") }}/'+id,
    				type: 'GET',
    				
    			})
    			.done(function(res) 
    			{
                    if (res.success == true) 
    				{
    					Swal.fire({ type: 'success', title: 'สำเร็จ!', text: 'ทำการลบข้อมูลเรียบร้อยแล้ว' }).then(
			    		() => window.location = "/master-users/users")
    				}
    				else
    				{
    					$('#vat').addClass('input-error');
    					alertError(res.remark);
    				}
                });
        }
        });
        return false;
    });
        function emailIsValid (email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)
        }
    	function reset_error_input()
    	{
    		$('#role').removeClass('input-error');
    		$('#email').removeClass('input-error');
    		$('#password').removeClass('input-error');
			$('#re_password').removeClass('input-error');
            $('#status').removeClass('input-error');
    	}
        function alertError(text)
    	{
    		Swal.fire({
						type: 'warning',
						title: 'แจ้งเตือน',
                        icon: "warning",
                        dangerMode: true,
						text: text
					 })
    	}
</script>
@endsection