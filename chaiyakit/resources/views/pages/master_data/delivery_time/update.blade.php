@extends('layouts.app')

@section('content')
<div class="form-main" id="delivery_time">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-6">
                <h2>แก้ไขข้อมูลเวลาจัดส่ง</h2>
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
                        <li class="breadcrumb-item"><a href="{{ route('delivery_time') }}">จัดการข้อมูลเวลาจัดส่ง</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">จัดการข้อมูล</li>
                    </ol>
                </nav>
            </div>
        </div>
        <hr>
        <form id="form_delivery_time" method="PUT">
            @csrf
            <div class="row justify-content-center mt-5 form">
                <div class="form-group col-xs-3 col-md-3 mr-3">
                    <div class="row">
                        <label for="exampleInputEmail1">ชื่อรอบจัดส่ง :</label> <span
                            style="color: red; font-size: 15px;">*</span>
                    </div>
                    <div class="row">
                        <input type="text" class="form-control" name="name" value=" {{ $data->name }}">
                    </div>
                </div>
                <div class="form-group col-xs-3 col-md-3">
                    <div class="row">
                        <label for="exampleInputPassword1">เวลาจัดส่ง : <span
                                style="color: red; font-size: 15px;">*</span></label>
                    </div>
                    <div class="row">
                        <div class="form-group mr-2">
                            <select name="round_time_h" id="round_time_h" class="form-control"
                                value=" {{ $data->name }}">
                                <option selected value="{!! Str::limit($data->round_time, 2, '') !!}"
                                    label="{!! Str::limit($data->round_time, 2, '') !!}"></option>
                                <?php 
                           $start = "00";
                           $end = "23";                
                             for($i=$start; $i<=$end; $i++){                  
                                echo '<option value='.$i.'>'.$i.'</option>';
                             }
                       ?>
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <select name="round_time_m" id="round_time_m" class="form-control">
                                <option selected name="round_time_h"
                                    value="{!! Str::substr($data->round_time, $substr = 3 ,$end = 2) !!}"
                                    label="{!! Str::substr($data->round_time, $substr = 3 ,$end = 2) !!}"></option>
                                <?php 
                           $start = "00";
                           $end = "59";                
                             for($i=$start; $i<=$end; $i++){                  
                                echo '<option value='.$i.'>'.$i.'</option>';
                             }
                       ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12 text-center mt-5">
                    <a href="{{ route('delivery_time') }}" type="button" class="btn btn-danger text-white">ยกเลิก</a>
                    <button type="submit" class="btn btn-success">ยืนยันทำรายการ</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')

<script type="text/javascript">
    $('#timepicker').timepicker({
        uiLibrary: 'bootstrap4'
    });

    let master_round_id = '{{ $data->master_round_id }}';

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
                url: `{{ url('api/master-data/delivery_time/${master_round_id}') }}`,
                type: "DELETE",
            }).done(function(res) {
                if (res.status == true) {
                    Swal.fire('สำเร็จ!',res.message,'success').then(() => (window.location = "/master-data/delivery_time"))
                } else {
                    Swal.fire({icon: 'warning',title: 'เตือน!',text: res.message})
                }
            });
        }
        });
        return false;
    });

        $("#form_delivery_time").submit(function(event) {
        let time = $('#round_time_h option:selected').val() +':'+ $('#round_time_m option:selected').val()

        $.ajax({
            url: `{{ url('api/master-data/delivery_time/${master_round_id}') }}`,
            type: "PUT",
            data: $('form#form_delivery_time').serialize() + `&time=${time}`,
        }).done(function(res) {
            if (res.status == true) {
                Swal.fire('สำเร็จ!',res.message,'success').then(() => (window.location = "/master-data/delivery_time"))
            } else {
                Swal.fire({icon: 'warning',title: 'เตือน!',text: res.message})
            }
        });
        event.preventDefault();
    });


</script>
@endsection