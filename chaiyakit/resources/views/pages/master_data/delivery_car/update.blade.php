@extends('layouts.app')

@section('content')
<div class="form-main" id="delivery_car">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-6">
                <h2>แก้ไขข้อมูลรถจัดส่งสินค้า</h2>
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
                                onclick="window.history.go(-1); return false;">จัดการข้อมูลรถจัดส่งสินค้า</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">แก้ไขข้อมูลรถจัดส่งสินค้า</li>
                    </ol>
                </nav>
            </div>
        </div>
        <hr>
        <form id="form_delivery_car" method="PUT">
            @csrf
            <div class="row">
                <div class="form-group col-md-4 col-xs-6">
                    <label for="exampleInputEmail1">ประเภทรถ :</label>
                    <select class="form-control" name="master_truck_type_id">
                        @foreach ($master_truck_type as $item)
                        <option value="{{$item->master_truck_type_id}}"
                            {{ $item->master_truck_type_id == $data->master_truck_type_id ? 'selected' : '' }}>
                            {{$item->type}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="form-group col-md-4 col-xs-6">
                    <label for="exampleInputEmail1">บรรทุกน้ำหนัก (ตัน) :</label>
                    <input type="text" class="form-control number" name="weight" value="{{ $data->weight }}">
                </div>
                <div class="form-group col-md-4 col-xs-6">
                    <label for="exampleInputEmail1">วันที่หมดอายุภาษีทะเบียนรถ :</label>
                    <input type="text" class="form-control" id="datepicker" name="date_vat_expire">
                </div>
                <div class="form-group col-md-4 col-xs-6">
                    <label for="exampleInputEmail1">ป้ายทะเบียน :</label>
                    <input type="text" class="form-control" name="license_plate" value="{{ $data->license_plate }}">
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
    let master_truck_id = '{{ $data->master_truck_id }}';
        
    var weight = new Cleave('.number', {
        numeral: true,
        numeralThousandsGroupStyle: 'thousand'
    });

    let date_vat_expire = '{{ $data->date_vat_expire ?? 0 }}'
    console.log(date_vat_expire);
    
    $('#datepicker').datepicker({
        uiLibrary: 'bootstrap4',
        format:'dd/mm/yyyy',
        value: date_vat_expire == 0 ? '' : moment(date_vat_expire).format('DD/MM/Y'),
    });

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
                url: `{{ url('api/master-data/delivery_car/${master_truck_id}') }}`,
                type: "DELETE",
            }).done(function(res) {
                if (res.status == true) {
                    Swal.fire('สำเร็จ!',res.message,'success').then(() => (window.location = "/master-data/delivery_car"))
                } else {
                    Swal.fire({icon: 'warning',title: 'เตือน!',text: res.message})
                }
            });
        }
        });
        return false;
    });

    $("#form_delivery_car").submit(function(event) {
        $.ajax({
            url: `{{ url('api/master-data/delivery_car/${master_truck_id}') }}`,
            type: "PUT",
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