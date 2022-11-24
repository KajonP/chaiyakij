@extends('layouts.app')

@section('content')
<div class="form-main" id="product_info" class="table">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-xs-6 col-md-6 main-title">
                <h2>จัดการข้อมูลภาษี</h2>
            </div>
            <div class="col-12 col-md-6 col-xs-6 main-title" align="right">
            <a href="{{ url('master-data/vat/add') }}" class="btn btn-lg btn-danger"><i class="fa fa-plus"></i> สร้างใหม่</a>
            </div>
        </div>
        <hr>
    
        <div class="row mt-5">
            <div class="col-12 col-xs-12 form-content mb-3 table-responsive">
                <table class="table table-striped" id="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ภาษี (%)</th>
                            <th>ค่าเริ่มต้น</th>
                            <th>แก้ไขโดย</th>
                            <th>วันที่แก้ไข</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                </table>
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
	            "order": [],
	            "searching": false,
	            processing: true,
	            serverSide: true,
	            ajax: {
	                url : "{{ url('api/master-data/vat') }}",
	                method : 'GET',
		            data: function(d) 
	                { d._token = "{{ csrf_token() }}";
	                  d.search_type = $('#search_type').val();
	                 }
	            },
                columns: [{ data: function(a,b,c,d){ return (oTable.page.info().start+d.row+1); } ,
                className: "text-left", name: 'master_vat_id'},
						  {className: "text-left", data: 'vat'},
                          {  data: 'status_text'},
                          {  className: "text-center", data: 'update_name'},
                          {  className: "text-center", data: 'updated_date'},
                          {
                            className: "text-right",
            data: function(a) {
                return `<a href="/master-data/vat/vat_manage/${a.master_vat_id}" class="btn btn-dark"><i class="fas fa-cog"></i> จัดการ</a>`;
              
            }
            }
                          ],
	            });
</script>
@endsection