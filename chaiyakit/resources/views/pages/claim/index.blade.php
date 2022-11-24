@extends('layouts.app')

@section('content')

<div class="form-main">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-xs-4 col-md-6 main-title">
                <h2>เมนูการคืน / เคลมสินค้า</h2>
            </div>

        </div>
        <hr>
        <div class="row">
            <div class="input-group col-xs-4 col-md-4">
                <input class="form-control py-2" type="search" value="" id="search" placeholder="ค้นหาเลขที่ใบสั่งซื้อ,ชื่อร้านค้า,หน่วยงาน">
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
                            <th>#</th>
                            <th>เลขที่ใบสั่งซื้อ</th>
                            <th>ชื่อร้านค้า</th>
                            <th>หน่วยงาน</th>
                            <th>จำนวนเงิน</th>
                            <th>จำนวนที่สั่ง</th>
                            <th>ส่งแล้ว</th>
                            <th>ค้างส่ง</th>
                            <th>คืน/จำนวน</th>
                            <th>เคลม/จำนวน</th>
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
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ url('api/claim/claim') }}",
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
              "width": "10%"
            }
            ,
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
                    return numeral(a.sum_order_delivery_item).format("0,0");
                }
            },
            {
              "width": "10%", className: "text-center",
                data: function(a) {
                    return numeral(a.qty_all - a.sum_order_delivery_item).format("0,0");
                }
            },
            { "width": "7%", className: "text-left table-row",
              data: function(a) {
                  return numeral(a.count_01).format("0,0");
              }
            },
            { "width": "7%", className: "text-left table-row",
              data: function(a) {
                  return numeral(a.count_02).format("0,0");
              }
            },
            { "width": "10%", className: "text-left",
              data: function(a) {
                let url_order_claim = '{{ route("claim.list.order_claim", ":id") }}'.replace(
                ":id",
                a.order_id
                );
                let url_list_claim_return = '{{ route("claim.list.claim_return", ":id") }}'.replace(
                ":id",
                a.order_id
                );
                  return `<div class="dropdown  show">
                              <button role="button" type="button" id="dropdownMenuLink" class="btn btn-dark" data-toggle="dropdown">
                          <i class="fas fa-cog"></i> จัดการ
                          </button>
                          <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                              <a class="dropdown-item" href="${url_order_claim}">สร้างรายการคืน / เคลม</a>
                              <a class="dropdown-item" href="${url_list_claim_return}">รายการคืน / เคลม</a>
                          </div></div>`;

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
