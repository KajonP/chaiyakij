@extends('layouts.app')

@section('content')
<div class="form-main" id="car_type_information">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2>สร้างข้อมูลประเภทรถ</h2>
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a
                                onclick="window.history.go(-1); return false;">จัดการข้อมูลประเภทรถ</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">สร้างข้อมูลประเภทรถ</li>
                    </ol>
                </nav>
            </div>
        </div>
        <hr>
        <form id="form_car_type_information" method="POST">
            @csrf
            <div class="row justify-content-center mt-5">
                <div class="form-group col-4">
                    <label for="">ประเภทรถ : </label> <span style="color: red; font-size: 16px;">*</span>
                    <input type="text" class="form-control" name="type">
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
    $("#form_car_type_information").submit(function(event) {
        $.ajax({
            url: "{{ url('api/master-data/car_type_information') }}",
            type: "POST",
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