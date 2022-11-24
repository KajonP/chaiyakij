@extends('layouts.app')

@section('content')

<div class="form-main" id="product_info">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-6 main-title">
                <h2>จัดการข้อมูลสินค้า</h2>
            </div>
            <div class="col-12 col-md-6 main-title" align="right">
            <a href="{{ route('product_information.create') }}" class="btn btn-lg btn-danger"><i class="fa fa-plus"></i> สร้างใหม่</a>
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
                <table class="table table-striped" id="table"></table>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@section('js')
<script type="text/javascript">

    var oTable = $('#table').DataTable({
     		"targets": 'no-sort',
          "bSort": false,
          "searching": false,
          "processing": true,
          "serverSide": true,
          "order": [[ 1 , "DESC" ]],
          "ajax": {
              url : "{{ url('api/master-data/product_info') }}",
              method : 'GET',
            data: function(d)
              {
                // console.log(d);
                d._token = "{{ csrf_token() }}";
                d.product_name = $('#search').val();
              }
          },
          columns: [
            { title : '#' , data: function(a,b,c,d){ return (oTable.page.info().start+d.row+1); } , name: 'id'},
                ///////////////////////////////
            { title : 'ID' , data: 'id_product_main', name: 'master_product_main.id_product_main', visible: false },
                ///////////////////////////////
				    { title : 'สินค้า' ,"width": "10%",data: 'product_name', "width": "30%",name: 'product_name'},
            { title : 'ประเภทสินค้า' ,data: function(a){
              var str = a.product_type;
              var replace = str.slice(0, -2);
              return (replace);
            } , "width": "30%",name: 'product_type'},
            { title : 'จัดการ' , "width": "10%",className: "text-left",data: function(a) { return `<a href="/master-data/product_information/update/${a.id}" class="btn btn-dark"><i class="fas fa-cog"></i> จัดการ</a>`; }}
          ],
     });


     $('#btn_search').click(function(){
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
