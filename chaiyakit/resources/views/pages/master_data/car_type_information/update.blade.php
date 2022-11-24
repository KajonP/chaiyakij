@extends('layouts.app')

@section('content')
<div class="form-main" id="car_type_information">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-6">
                <h2>แก้ไขข้อมูลประเภทรถ</h2>
            </div>
            <div class="col-12 col-md-6 text-right">
                <h5><a href="" id="delete" class="text-danger border-bottom border-danger">
                        <i class="fa fa-trash"></i>
                        ต้องการลบข้อมูลนี้
                    </a></h5>
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a
                                onclick="window.history.go(-1); return false;">จัดการข้อมูลประเภทรถ</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">แก้ไขข้อมูลประเภทรถ</li>
                    </ol>
                </nav>
            </div>
        </div>
        <hr>
        <form id="form_car_type_information" method="PUT">
            @csrf
            <div class="row justify-content-center mt-5">
                <div class="form-group col-4">
                    <label for="exampleInputEmail1">ประเภทรถ : </label> <span style="color: red; font-size: 16px;">*</span>
                    <input type="text" class="form-control" name="type" value="{{ $data->type }}">
                </div>
                <div class="col-12 text-center mt-5">
                    <a onclick="window.history.go(-1); return false;" type="button"
                        class="btn btn-danger text-white">ยกเลิก</a>
                    <button type="submit" class="btn btn-success">ยืนยันทำรายการ</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')

<script type="text/javascript">
    let master_truck_type_id = '{{ $data->master_truck_type_id }}';
    // Delete Data
    $( "#delete" ).click(function() {
        Swal.fire({
        title: 'ต้องการลบข้อมูลนี้ ?',
        text: "",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'ยกเลิก',
        confirmButtonText: 'ยืนยันการลบ',
        reverseButtons: true
        }).then((result) => {
        if (result.value) {
            $.ajax({
                url: `{{ url('api/master-data/car_type_information/${master_truck_type_id}') }}`,
                type: "DELETE",
            }).done(function(res) {
                if (res.status == true) {
                    Swal.fire('สำเร็จ!',res.message,'success').then(() => (window.location = "/master-data/car_type_information"))
                } else {
                    Swal.fire({icon: 'warning',title: 'เตือน!',text: res.message})
                }
            });
        }
        });
        return false;
    });

    // Update Data
    $("#form_car_type_information").submit(function(event) {
        $.ajax({
            url: `{{ url('api/master-data/car_type_information/${master_truck_type_id}') }}`,
            type: "PUT",
            data: $(this).serialize()
        }).done(function(res) {
            if (res.status == true) {
                Swal.fire('สำเร็จ!',res.message,'success').then(() => (window.location = "/master-data/car_type_information"))
            } else {
                Swal.fire({icon: 'warning',title: 'เตือน!',text: res.message})
            }
        });
        event.preventDefault();
    });
</script>
@endsection