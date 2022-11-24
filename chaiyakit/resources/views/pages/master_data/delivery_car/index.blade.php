@extends('layouts.app')

@section('content')
<div class="form-main" id="delivery_car">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-6">
                <h2>จัดการข้อมูลรถจัดส่งสินค้า</h2>
            </div>
            <div class="col-12 col-md-6 text-right">
                <a href="{{ route('delivery_car.create') }}" class="btn btn-lg btn-danger"><i class="fa fa-plus"></i>
                    สร้างใหม่</a>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="input-group col-md-4">
                <input class="form-control py-2" type="search" value="" id="search" placeholder="ค้นหา...">
                <span class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" id="btn_search">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
            <div class="input-group col-md-2">
                <select class="form-control" name="master_truck_type_id" id="master_truck_type_id">
                    <option value="">-- ทุกประเภท --</option>
                    @foreach ($master_truck_type as $item)
                    <option value="{{$item->master_truck_type_id}}">{{$item->type}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-5">
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
                            <th>ประเภทรถ</th>
                            <th>บรรทุกน้ำหนัก (ตัน)</th>
                            <th>วันที่หมดอายุภาษีทะเบียนรถ</th>
                            <th>ป้ายทะเบียน</th>
                            {{-- <th>พิกัด (GPS)</th> --}}
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
    var oTable = $("#table").DataTable({
        targets: "no-sort",
        bSort: false,
        order: [],
        searching: false,
        bLengthChange: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('api/master-data/delivery_car') }}",
            method: "GET",
            data: function(d) {
            d._token = "{{ csrf_token() }}";
            d.search = $("#search").val();
            d.search_type = $("#master_truck_type_id option:selected").val();
            }
        },
        columns: [
            { data: function(a, b, c, d) { return oTable.page.info().start + d.row + 1; }, "width": "10%",name: "master_truck_id" },
            { "width": "10%", className: "text-left",data: "type" },
            { "width": "30%",className: "text-center",data: function(a) { return numeral(a.weight).format('0,0.00') }},
            { "width": "20%",className: "text-left",data: function(a) { return moment(a.date_vat_expire).format('DD-MM-Y') }},
            { "width": "20%",className: "text-left",data: "license_plate" },
            { "width": "10%",className: "text-left",data: function(a) { return `<a href="/master-data/delivery_car/update/${a.master_truck_id}" class="btn btn-dark"><i class="fas fa-cog"></i> จัดการ</a>`; }}
        ]
    });

    $('#btn_search').click(function(){
        oTable.draw();
    });

    $( "#master_truck_type_id" ).change(function() {
        oTable.draw();
    });

    $('#btn_reset').click(function()
    {
        $("#master_truck_type_id").val($("#master_truck_type_id option:first").val());
        $('#search').val('');
        oTable.draw();
    });
</script>
@endsection