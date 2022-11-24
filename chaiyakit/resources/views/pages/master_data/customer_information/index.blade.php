@extends('layouts.app')

@section('content')

<div class="form-main" >
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-xs-4 col-md-6 main-title">
                <h2>จัดการข้อมูลลูกค้า</h2>
            </div>
            <div class="col-12 col-xs-4 col-md-6 text-right">
                <a href="{{ route('customer_information.create_account') }}" class="btn btn-lg btn-danger"><i class="fa fa-plus"></i> สร้างใหม่</a>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="input-group col-xs-4 col-md-4">
                <input class="form-control py-2" type="search" value="" id="search" placeholder="ค้นหา...">
                <span class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" id="btn_search">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>

            <div class="col-xs-5 col-md-5">
                <button type="button" class="btn btn-secondary" id="btn_reset">
                    <i class="fas fa-redo"></i>
                    <span>รีเซ็ต</span>
                </button>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-xs-12 col-md-12 form-content mb-3 table-responsive">
                <table class="table table-striped" id="table" style="width:100%">
                    <thead class="table-header">
                        <tr>
                            <th >#</th>
                            <th >ชื่อร้านค้า</th>
                            {{-- <th >ชื่อหน่วยงาน</th> --}}
                            <th >เลขประจำตัวภาษีอากร</th>
                            <th >เบอร์โทรศัพท์</th>
                            <th >จัดการ</th>
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
            url: "{{ url('api/master-data/customer_information') }}",
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
            "width": "10%", className: "text-left table-row", name: "master_merchant_id"
            },
            { "width": "20%",className: "text-left table-row",data: "name_merchant" },
            // { "width": "20%",className: "text-left table-row",data: "name_department" },
            { "width": "20%",className: "text-left table-row",data: "tax_number" },
            { "width": "20%",className: "text-left table-row",data: "phone_number" },


            {
            "width": "10%",className: "text-left",
            data: function(a) {
                return `<a href="/master-data/customer_information/update/${a.master_merchant_id}" class="btn btn-dark"><i class="fas fa-cog"></i> จัดการ</a>`;
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
