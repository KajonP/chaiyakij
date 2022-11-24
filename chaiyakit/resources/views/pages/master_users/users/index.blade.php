@extends('layouts.app')

@section('content')
<div class="form-main" id="delivery_car">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-6">
                <h2>จัดการข้อมูลผู้ดูแลระบบ</h2>
            </div>
            <div class="col-12 col-md-6 text-right">
                <a href="{{ route('users.add') }}" class="btn btn-lg btn-danger"><i class="fa fa-plus"></i> สร้างใหม่</a>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="input-group col-md-4">
                <input class="form-control py-2" type="search" value="" id="search_key" name="search_key"
                    placeholder="ค้นหา...">
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
                <table class="table table-striped dt-responsive nowrap" style="width:100%" id="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ชื่อ</th>
                            <th>บัญชีผู้ใช้งาน (Email)</th>
                            <th>สิทธ์ใช้งาน</th>
                            <th>สถานะใช้งาน</th>
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
   var oTable = $('#table').DataTable({
	       		"targets": 'no-sort',
	            "bSort": false,
	            "order": [],
	            "searching": false,
	            processing: true,
	            serverSide: true,
	            ajax: {
	                url : "{{ url('api/master-users/users') }}",
	                method : 'POST',
		            data: function(d) 
	                { d._token = "{{ csrf_token() }}";
	                  d.search_key = $('#search_key').val();
	                 }
	            },
	            columns: [{ data: function(a,b,c,d){ return (oTable.page.info().start+d.row+1); } , name: 'id'},
						  { "width": "20%", data: 'name'},
                          { "width": "20%", data: 'email'},
                          { "width": "20%", data: 'role'},
                          { "width": "20%", className: "text-center", data: 'status_text'},
                          {
            "width": "10%",
            data: function(a) {
              if(a.status !='02'){
                return `<a href="/master-users/users/users_manage/${a.id}" class="btn btn-dark"><i class="fas fa-cog"></i> จัดการ</a>`;
              }else{
                return '';
              }
               
            }
            }
                          ],
	            });
        $('#btn_search').click(function(e) 
    	{
    		oTable.draw();
    	});
        $('#btn_reset').click(function(e) 
    	{
    		$('#search_key').val('');
    		
    		oTable.draw();
    	});

</script>
@endsection