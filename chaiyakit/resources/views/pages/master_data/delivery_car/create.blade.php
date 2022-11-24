@extends('layouts.app')

@section('content')
<div class="form-main" id="delivery_car">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2>สร้างข้อมูลรถจัดส่งสินค้า</h2>
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a
                                onclick="window.history.go(-1); return false;">จัดการข้อมูลรถจัดส่งสินค้า</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">สร้างข้อมูลรถจัดส่งสินค้า</li>
                    </ol>
                </nav>
            </div>
        </div>
        <hr>
        <form id="form_delivery_car" method="POST">
            @csrf
            <div class="row">
                <div class="form-group  col-xs-6 col-md-4">
                    <label for="exampleInputEmail1">ประเภทรถ :</label> <span style="color: red; font-size: 15px;">*</span>
                    <select class="form-control" name="master_truck_type_id">
                        <option selected disabled>เลือกประเภทรถ</option>
                        @foreach ($master_truck_type as $item)
                        <option value="{{$item->master_truck_type_id}}">{{$item->type}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="form-group col-xs-6  col-md-4">
                    <label for="exampleInputEmail1">บรรทุกน้ำหนัก (ตัน) :</label> <span style="color: red; font-size: 15px;">*</span>
                    <input type="text" class="form-control number" name="weight">
                </div>
                <div class="form-group  col-xs-6 col-md-4 ">
                    <label for="exampleInputEmail1">วันที่หมดอายุภาษีทะเบียนรถ :</label> <span style="color: red; font-size: 15px;">*</span>
                    <input type="text" class="form-control" id="datepicker" name="date_vat_expire">
                </div>
                <div class="form-group  col-xs-6 col-md-4 ">
                    <label for="exampleInputEmail1">ป้ายทะเบียน :</label> <span style="color: red; font-size: 15px;">*</span>
                    <input type="text" class="form-control" name="license_plate">
                </div>
                <div class="col-md-12 col-xs-6 text-center mt-5">
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
    $('#datepicker').datepicker({
        uiLibrary: 'bootstrap4',
        format:'dd/mm/yyyy',
    });
    var weight = new Cleave('.number', {
        numeral: true,
        numeralThousandsGroupStyle: 'thousand'
    });

    $("#form_delivery_car").submit(function(event) {
        $.ajax({
            url: "{{ url('api/master-data/delivery_car') }}",
            type: "POST",
            data: $(this).serialize() + `&weight=${weight.getRawValue()}`,
        }).done(function(res) {
            if (res.status == true) {
                Swal.fire('สำเร็จ!',res.message,'success').then(() => (window.location = "/master-data/delivery_car"))
            } else {
                Swal.fire({icon: 'warning',title: 'เตือน!',text: res.message})
            }
        });
         event.preventDefault();
    });
</script>
@endsection