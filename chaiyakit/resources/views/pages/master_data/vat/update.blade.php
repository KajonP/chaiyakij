@extends('layouts.app')

@section('content')
<div class="form-main" id="car_type_information">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-6 col-xs-6">
                <h2>แก้ไขข้อมูลภาษี</h2>
            </div>
            <div class="col-12 col-md-6 col-xs-6 text-right">
            @if ($data->master_vat_id  > 2)
                <h5><a href="" id="delete" class="text-danger border-bottom border-danger">
                        <i class="fa fa-trash"></i>
                        ต้องการลบข้อมูลนี้
                    </a></h5>
                    @endif
            </div>
            <div class="col-12 col-xs-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a onclick="window.history.go(-1); return false;">จัดการข้อมูลภาษี</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">แก้ไขข้อมูลภาษี</li>
                    </ol>
                </nav>
            </div>
        </div>
        <hr>
        <div class="row  mt-5 form" id="form_car_type_information">
            <div class="form-group col-3 col-xs-3">
            <input type="hidden" name="id" id="id" value="{{ $data->master_vat_id }}">
            <label for="exampleInputEmail1">ภาษี (%) : </label> <span style="color: red; font-size: 16px;">*</span>
            <div class="input-group ">
            <div class="input-group-prepend">
    <div class="input-group-text">
      <input type="checkbox" aria-label="ค่าเริ่มต้น" name="is_default" id="is_default" value="01"  {{ ($data->is_default === '01') ? 'checked="checked" ' : '' }}>
      <span class="ml-1">เป็นค่าเริ่มต้น</span>
    </div>
  </div>
                <input type="number" class="form-control" id="vat"  value="{{ $data->vat }}">
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
            var id 	  = $('#id').val();
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
    				url: '{{ url("master-data/vat/update") }}',
    				type: 'POST',
    				data: { _token		  : '{{ csrf_token() }}',
                            id		  :id,
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
    				url: '{{ url("master-data/vat/delete") }}/'+id,
    				type: 'GET',
    				
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
        return false;
    });
</script>
@endsection