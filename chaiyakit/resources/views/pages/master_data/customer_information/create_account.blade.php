@extends('layouts.app')

@section('content')

<div class="form-main">
    <div class="container-fluid" id="form_customer_information">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <h2 class="h2">เพิ่มข้อมูลลูกค้า</h2>
            </div>
            <div class="col-xs-12 col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a
                                onclick="window.history.go(-1); return false;">จัดการข้อมูลรถลูกค้า</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">เพิ่มข้อมูลลูกค้า</li>
                    </ol>
                </nav>
            </div>
        </div>

        <hr>
        <form id="form_customer_information" method="POST">
            @csrf
            <div class="row mt-5 form">
                <div class="form-group col-xs-3 col-md-3">
                    <label class="required" for="">ชื่อร้านค้า :</label> <span
                        style="color: red; font-size: 15px;">*</span>
                    <input type="text" class="form-control" name="name_merchant">
                </div>
                {{-- <div class="form-group col-xs-3 col-md-3">
                    <label for="">ชื่อหน่วยงาน :</label>
                    <input type="text" class="form-control" name="name_department">
                </div> --}}
                <div class="form-group col-xs-3 col-md-3">
                    <label for="">ละจิจูด :</label>
                    <input type="text" class="form-control" name="latitude">
                </div>
                <div class="form-group col-xs-3 col-md-3">
                    <label for="">ลองจิจูด :</label>
                    <input type="text" class="form-control" name="longitude">
                </div>
                <div class="form-group col-xs-3 col-md-3 ">
                    <label for="">เลขประจำตัวภาษีอากร :</label>
                    <input type="text" class="form-control" maxlength="13" onkeypress="return isNumber(event)"
                        title="กรุณากรอกให้ถูกต้อง" name="tax_number">
                </div>
            </div>
            <div class="row ">

                <div class="form-group col-xs-3 col-md-3">
                    <label for="">เบอร์โทรศัพท์1 :</label>
                    <input type="text" class="form-control" maxlength="10" onkeypress="return isNumber(event)"
                        title="กรุณากรอกเบอร์โทรศัพให้ถูกต้อง" name="phone_number">
                </div>
                <div class="form-group col-xs-3 col-md-3">
                    <label for="">เบอร์โทรศัพท์2 :</label>
                    <input type="text" class="form-control" maxlength="10" onkeypress="return isNumber(event)"
                        title="กรุณากรอกเบอร์โทรศัพให้ถูกต้อง" name="phone_number2">
                </div>
                <div class="form-group col-xs-3 col-md-3">
                    <label for="">เบอร์โทรศัพท์3 :</label>
                    <input type="text" class="form-control" maxlength="10" onkeypress="return isNumber(event)"
                        title="กรุณากรอกเบอร์โทรศัพให้ถูกต้อง" name="phone_number3">
                </div>
                <div class="form-group col-xs-3 col-md-3">
                    <label for="">เบอร์โทรศัพท์4 :</label>
                    <input type="text" class="form-control" maxlength="10" onkeypress="return isNumber(event)"
                        title="กรุณากรอกเบอร์โทรศัพให้ถูกต้อง" name="phone_number4">
                </div>
                <div class="form-group col-xs-3 col-md-3">
                    <label for="">Fax :</label>
                    <input type="text" class="form-control" maxlength="10" onkeypress="return isNumber(event)"
                        title="กรุณากรอกเบอร์โทรศัพให้ถูกต้อง" name="fax">
                </div>
                <div class="form-group col-xs-9 col-md-9">
                    <label for="">Link Google Map :</label>
                    <input type="text" class="form-control" name="link_google_map">
                </div>
            </div>
            <div class="row ">
                <div class="form-group col-xs-12 col-md-12">
                    <label for="">ที่อยู่ :</label>
                    <textarea type="text" class="form-control" name="address"></textarea>
                </div>

            </div>
    </div>



    <div class="col-12 text-center mt-5">
        <a onclick="window.history.go(-1); return false;" type="button" class="btn btn-danger text-white">ยกเลิก</a>
        <button type="submit" class="btn btn-success">ยืนยันทำรายการ</button>
    </div>
    </form>
</div>
<div id="map_canvas"></div>

</div>

@endsection

@section('js')

<script type="text/javascript">
    $("#form_customer_information").submit(function(event) {
        $.ajax({
            url: "{{ url('api/master-data/customer_information') }}",
            type: "POST",
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



</script>

@endsection
