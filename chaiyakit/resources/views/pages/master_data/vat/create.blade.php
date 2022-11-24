@extends('layouts.app')

@section('content')
<div class="form-main" id="car_type_information">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-xs-6">
                <h2>สร้างข้อมูลภาษี</h2>
            </div>
            <div class="col-12 col-xs-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a onclick="window.history.go(-1); return false;">จัดการข้อมูลภาษี</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">สร้างข้อมูลภาษี</li>
                    </ol>
                </nav>
            </div>
        </div>
        <hr>
        <div class="row  mt-5 form" id="form_car_type_information">
            <div class="form-group col-3 col-xs-3">
                <label for="exampleInputEmail1">ภาษี (%) : </label> <span style="color: red; font-size: 16px;">*</span>
                <div class="input-group ">
            <div class="input-group-prepend">
    <div class="input-group-text">
      <input type="checkbox" aria-label="ค่าเริ่มต้น" name="is_default" id="is_default" value="01"  >
      <span class="ml-1">เป็นค่าเริ่มต้น</span>
    </div>
  </div>
                <input type="number" class="form-control" id="vat"  value=>
                </div>
            </div>
            <div class="col-12 col-xs-6 text-center mt-5">
                <a onclick="window.history.go(-1); return false;" type="button" class="btn btn-danger text-white">ยกเลิก</a>
                <button type="button" id="btn_save" class="btn btn-success">ยืนยันทำรายการ</button>
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

    		var vat 	  = $('#vat').val();
			var is_default 	  = $('#is_default:checked').val();


    		if(vat == '')
    		{
    			$('#vat').addClass('input-error');
    			alertError('กรุณากรอกภาษี');
    		}
    		
    		else
    		{
    			$.ajax({
    				url: '{{ url("master-data/vat/create") }}',
    				type: 'POST',
    				data: { _token		  : '{{ csrf_token() }}',
                            vat		  : vat,
							is_default  :is_default
    					  }
    			})
    			.done(function(res) 
    			{
    				if (res.status == true) 
    				{
						Swal.fire('สำเร็จ!',res.message,'success').then(() => (window.location = "/master-data/vat"))
    				}
    				else
    				{
    					$('#vat').addClass('input-error');
    					alertError(res.remark);
    				}
    			});
    		}
    	});

    	function reset_error_input()
    	{
    		$('#vat').removeClass('input-error');
    		
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