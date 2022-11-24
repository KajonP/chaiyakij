@extends('layouts.app')
@section('content')
<div class="form-main" id="table-font">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-6">
                <h2>จัดการข้อมูลเวลาจัดส่ง</h2>
            </div>
            <div class="col-12 col-md-6 text-right">
                <a href="{{ route('delivery_time.create') }}" class="btn btn-lg btn-danger"><i class="fa fa-plus"></i>
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
            <div class="col-5">
                <button type="button" class="btn btn-secondary" id="btn_reset">
                    <i class="fas fa-redo"></i>
                    <span>รีเซ็ต</span>
                </button>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12 form-content mb-3 table-responsive">
                <table class="col-12 table table-striped dt-responsive nowrap" style="width:100%" id="table">
                    <thead>
                        <tr>
                            <th class="table-row">#</th>
                            <th class="table-row">ชื่อรอบจัดส่ง</th>
                            <th class="table-row">เวลาจัดส่ง</th>
                            <th class="table-row">จัดการ</th>
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
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('api/master-data/delivery_time') }}",
            method: "GET",
            data: function(d) {
            d._token = "{{ csrf_token() }}";
            d.search = $("#search").val();
            }
        },
        columns: [
            {
            data: function(a, b, c, d) {
                return oTable.page.info().start + d.row + 1;
            },
               "width": "25%", className: "table-row text-left", name: "master_round_id"
            },
            {  "width": "25%", className: "table-row", data: "name" },
            {  "width": "40%", className: "table-row ", data: function(a) {return moment(a.round_time, "HH:mm:ss").format('HH:mm')}
             },
            {
                "width": "10%",className:"text-left",data: function(a) {
                return `<a href="/master-data/delivery_time/update/${a.master_round_id}" class="btn btn-dark"><i class="fas fa-cog"></i> จัดการ</a>`;
            }
            }
        ]
        });

    $('#btn_search').click(function(){
        oTable.draw();
    });

    $('#btn_reset').click(function()
    {
        $('#search').val('');
        oTable.draw();
    });

</script>
@endsection