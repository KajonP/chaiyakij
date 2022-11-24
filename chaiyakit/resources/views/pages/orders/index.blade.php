@extends('layouts.app')

@section('content')
<div class="form-main" id="delivery_car">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-6">
                <h2>จัดการใบสั่งซื้อ</h2>
            </div>
            @hasanyrole('super-admin|manager|admin')
            <div class="col-12 col-md-6 text-right">
                <a href="{{ route('orders.add') }}" class="btn btn-lg btn-danger"><i class="fa fa-plus"></i> สร้างใหม่</a>
            </div>
            @endhasanyrole
        </div>
        <hr>
        <div class="row">
            <div class="input-group col-md-4">
                <input class="form-control py-2" type="search" value="" id="search_key" name="search_key"
                    placeholder="ค้นหาเลขที่สั่งซื้อ,ชื่อร้านค้า,หน่วยงาน">
                <span class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" id="btn_search">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
            <div class="input-group col-md-4">


            <div id="daterange" class="daterangepicker_box" >
                <span class="col-md-10">ค้นหาจากวันที่เริ่มต้น - วันที่สิ้นสุด</span>   <i class="fa fa-calendar"></i>&nbsp;
                <i class="fa fa-caret-down"></i>
            </div>
           <input type="hidden" id="startDate">
           <input type="hidden" id="endDate">
            </div>
            <div class="input-group col-md-2">
                <select class="form-control" name="status_send" id="status_send">
                    <option value="">-- ทุกสถานะ --</option>
                    <option value="00">สั่งซื้อสินค้า</option>
                    <option value="01">ดำเนินการจัดส่ง</option>
                    <option value="03">จัดส่งสำเร็จ</option>
                    <option value="04">ยกเลิกบิล</option>

                </select>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-secondary" id="btn_reset">
                    <i class="fas fa-redo"></i>
                    <span>รีเซ็ต</span>
                </button>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12 form-content mb-3 table-responsive">
                <table class="table table-striped dt-responsive nowrap" style="width:100%" id="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>วันที่สั่งซื้อ</th>
                            <th>เลขที่สั่งซื้อ</th>
                            <th>ชื่อร้านค้า</th>
                            <th>หน่วยงาน</th>
                            <th>จำนวนเงิน</th>
                            <th>สถานะ</th>
                            <th>ผู้จัดทำ</th>
                            <th>ผู้แก้ไข้</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')

<script type="text/javascript">
 var start = moment().subtract(29, 'days');
var end = moment();


   var oTable = $('#table').DataTable({
	       		"targets": 'no-sort',
	            "bSort": false,
	            "order": [],
	            "searching": false,
	            processing: true,
	            serverSide: true,
	            ajax: {
	                url : "{{ url('api/orders/getData') }}",
	                method : 'POST',
		            data: function(d)
	                { d._token = "{{ csrf_token() }}";
	                  d.search_key = $('#search_key').val();
                      d.search_type = $("#status_send option:selected").val();
                      d.startDate = $('#startDate').val();
                      d.endDate = $('#endDate').val();
	                 }
	            },
	            columns: [{ data: function(a,b,c,d){ return (oTable.page.info().start+d.row+1); } , name: 'order_id'},
						  { data: 'created_date'},
                          { data: 'order_number'},
                          { data: 'name_merchant'},
                          { data: 'department_name'},
                          { data: 'price_all', className: "text-right"},
                          { data: 'status_text', className: "text-center"},
                          { data: 'create_name'},
                          { data: 'update_name'},
                          {
            className: "text-center",
            data: function(a) {
                //check superadmin
                var rloes = {{\Auth::user()->hasRole('super-admin') ? 'true' : 'false'}}
                var str = ''
                if(rloes == true)
                {
                    //check status send
                    if(a.staus_send == '00'){
                        str += `<a class="dropdown-item"  href="/orders/edit/${a.order_id}"><i class="fa fa-cog" aria-hidden="true"></i> แก้ไขบิล</a>`
                        str += `<a class="dropdown-item" onClick="Cancelorder(${a.order_id})" href="javascript:void(0)"><i class="fa fa-times" aria-hidden="true"></i> ยกเลิกบิล</a>`
                    }
                    //check status send end
                }
                //check  superadmin end
                return `<div class="dropdown  show">
                            <button role="button" type="button" id="dropdownMenuLink" class="btn btn-dark" data-toggle="dropdown"><i class="fas fa-cog"></i> จัดการ</button>`+
                            `<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="/orders/detail/${a.order_id}">
                                <i class="fas fa-file-alt" aria-hidden="true"></i> ดูใบสั่งซื้อ</a>`
                                +str+
                            ` </div>
                        </div>`;
            }
            }
                          ],
	            });
        $('#btn_search').click(function(e)
    	{
    		oTable.draw();
    	});
        $( "#status_send" ).change(function() {
        oTable.draw();
    });
        $('#btn_reset').click(function(e)
    	{
    		$('#search_key').val('');
            $('#daterange span').html('ค้นหาจากวันที่เริ่มต้น - วันที่สิ้นสุด');
            $('#startDate').val('');
            $('#endDate').val('');
            start = moment().subtract(29, 'days');
            end = moment();
    		oTable.draw();
    	});


    function cb(start, end) {
        $('#daterange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
        $('#startDate').val(start.format('YYYY-MM-DD'));
        $('#endDate').val(end.format('YYYY-MM-DD'));

    }
    $('#daterange').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });
    $('#daterange').daterangepicker({
        autoUpdateInput: false,
        maxDate : moment(),
        opens: 'left',
        alwaysShowCalendars : true,
        showCustomRangeLabel : false,
        autoUpdateInput: true,
        autoApply: false,
        ranges: {
           'วันนี้': [moment(), moment()],
           'เมื่อวาน': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           '7 วันล่าสุด': [moment().subtract(6, 'days'), moment()],
           '30 วันล่าสุด': [moment().subtract(29, 'days'), moment()],
           '180 วันล่าสุด': [moment().subtract(179, 'days'), moment()],
           'เดือนนี้': [moment().startOf('month'), moment().endOf('month')],
        //    'เดือนที่แล้ว': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
           'ปีนี้': [moment().startOf('year'), moment()]
        }
    });

    $('#daterange').on('apply.daterangepicker', function(ev, picker) {
        cb(picker.startDate, picker.endDate);
        oTable.draw();
    });
    $('#daterange').on('cancel.daterangepicker', function(ev, picker) {

        $('#daterange span').html('ค้นหาจากวันที่เริ่มต้น - วันที่สิ้นสุด');
                $('#startDate').val('');
                $('#endDate').val('');
                start = moment().subtract(29, 'days');
                end = moment();
    });
    function Cancelorder(id)
    {
        Swal.fire({
            title: 'ต้องการยกเลิกใบสั่งซื้อนี้ใช่ไหม',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ยืนยัน',
            cancelButtonText: 'ยกเลิก',
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "{{route('oredrs.cancelorder')}}",
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    data: {order_id : id},
                    success: function(res) {
                         Swal.fire({
                            title: res.mgs,
                            icon: res.status,
                            // confirmButtonText: 'OK',
                        }).then((result) => {
                            $('#table').DataTable().ajax.reload();
                            // console.log(result);
                        })
                    },
                    error : function(er){
                        console.log(er);
                    }
                });
            }
        })
        // console.log(id);
    }
</script>
@endsection
