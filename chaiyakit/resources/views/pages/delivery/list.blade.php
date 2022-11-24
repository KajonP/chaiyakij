@extends('layouts.app')

@section('content')
<div class="form-main" id="page-delivery-list">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 align-self-end">
                <h2>การจัดส่งสินค้า</h2>
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('delivery') }}">เมนูการจัดส่งสินค้า</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">การจัดส่งสินค้า</li>
                    </ol>
                </nav>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="form-group col-md-4">
                <label for="">Search :</label>
                <div class="input-group">
                    <input class="form-control py-2" type="search" value="" id="search" name="search"
                        placeholder="ค้นหาเลขที่ใบสั่งซื้อ,ชื่อร้านค้า,หน่วยงาน">
                    <span class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="btn_search">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>
            </div>
            <div class="form-group col-md-3">
                <label for="">วันที่เริ่มต้น - วันที่สิ้นสุด :</label>
                <div class="input-group">
                    <input type="text" class="form-control date" name="daterange" id="daterange" value="" />
                    <label class="input-group-addon btn btn-outline-secondary m-0" for="daterange"
                        style="border-radius: 0px 0.25rem 0.25rem 0;">
                        <span class="fa fa-calendar"></span>
                    </label>
                </div>
            </div>
            <div class="form-group col-md-2">
                <label for="">สถานะการจัดส่ง :</label>
                <select class="form-control" name="status_send" id="status_send">
                    <option value="">-- ทุกสถานะ --</option>
                    <option value="00">สั่งซื้อสินค้า</option>
                    <option value="01">ดำเนินการจัดส่ง</option>
                    <option value="03">จัดส่งสำเร็จ</option>
                    <option value="04">ยกเลิกบิล</option>
                </select>
            </div>
            <div class="form-group col-2 align-self-end">
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
                            <th>เลขที่ใบสั่งซื้อ</th>
                            <th>ชื่อร้านค้า</th>
                            <th>หน่วยงาน</th>
                            <th>จำนวนเงิน</th>
                            <th>จำนวนที่สั่ง</th>
                            <th>ส่งสินค้า</th>
                            <th>สินค้าค้างส่ง</th>
                            <th>สถานะ</th>
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
    $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });
    var ds = '', de = '';
    $('input[name="daterange"]').daterangepicker(
    {
        autoUpdateInput: false,
        maxDate : moment().format('D-M-Y'),
        opens: "center",
        drops: "up",
        ranges: {
           'วันนี้': [moment(), moment()],
           'เมื่อวาน': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'ย้อนหลัง 7 วัน': [moment().subtract(6, 'days'), moment()],
           'ย้อนหลัง 30 วัน': [moment().subtract(29, 'days'), moment()],
           'เดือนนี้': [moment().startOf('month'), moment().endOf('month')],
           'เดือนที่แล้ว': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    },
    function(start, end, label) {
        ds = start.format("D-M-Y");
        de = end.format("D-M-Y");
        oTable.draw();
    }
    );

    $( "#status_send" ).change(function() {
        oTable.draw();
    });

    var oTable = $("#table").DataTable({
    "targets" : "no-sort",
    "bSort" : false,
    "order" : [],
    "searching" : false,
    //bLengthChange: false,
    processing: true,
    serverSide: true,
    ajax: {
        //url: "{{ url('api/delivery/list2') }}",
        url: "{{ route('delivery-list') }}",
        type: "GET",
        data: function(d) {
          d._token = "{{ csrf_token() }}";
          d.search = $("#search").val();
          d.date_start = ds;
          d.date_end = de;
          d.status_send = $("#status_send option:selected").val();
		},
		error: function(request, status, error, response) {
		//	console.log(response);
			alert(request.responseText);
			alert(error);
		},
    },
    columns: [
        {
        data: function(a, b, c, d) {
            return oTable.page.info().start + d.row + 1;
        }
        },
        { className: "", data: "created_date" },
        { className: "", data: "order_number" },
        { className: "", data: "name_merchant" },
        { className: "", data: "department_name" },
        {
        className: "text-right",
        data: function(a) {
            return numeral(a.grand_total).format("0,0.00");
        }
        },
        {
        className: "text-center",
        data: function(a) {
            return numeral(a.qty_all).format("0,0");
        }
        },
        {
        className: "text-center",
        data: function(a) {
            //return numeral(a.sum_order_delivery_item).format("0,0");
            return numeral(a.qty_all).format("0,0");
        }
        },
        {
        className: "text-center",
        data: function(a) {
            //return numeral(a.qty_all - a.sum_order_delivery_item).format("0,0");
            return numeral(a.qty_all).format("0,0");
        }
        },
        {
        className: "text-center",
        data: function(a) {
            return status(a.staus_send);
        }
        },
        {
        className: "text-center",
        data: function(a) {
            let url_order_detail = '{{ route("delivery.list.order-detail", ":id") }}'.replace(
            ":id",
            a.order_id
            );
            let url_list_delivery = '{{ route("delivery.list.delivery", ":id") }}'.replace(
            ":id",
            a.order_id
            );
            var strbtn = '';			//Pera
            if(a.staus_send != '04') {
                strbtn =  '<a class="dropdown-item" href="'+url_list_delivery+'"><i class="fas fa-cog"></i>&nbspรายการจัดส่ง</a>';
            }
            console.log(strbtn);
            return '<button id="btnGroupDrop1" type="button" class="btn btn-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
                   +'<i class="fas fa-cog"></i> จัดการ '
                   +'</button>'
                   +'<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">'
                   +'<a class="dropdown-item" href="${url_order_detail}"><i class="fas fa-file-alt"></i>&nbsp&nbspดูใบสั่งซื้อ</a>'
                                +strbtn+
                        '</div>';        }
        }
    ]
    });

    $("#btn_search").click(function() {
    oTable.draw();
    });

    $("#btn_reset").click(function() {
    $("#search").val("");
    $("#status_send").val($("#status_send option:first").val());
    ds = '';
    de = '';
    $('input[name="daterange"]').data('daterangepicker').setStartDate(ds);
    $('input[name="daterange"]').data('daterangepicker').setEndDate(de);
    oTable.draw();
    });

    let status = status => {
    switch (status) {
        case "01":
        return '<span class="badge-pill text-white py-1" style="font-size:12px; background-color:#0095DD">ดำเนินการจัดส่ง</span>';
        break;
        case "02":
        return '<span class="badge-pill text-white py-1" style="font-size:12px; background-color:#FFC627">เคลม</span>';
        break;
        case "03":
        return '<span class="badge-pill text-white py-1" style="font-size:12px; background-color:#27C671">ส่งสำเร็จ</span>';
        break;
        case "04":
        return '<span class="badge-pill text-white py-1" style="font-size:12px; background-color:#ed2323">ยกเลิกบิล</span>';
        break;
        default:
        // return '<span class="badge-pill text-white py-1" style="font-size:12px; background-color:#FF871E">สั่งซื้อสินค้า</span>';
        return '<span class="badge-pill text-white py-1" style="font-size:12px; background-color:#FFCC00">สั่งซื้อสินค้า</span>';
        break;
    }
    };

</script>
@endsection
