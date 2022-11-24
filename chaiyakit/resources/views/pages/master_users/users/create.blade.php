@extends('layouts.app')

@section('content')
<div class="form-main" id="delivery_car">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2>สร้างข้อมูลผู้ดูแลระบบ</h2>
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a onclick="window.history.go(-1); return false;">จัดการข้อมูลผู้ดูแลระบบ</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">สร้างข้อมูลผู้ดูแลระบบ</li>
                    </ol>
                </nav>
            </div>
        </div>
        <hr>
        <div class="form mt-5" id="form_users">
            <div class="row">
                <div class="form-group col-4">
                    <label for="role">สิทธ์ใช้งาน : <span class="text-danger">*</span></label>
                    <select class="form-control" id="role">
                    <option value="">-- เลือก --</option>
										@foreach($roles as $value)
										<option value="{{ $value->name }}" >{{ $value->name }}</option>
										@endforeach
									</select>
                </div>
               

            </div>
            <div class="row ">
                <div class="form-group col-4">
                    <label for="name">ชื่อ : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control"  id="name">
                </div>
                <div class="form-group col-4">
                    <label for="email">บัญชีผู้ใช้งาน (Email) : <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" placeholder="name@example.com">
                </div>
                </div>
                <div class="row ">
                <div class="form-group col-4">
                    <label for="password">รหัสผ่าน : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="password">
                </div>
                <div class="form-group col-4">
                    <label for="password2">ยืนยันรหัสผ่าน : <span class="text-danger">*</span></label>
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
    	
    		var name 		  = $('#name').val();
    		var email 	  = $('#email').val();
    		var password 	  = $('#password').val();
    		var re_password   = $('#re_password').val();
    		if(role == '')
    		{
    			$('#role').addClass('input-error');
    			alertError('กรุณาเลือกสิทธ์ใช้งาน');
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
    		else if(password == '')
    		{
    			$('#password').addClass('input-error');
    			alertError('กรุณากรอกรหัสผ่านใหม่');
    		}
    		else if(re_password == '')
    		{
    			$('#re_password').addClass('input-error');
    			alertError('กรุณากรอกยืนยันรหัสผ่าน');
    		}
    		else if(password != re_password)
    		{
    			$('#password').addClass('input-error');
    			$('#re_password').addClass('input-error');
    			alertError('รหัสผ่านยืนยันไม่ตรงกัน');
    		}
    		else
    		{
    			$.ajax({
    				url: '{{ url("master-users/users/create") }}',
    				type: 'POST',
    				data: { _token			: '{{ csrf_token() }}',
    					    role			: role,
    					    name			: name,
    					    email 		: email,
    					    password 		: password
                            }
    			})
    			.done(function(res) 
    			{
    				if (res.status == true) 
    				{
						Swal.fire('สำเร็จ!',res.message,'success').then(() => window.location = "/master-users/users")
    				}
    				else
    				{
    					$('#'+res.id_error+'').addClass('input-error');
    					alertError(res.remark);
    				}
    			});
    		}
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