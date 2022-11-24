@extends('layouts.app')



@section('content')

<div class="form-main" id="page-claim-detail">

    <div class="container-fluid">

        <div class="row">

            <div class="col-12">

                <nav aria-label="breadcrumb">

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item">

                            <a href="{{ route('claim') }}">เมนูการคืน / เคลมสินค้า</a>

                        </li>

                        <li class="breadcrumb-item active" aria-current="page">รายการจัดส่ง</li>

                    </ol>

                </nav>

            </div>

        </div>

        <div class="row">

            <div class="col-12 col-md-6 align-self-end">

                <h2>ใบสั่งซื้อสินค้าเลขที่ : {{ $data['Order']->order_number }}</h2>

            </div>

        </div>

        <hr>
        <div class="row">
            <div class="form-group col-md-3">
                <label for="" class="label-blue">วันที่สั่งซื้อ :</label>
                <br>
                <span>{{ formatDateThat($data['Order']->created_date) }}</span>
            </div>
            <div class="form-group col-md-3">
                <label for="" class="label-blue">ชื่อผู้จัดทำใบสั่งซื้อ :</label>
                <br>
                <span>{{ $data['Order']->admin_name }}</span>
            </div>
            <div class="form-group col-md-3">
                <label for="" class="label-blue">ร้านค้า :</label>
                <br>
                <span>{{ $data['Order']->name_merchant }}</span>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-3">
                <label for="" class="label-blue">หน่วยงาน :</label>
                <br>
                <span>{{ $data['Order']->department_name }}</span>
            </div>
            <div class="form-group col-md-3">
                <label for="" class="label-blue">เลขประจำตัวภาษีอากร :</label>
                <br>
                <span>{{ $data['Order']->tax_number }}</span>
            </div>
            <div class="form-group col-md-6">
                <label for="" class="label-blue">ที่อยู่ :</label>
                <br>
                <span>{{ $data['Order']->status_departmen == "00" ? $data['Order']->address : $data['Order']->address_department }}</span>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-3">
                <label for="" class="label-blue">เบอร์โทรศัพท์ :</label>
                <br>
                <span>{{ $data['Order']->phone_number }}</span>
            </div>
            <div class="form-group col-md-3">
                <label for="" class="label-blue">เบอร์โทรศัพท์ (หน่วยงาน) :</label>
                <br>
                <span>{{ $data['Order']->phone_department }}</span>
            </div>
            <div class="form-group col-md-6">
                <label for="" class="label-blue">จำนวนเงินรวมทั้งสิ้น (บาท) :</label>
                <br>
                <span>{{ number_format($data['Order']->grand_total,2) }}</span>
            </div>
        </div>

        {{-- <div class="row">

            <div class="form-group col-md-3">

                <label for="" class="label-blue">วันที่สั่งซื้อ :</label>

                <br>

                <span>{{ formatDateThat($data['Order']->created_date) }}</span>

    </div>

    <div class="form-group col-md-3">

        <label for="" class="label-blue">ชื่อผู้จัดทำใบสั่งซื้อ :</label>

        <br>

        <span>{{ $data['Order']->admin_name }}</span>

    </div>

</div>

<div class="row">

    <div class="form-group col-md-3">

        <label for="" class="label-blue">ร้านค้า :</label>

        <br>

        <span>{{ $data['Order']->name_merchant }}</span>

    </div>

    <div class="form-group col-md-3">

        <label for="" class="label-blue">หน่วยงาน :</label>

        <br>

        <span>{{ $data['Order']->department_name }}</span>

    </div>

    <div class="form-group col-md-6">

        <label for="" class="label-blue">ที่อยู่ :</label>

        <br>

        <span>{{ $data['Order']->address }}</span>

    </div>

</div>

<div class="row">

    <div class="form-group col-md-3">

        <label for="" class="label-blue">เบอร์โทรศัพท์ :</label>

        <br>

        <span>{{ $data['Order']->phone_number }}</span>

    </div>

    <div class="form-group col-md-3">

        <label for="" class="label-blue">เลขประจำตัวภาษีอากร :</label>

        <br>

        <span>{{ $data['Order']->tax_number }}</span>

    </div>

    <div class="form-group col-md-6">

        <label for="" class="label-blue">จำนวนเงินรวมทั้งสิ้น (บาท) :</label>

        <br>

        <span>{{ number_format($data['Order']->grand_total,2) }}</span>

    </div>

</div> --}}

<div class="row style2" style="background: #f4f4f5;padding-top: 1rem;">

    <div class="form-group col-md-3">

        <label for="">จำนวนสินค้าที่สั่งทั้งหมด :</label>

        <br>

        <span class="c-0095dd">{{ number_format($data['Order']->qty_all) }}</span>

    </div>

    <div class="form-group col-md-3">

        <label for="">จำนวนสินค้าที่ส่งแล้ว :</label>

        <br>

        <span class="c-000000"
            id="sum_order_delivery_item">{{ number_format(($data['Order']->sum_order_delivery_item - $data['Order']->sum_order_delivery_item_claim) - $data['Order']->sum_order_delivery_item_return) }}</span>

    </div>

    <div class="form-group col-md-2">

        <label for="">จำนวนสินค้าค้างส่ง :</label>

        <br>

        <span class="c-ff871e"
            id="remain_delivery_item">{{  number_format($data['Order']->qty_all - $data['Order']->sum_order_delivery_item) }}</span>

    </div>

    <div class="form-group col-md-2">

        <label for="">จำนวนสินค้าเคลม ค้างส่ง :</label>

        <br>

        <span class="c-EE6C98">{{  number_format($data['Order']->sum_order_delivery_item_claim) }}</span>

    </div>

    <div class="form-group col-md-2">
        <label for="">จำนวนสินค้าคืน :</label>
        <br>
        <span class="c-red">{{  number_format($data['Order']->sum_order_delivery_item_return) }}</span>
    </div>
</div>

<div class="row mt-3">

    <div class="col-12 form-content mb-3 table-responsive">

        <table class="table table-striped dt-responsive nowrap" style="width:100%" id="table">

            <thead>

                <tr class="text-center">

                    <th>#</th>

                    <th>เลขที่ใบจัดส่ง</th>

                    <th>วันที่ส่ง</th>

                    <th>จำนวนสินค้าที่จัดส่ง</th>

                    <th>ประเภทรถ</th>

                    <th>ป้ายทะเบียน</th>

                    <th>สถานะการจัดส่ง</th>

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

<script>
    var ds = moment().startOf('month').format('D-M-Y'), de = moment().endOf('month').format('D-M-Y');

    $('input[name="daterange"]').daterangepicker(

    {

        startDate : ds,

        endDate : de,

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

    $( "#status_send , #truck_type" ).change(function() {

        oTable.draw();

    });



    var order_id = "{{$data['Order']->order_id}}";

    var oTable = $("#table").DataTable({

        targets: "no-sort",

        pageLength:50,

        bSort: false,

        bPaginate:false,

        bInfo:false,

        order: [],

        searching: false,

        bLengthChange: false,

        processing: true,

        serverSide: true,

        ajax: {

            url: `{{ url('api/claim/OrderDelivery/${order_id}') }}`,

            method: "GET",

            data: function(d) {

                d._token = "{{ csrf_token() }}";

                d.search = $("#search").val();

                d.date_start = ds;

                d.date_end = de;

                d.status_send = $("#status_send option:selected").val();

                d.truck_type = $("#truck_type option:selected").val();

            }

        },

        columns: [

            {

                data: function(a, b, c, d) {

                    return oTable.page.info().start + d.row + 1;

                }

            },

            { className: "text-center", data: "order_delivery_number" },

            {

            className: "text-center",

            data: function(a) {

                return a.date_schedule ? a.date_schedule : '-';

            }},

            {

            className: "text-center",

            data: function(a) {

                return numeral(a.sum_order_delivery_item).format("0,0");

            }

            },

            {

            className: "text-center",

            data: function(a) {

                return a.type ? a.type : '-';

            }},

            {

            className: "text-center",

            data: function(a) {

                return a.license_plate ? a.license_plate : '-';

            }},

            {   className: "text-center",

                data: function(a) {

                    return delivery_type(a.delivery_type,a.delivery_type_name) + status(a.status);

                }

            },

            {   className: "text-center",

                data: function(a) {

                let url_delivery_send = `{{ route("claim.list.claim.send", [':id', ':delivery_id'] ) }}`.replace(':id', a.order_id).replace(':delivery_id', a.order_delivery_id);

                let btn_a,status_btn = false;



                if (a.delivery_type == '01') {

                    status_btn = true

                }



                //เครม

                btn_a = `<a class="dropdown-item" href="${url_delivery_send}"><i class="fas fa-cog"></i>&nbsp จัดการ คืน / เคลม สินค้า</a>`;





                return `<button id="btnGroupDrop1" type="button" class="btn btn-dark" ${status_btn ? 'disabled' : ''} data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                            <i class="fas fa-cog"></i> จัดการ

                        </button>

                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">



                            ${btn_a}



                        </div>`;

            }

        }

        ]

    });



    $("#btn_search").click(function() {

        oTable.draw();

    });



    $("#btn_reset").click(function() {

        $("#search").val("");

        $("#status_send").val($("#status_send option:first").val());

        $("#truck_type").val($("#truck_type option:first").val());

        ds = moment().startOf('month').format('D-M-Y');

        de = moment().endOf('month').format('D-M-Y');

        $('input[name="daterange"]').data('daterangepicker').setStartDate(ds);

        $('input[name="daterange"]').data('daterangepicker').setEndDate(de);

        oTable.draw();

    });



    let status = status => {

    switch (status) {

        case "00":

        return `<span class="badge-pill text-white py-1" style="font-size:12px; background-color:#495057">ยังไม่จัดส่ง</span>`;

        break;

        case "01":

        return `<span class="badge-pill text-white py-1" style="font-size:12px; background-color:#FF871E">เตรียมการจัดส่ง</span>`;

        break;

        case "02":

        return `<span class="badge-pill text-white py-1" style="font-size:12px; background-color:#FFC627">กำลังจัดส่ง</span>`;

        break;

        case "03":

        return `<span class="badge-pill text-white py-1" style="font-size:12px; background-color:#EE6C98">เลื่อนการจัดส่ง</span>`;

        break;

        case "04":

        return `<span class="badge-pill text-white py-1" style="font-size:12px; background-color:#EE6C98">เคลม</span>`;

        break;

        case "05":

        return `<span class="badge-pill text-white py-1" style="font-size:12px; background-color:#27C671">จัดส่งสำเร็จ</span>`;

        break;

    }

    };



    let delivery_type = (delivrey_type,delivery_type_name) =>  {

        switch (delivrey_type) {

        case "01":

        return `<span class="badge-pill text-white py-1" style="font-size:12px; background-color:red">${delivery_type_name}</span> / `;

        break;

        case "02":

        return `<span class="badge-pill text-white py-1" style="font-size:12px; background-color:#EE6C98">${delivery_type_name}</span> / `;

        break;

        default: return ''; break;

        }

    };



    let confirm_send_success = (order_id,order_delivery_id) =>{

        Swal.fire({

            title: 'ยืนยัน?',

            text: "ต้องการยืนยันการจัดส่ง เลขที่รายการ "+order_delivery_id,

            icon: 'warning',

            showCancelButton: true,

            reverseButtons: true,

            confirmButtonColor: '#3085d6',

            cancelButtonColor: '#d33',

            cancelButtonText: 'ยกเลิก',

            confirmButtonText: 'ยืนยัน',

            showLoaderOnConfirm: true,

            preConfirm: (login) => {

                return fetch(`{{ url('api/delivery/${order_id}/sendOrderDeliverySuccess/${order_delivery_id}') }}`,{method: 'PUT',headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}})

                .then(response => {

                    return response.json()

                })

            },

            allowOutsideClick: () => !Swal.isLoading()

            }).then((result) => {

            if (result.value) {

                Swal.fire(

                    'สำเร็จ',

                    'ยืนยันการจัดส่ง เลขที่รายการ ' + order_delivery_id + ' สำเร็จ',

                    'success'

                )

                $('#sum_order_delivery_item').text(numeral(result.value.sum_order_delivery_item).format('0,0'))

                $('#remain_delivery_item').text(numeral(result.value.qty_all - result.value.sum_order_delivery_item).format('0,0'))

                oTable.draw();

            }

        })

    }



</script>

@endsection