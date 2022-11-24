@extends('layouts.app')

@section('content')
<div class="form-main" id="page-delivery-list">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 align-self-end">
                <h2>การจัดส่งสินค้ารายวัน</h2>
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        {{-- <li class="breadcrumb-item">
                            <a href="{{ route('delivery') }}">จัดการข้อมูลขนส่งรายวัน</a>
                        </li> --}}
                        <li class="breadcrumb-item active" aria-current="page">จัดการข้อมูลขนส่งรายวัน</li>
                    </ol>
                </nav>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="form-group col-md-3">
                <label for="">วันที่เริ่มต้น - วันที่สิ้นสุด :</label>
                <div class="input-group">
                    <input type="text" class="form-control datepicker" name="daterange" id="daterange" autocomplete="off" value="" />
                    <label class="input-group-addon btn btn-outline-secondary m-0" for="daterange"
                        style="border-radius: 0px 0.25rem 0.25rem 0;">
                        <span class="fa fa-calendar"></span>
                    </label>
                </div>
            </div>
            {{-- <div class="form-group col-2 align-self-end">
                <button type="button" class="btn btn-secondary" id="btn_reset">
                    <i class="fas fa-redo"></i>
                    <span>รีเซ็ต</span>
                </button>
            </div> --}}
        </div>
        <div class="row mt-5">
            <div class="col-12 form-content mb-3 table-responsive">
                <table class="table table-striped dt-responsive nowrap" style="width:100%" id="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>วันที่จัดส่ง</th>
                            <th>จำนวนสินค้า</th>
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
    //  $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
    //   $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    // });
    var ds = '', de = '';
    $('.datepicker').daterangepicker(
    {
        // minDate : moment().format('D-M-Y'),
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        }
    }
    );

    $('.datepicker').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
            ds = picker.startDate.format("D-M-Y");
            de = picker.endDate.format("D-M-Y");
            oTable.draw();
            // search_page()
    })
    $('.datepicker').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        $('.datepicker').data('daterangepicker').setStartDate(moment());
        $('.datepicker').data('daterangepicker').setEndDate(moment());
        ds = '';
        de = '';
        oTable.draw();
        // search_page()
    })

    var oTable = $("#table").DataTable({
    targets: "no-sort",
    bSort: false,
    order: [],
    searching: false,
    bLengthChange: false,
    processing: true,
    serverSide: true,
    ajax: {
        url: "{{ route('itemsend.list') }}",
        method: "GET",
        data: function(d) {
            d._token = "{{ csrf_token() }}";
            d.search = $("#search").val();
            d.date_start = ds;
            d.date_end = de;
        }
    },
    columns: [
        {
        data: function(a, b, c, d) {
            return oTable.page.info().start + d.row + 1;
        }
        },
        // { className: "", data: "created_date" },
        // { className: "", data: "name_merchant" },
        // { className: "", data: "department_name" },
        { className: "", data: "date_schedule" },
        {
        className: "text-center",
        data: function(a) {
            return numeral(a.sum_order_delivery_item).format("0,0");
        }
        },
        {
        className: "text-center",
        data: function(a) {
            let url_order_detail = window.location.origin +'/master-data/itemsend/'+a.date_send;
            return `<button id="btnGroupDrop1" type="button" class="btn btn-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-cog"></i> จัดการ
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                <a class="dropdown-item" href="${url_order_detail}"><i class="fas fa-file-alt"></i>&nbsp&nbspดูรายละเอียด</a>
                        </div>`;
        }
        }
    ]
    });


</script>
@endsection
