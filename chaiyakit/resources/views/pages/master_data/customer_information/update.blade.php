@extends('layouts.app')

@section('content')

<div class="form-main" >
    <div class="container-fluid" id="table-font">
        <div class="row">
            <div class="col-xs-12 col-md-12" >
                <h2 class="h2">จัดการข้อมูลลูกค้า</h2>
            </div>
            <div class="col-xs-12 col-md-12 text-right">
                <h5><a href="" id="delete" class="text-danger border-bottom border-danger">
                        <i class="fa fa-trash"></i>
                        ต้องการลบข้อมูลนี้
                    </a></h5>
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('customer_information') }}">จัดการข้อมูลลูกค้า</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">แก้ไขข้อมูลลูกค้า</li>
                    </ol>
                </nav>
            </div>

        </div>
        <hr>
        <form id="form_customer_information" method="PUT">
            @csrf
        <div class="row mt-5 form" >
            <div class="form-group col-xs-3 col-md-3">
                <label for="exampleInputEmail1">ชื่อร้านค้า :</label> <span style="color: red; font-size: 16px;">*</span>
                <input type="text" class="form-control" name="name_merchant" disabled value="{{ $data->name_merchant }}">
            </div>
            {{-- <div class="form-group col-xs-3 col-md-3">
                <label for="exampleInputEmail1">ชื่อหน่วยงาน :</label>
                <input type="text" class="form-control" name="name_department" value="{{ $data->name_department }}">
            </div> --}}
            <div class="form-group col-xs-3 col-md-3">
                <label for="">ละจิจูด :</label>
                <input type="text" class="form-control"  name="latitude" value="{{ $data->latitude }}">
            </div>
            <div class="form-group col-xs-3 col-md-3">
                <label for="">ลองจิจูด :</label>
                <input type="text" class="form-control"  name="longitude" value="{{ $data->longitude }}">
            </div>
            <div class="form-group col-xs-3 col-md-3">
                <label for="exampleInputEmail1">เลขประจำตัวภาษีอากร :</label>
                <input type="text" class="form-control" name="tax_number"maxlength="13" onkeypress="return isNumber(event)" title="กรุณากรอกห้ถูกต้อง" value="{{ $data->tax_number }}">
            </div>
        </div>
        <div class="row ">

            <div class="form-group col-xs-3 col-md-3">
                <label for="exampleInputEmail1">เบอร์โทรศัพท์1 :</label>
                <input type="text" class="form-control" name="phone_number" maxlength="10" onkeypress="return isNumber(event)" title="กรุณากรอกเบอร์โทรศัพให้ถูกต้อง" value="{{ $data->phone_number }}">
            </div>
            <div class="form-group col-xs-3 col-md-3">
                <label for="exampleInputEmail1">เบอร์โทรศัพท์2 :</label>
                <input type="text" class="form-control" name="phone_number2" maxlength="10" onkeypress="return isNumber(event)" title="กรุณากรอกเบอร์โทรศัพให้ถูกต้อง" value="{{ $data->phone_number2 }}">
            </div>
            <div class="form-group col-xs-3 col-md-3">
                <label for="exampleInputEmail1">เบอร์โทรศัพท์3 :</label>
                <input type="text" class="form-control" name="phone_number3" maxlength="10" onkeypress="return isNumber(event)" title="กรุณากรอกเบอร์โทรศัพให้ถูกต้อง" value="{{ $data->phone_number3 }}">
            </div>
            <div class="form-group col-xs-3 col-md-3">
                <label for="exampleInputEmail1">เบอร์โทรศัพท์4 :</label>
                <input type="text" class="form-control" name="phone_number4" maxlength="10" onkeypress="return isNumber(event)" title="กรุณากรอกเบอร์โทรศัพให้ถูกต้อง" value="{{ $data->phone_number4 }}">
            </div>
            <div class="form-group col-xs-3 col-md-3">
                <label for="exampleInputEmail1">fax :</label>
                <input type="text" class="form-control" name="fax" maxlength="10" onkeypress="return isNumber(event)" title="กรุณากรอกเบอร์โทรศัพให้ถูกต้อง" value="{{ $data->fax }}">
            </div>
            <div class="form-group col-xs-9 col-md-9">
                <label for="">Link Google Map :</label>
                <input type="text" class="form-control"  name="link_google_map" value="{{ $data->link_google_map }}">
            </div>
        </div>

        <div class="row ">
            <div class="form-group col-xs-12 col-md-12">
                <label for="exampleInputEmail1">ที่อยู่ :</label>
                <textarea type="text" class="form-control" name="address"><?php echo $data->address ?></textarea>
            </div>

        </div>

        <div class="col-12 text-center mt-5">
            <a onclick="window.history.go(-1); return false;" type="button" class="btn btn-danger text-white">ยกเลิก</a>
            <button type="submit" class="btn btn-success">ยืนยันทำรายการ</button>
        </div>
    </form>
    </div>
</div>


@endsection

@section('js')

<script type="text/javascript">

let master_merchant_id = '{{ $data->master_merchant_id }}';

        $("#form_customer_information").submit(function(event) {
        $.ajax({
            url: `{{ url('api/master-data/customer_information/${master_merchant_id}') }}`,
            type: "PUT",
            data: $('form#form_customer_information').serialize()
        }).done(function(res) {
            if (res.status == true) {
                Swal.fire('สำเร็จ!',res.message,'success').then(() => (window.location = "/master-data/customer_information"))
            } else {
                Swal.fire({icon: 'warning',title: 'เตือน!',text: res.message})
            }
        });
        event.preventDefault();
    });

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
                url: `{{ url('api/master-data/customer_information/${master_merchant_id}') }}`,
                type: "DELETE",
            }).done(function(res) {
                if (res.status == true) {
                    Swal.fire('สำเร็จ!',res.message,'success').then(() => (window.location = "/master-data/customer_information"))
                } else {
                    Swal.fire({icon: 'warning',title: 'เตือน!',text: res.message})
                }
            });
        }
        });
        return false;
    });

</script>
@endsection
