@extends('layouts.app')

@section('content')
<div class="form-main" id="page-delivery-detail">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('delivery') }}">เมนูการจัดส่งสินค้า</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('delivery.list') }}">การจัดส่งสินค้า</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('delivery.list.delivery',$order_id) }}">รายการจัดส่ง</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">จัดส่งสินค้าที่ค้าง</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-6 align-self-end">
                <h2>การขนส่งสินค้าเลขที่ : {{ $data['Order']->order_number .'/'. $data['OrderDelivery']->order_delivery_number }}</h2>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="form-group col-md-3">
                <label for="" class="label-blue">วันที่สั่งซื้อ :</label>
                <br>
                <span>{{ formatDateThat($data['Order']->created_date) }}</span>
            </div>
            <div class="form-group col-md-3">
                <label for="" class="label-blue">ชื่อผู้จัดทำใบสั่งซื้อ :</label>
                <br>
                <span>{{ $data['Order']->admin_name }}</span>
            </div>
            <div class="form-group col-md-3">
                <label for="" class="label-blue">ร้านค้า :</label>
                <br>
                <span>{{ $data['Order']->name_merchant }}</span>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-3">
                <label for="" class="label-blue">หน่วยงาน :</label>
                <br>
                <span>{{ $data['Order']->department_name }}</span>
            </div>
            <div class="form-group col-md-3">
                <label for="" class="label-blue">เลขประจำตัวภาษีอากร :</label>
                <br>
                <span>{{ $data['Order']->tax_number }}</span>
            </div>
            <div class="form-group col-md-6">
                <label for="" class="label-blue">ที่อยู่ :</label>
                <br>
                <span>{{ $data['Order']->status_departmen == "00" ? $data['Order']->address : $data['Order']->address_department }}</span>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-3">
                <label for="" class="label-blue">เบอร์โทรศัพท์ :</label>
                <br>
                <span>{{ $data['Order']->phone_number }}</span>
            </div>
            <div class="form-group col-md-3">
                <label for="" class="label-blue">เบอร์โทรศัพท์ (หน่วยงาน) :</label>
                <br>
                <span>{{ $data['Order']->phone_department }}</span>
            </div>
            <div class="form-group col-md-6">
                <label for="" class="label-blue">จำนวนเงินรวมทั้งสิ้น (บาท) :</label>
                <br>
                <span>{{ number_format($data['Order']->grand_total,2) }}</span>
            </div>
        </div>
        <div class="row style2" style="background: #f4f4f5;padding-top: 1rem;">
            <div class="form-group col-md-3">
                <label for="">จำนวนสินค้าที่จัดส่งในรอบนี้ :</label>
                <br>
                <span class="c-0095dd">{{ number_format($data['OrderDelivery']->sum_order_delivery_item) }}</span>
            </div>
            <div class="form-group col-md-3">
                <label for="">วันที่ส่ง :</label>
                <br>
                <span class="c-0095dd">{{ formatDateThatNoTime($data['OrderDelivery']->date_schedule) }}</span>
            </div>
            <div class="form-group col-md-4">
                <label for="">ประเภทรถ :</label>
                <br>
                <span
                    class="c-0095dd">{{ $data['OrderDelivery']->type .' - '. $data['OrderDelivery']->license_plate  }}</span>
            </div>
            <div class="form-group col-md-2 text-center">
                <label for="">สถานะการจัดส่ง :</label>
                <br>
                @switch($data['OrderDelivery']->status)
                @case('00')
                <span class="badge-pill text-white py-1" style="background-color:#495057">ยังไม่จัดส่ง</span>
                @break
                @case('01')
                <span class="badge-pill text-white py-1" style="background-color:#FF871E">เตรียมการจัดส่ง</span>
                @break
                @case('02')
                <span class="badge-pill text-white py-1" style="background-color:#FFC627">กำลังจัดส่ง</span>
                @break
                @case('03')
                <span class="badge-pill text-white py-1" style="background-color:#EE6C98">เลื่อนการจัดส่ง</span>
                @break
                @case('04')
                <span class="badge-pill text-white py-1" style="background-color:#EE6C98">เครม</span>
                @break
                @case('05')
                <span class="badge-pill text-white py-1" style="background-color:#27C671">จัดส่งสำเร็จ</span>
                @break
                @endswitch
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12 form-content mb-3 table-responsive">
                <table class="table table-striped dt-responsive nowrap" style="width:100%" id="table">
                    <thead>
                        <tr class="text-center">
                            <td>#</td>
                            <th>สินค้า</th>
                            <th>ประเภทสินค้า</th>
                            <th>ขนาดความยาว</th>
                            <th>หมายเหตุ</th>
                            <th>น้ำหนักรวม</th>
                            <th>ส่งสินค้า</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['OrderDeliveryItem'] as $key => $item)
                        <tr class="text-center">
                            <td>{{ $key+1 }}</td>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->product_type_name }}</td>
                            <td>{{ $item->product_size_name_text }}</td>


                            <td>{{ $item->remark }}</td>
                            <td>{{ $item->product_weight_text }}
                            </td>
                            </td>
                            <td class="c-0095dd">{{ $item->delivery_item_qty }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr>
                <form id="form_delivery_send_confirm" method="POST">
                    <div class="row justify-content-center mt-5">
                        <div class="col-5">
                            <div class="form-group row">
                                <label class="col-5 title-1 text-right">ยืนยันการจัดส่งสินค้า :</label>
                                <div class="col-7">
                                    <select class="form-control" name="status" id="status">
                                        <option value='' selected disabled hidden>- เลือก -</option>
                                        <option value='01'>ยืนยันการจัดส่ง</option>
                                        <option value='02'>เลื่อนการจัดส่ง</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center mt-5">
                        <div class="col-5">
                            <div class="row">
                                <div class="col-5 text-right">
                                    <a href="{{ route('delivery.list.delivery',$order_id) }}" type="button"
                                        class="btn btn-danger text-white">ยกเลิก</a>
                                </div>
                                <div class="col-7">
                                    <button type="submit" class="btn btn-success">ยืนยันทำรายการ</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal ทำการเลื่อนการจัดส่ง-->
    <div class="modal fade" id="changeSendModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="modal_change_send" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">ทำการเลื่อนการจัดส่ง</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-center">
                            <div class="col-10">
                                <textarea class="form-control" rows="4" name="remark"
                                    placeholder="ระบุเหตุผลการเลื่อนการจัดส่ง..."></textarea>
                                <input type="hidden" name="order_id" value="{{ $data['Order']->order_id }}">
                                <input type="hidden" name="order_delivery_id"
                                    value="{{ $data['OrderDelivery']->order_delivery_id }}">
                                <input type="hidden" name="truck_schedule_id"
                                    value="{{ $data['OrderDelivery']->truck_schedule_id }}">
                                <input type="hidden" name="master_merchant_id"
                                    value="{{ $data['Order']->master_merchant_id }}">
                            </div>
                            <hr class="col-12">
                            <div class="col-10 my-3">
                                <h5 class="label-black">และจะทำการจัดส่งอีกครั้งในวันที่</h5>
                            </div>
                            <div class="form-group col-md-5">
                                <label class="label-black">วันที่จัดส่ง :</label>
                                <input type="text" class="form-control date" name="date_send" id="datepicker"
                                    value="" />
                            </div>
                            <div class="form-group col-md-5">
                                <label class="label-black">รอบจัดส่ง :</label>
                                <select class="form-control" name="round">
                                    <option value='' selected disabled hidden>- เลือก -</option>
                                    @foreach ($data['MasterRound'] as $item)
                                    <option value="{{$item->master_round_id}}">
                                        {{ $item->name .' เวลา '. $item->round_time}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-5">
                                <label class="label-black">ประเภทรถ :</label>
                                <select class="form-control" name="truck_type" id="truck_type_modal_change_send">
                                    <option value='' selected disabled hidden>- เลือก -</option>
                                    @foreach ($data['MasterTruckType'] as $item)
                                    <option data-type-name="{{$item->type}}" value="{{$item->master_truck_type_id}}">
                                        {{ $item->type. ' (จำนวน : '. $item->count .' คัน)'}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-5" id="select_license_change_plate">
                                <label class="label-black">ป้ายทะเบียน :</label>
                                <select class="form-control" name="license_plate" id="license_plate_modal_change_send"
                                    disabled>
                                    <option value='' selected disabled hidden>- เลือก -</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-success"
                            id="btn_submit_modal_change_send">ยืนยันทำรายการ</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal ยืนยันการจัดส่งสินค้า-->
    <div class="modal fade" id="confirmSendModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="modal_confirm_send" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">ยืนยันการจัดส่งสินค้า</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-center">
                            <div class="form-group col-md-10">
                                <label class="label-black">ที่อยู่ในการจัดส่ง :</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="edit_address"
                                        name='edit_address' value="true">
                                    <label class="custom-control-label" for="edit_address">แก้ไขที่อยู่</label>
                                </div>
                                <textarea class="form-control" rows="4" name="address" id="address" readonly
                                    placeholder="ระบุที่อยู่ในการจัดส่ง...">{{ $data['Order']->status_departmen == "00" ? $data['Order']->address : $data['Order']->address_department }}</textarea>
                                <input type="hidden" name="order_id" value="{{ $data['Order']->order_id }}">
                                <input type="hidden" name="order_delivery_id"
                                    value="{{ $data['OrderDelivery']->order_delivery_id }}">
                                <input type="hidden" name="truck_schedule_id"
                                    value="{{ $data['OrderDelivery']->truck_schedule_id }}">
                                <input type="hidden" name="master_merchant_id"
                                    value="{{ $data['Order']->master_merchant_id }}">
                            </div>
                            <hr class="col-12">
                            <div class="form-group col-md-5">
                                <label class="label-black">วันที่จัดส่ง :</label>
                                <input type=" text" class="form-control date" name="date_send" id="datepicker"
                                    value="{{ date('d-m-Y', strtotime($data['OrderDelivery']->date_schedule)) }}"
                                    disabled />
                            </div>
                            <div class="form-group col-md-5">
                                <label class="label-black">รอบจัดส่ง :</label>
                                <select class="form-control" name="round" disabled>
                                    <option value='' selected disabled hidden>- เลือก -</option>
                                    @foreach ($data['MasterRound'] as $item)
                                    <option value="{{$item->master_round_id}}"
                                        {{($item->master_round_id == $data['OrderDelivery']->master_round_id) ? 'selected' : '' }}>
                                        {{ $item->name .' เวลา '. $item->round_time}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-5" >
                                <label class="label-black">ประเภทรถ :</label>
                                <select class="form-control" name="truck_type" id="truck_type_modal_confirm_send">
                                    <option value='' selected disabled hidden>- เลือก -</option>
                                    @foreach ($data['MasterTruckType'] as $item)
                                    <option data-type-name="{{$item->type}}" value="{{$item->master_truck_type_id}}"
                                        {{($item->master_truck_type_id == $data['OrderDelivery']->master_truck_type_id) ? 'selected' : '' }}>
                                        {{ $item->type. ' (จำนวน : '. $item->count .' คัน)'}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-5" id="select_license_plate">
                                <label class="label-black">ป้ายทะเบียน :</label>
                                <select class="form-control" name="license_plate" id="license_plate_modal_confirm_send"
                                    {{ $data['OrderDelivery']->license_plate ? '' : 'disabled'}}>
                                    @if ($data['OrderDelivery']->license_plate)
                                    <option value='{{ $data['OrderDelivery']->master_truck_id }}' selected>
                                        {{ $data['OrderDelivery']->license_plate }}</option>
                                    @else
                                    <option value='' selected disabled hidden>- เลือก -</option>
                                    @endif
                                </select>
                                <a onclick='license_plate_modal_confirm_send(); return false;' href=""><i
                                        class="fas fa-sync"
                                        style="position: absolute;right: -15px;bottom: 12px;"></i></a>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-success"
                            id="btn_submit_modal_confirm_send">ยืนยันทำรายการ</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $('#datepicker').datepicker({
        uiLibrary: 'bootstrap4',
        format: 'dd-mm-yyyy',
        minDate: moment().format('D-M-Y')
    });
    $("#edit_address").click(function(){
        $('input:checkbox').not(this).not(":disabled").prop('checked', this.checked);
        $('textarea#address').prop('readonly', function(i, v) { return !v; });
    });

    $("#form_delivery_send_confirm").submit(function(event) {
        let status = $('#status option:selected').val()
        if (status == '01') {
            $('#confirmSendModal').modal('show')
        }else if(status == '02'){
            $('#changeSendModal').modal('show')
        }else{
            Swal.fire({icon: 'warning',title: 'เตือน!',text: 'กรุณาเลือกการยืนยันการจัดส่งสินค้า'})
        }
        if($('#truck_type_modal_confirm_send').find(':selected').attr('data-type-name') == 'มารับเอง')
        {
            $('#select_license_plate').hide()
        }else{
            $('#select_license_plate').show()
        }
    //    console.log($('#truck_type_modal_confirm_send').find(':selected').attr('data-type-name'))
        event.preventDefault();
    });


    // Modal ยืนยันการจัดส่งสินค้า
    $("#modal_confirm_send").submit(function(event) {
        $('#btn_submit_modal_confirm_send').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> กำลังโหลด...');
        var values = {};
        $.each($(this).serializeArray(), function(i, field) {
            values[field.name] = field.value;
        });

        // check Validator
        if (!values.address) {
            Swal.fire({icon: 'warning',title: 'เตือน!',text: 'จำเป็นต้องเพิ่มที่อยู่ในการจัดส่ง ก่อนทำการจัดส่ง'})
            $('#btn_submit_modal_confirm_send').prop('disabled', false).html('ยืนยันทำรายการ');
        }else if (!values.truck_type) {
            Swal.fire({icon: 'warning',title: 'เตือน!',text: 'จำเป็นต้องเลือกประเภทรถ'})
            $('#btn_submit_modal_confirm_send').prop('disabled', false).html('ยืนยันทำรายการ');
        }else if(!values.license_plate){
            Swal.fire({icon: 'warning',title: 'เตือน!',text: 'จำเป็นต้องเลือกป้ายทะเบียน'})
            $('#btn_submit_modal_confirm_send').prop('disabled', false).html('ยืนยันทำรายการ');
        }else{
            $.ajax({
                url: "{{ url('api/delivery/confirmOrderDelivery') }}",
                type: "POST",
                data: $(this).serialize()
            }).done(function(res) {
                if (res.status == true) {
                    let url_delivery_summary = `{{ route("delivery.list.delivery.summary", [':id', ':delivery_id'] ) }}`.replace(':id', values.order_id).replace(':delivery_id', values.order_delivery_id);
                    Swal.fire('สำเร็จ!',res.message,'success').then(() => (window.location = url_delivery_summary))
                    $('#btn_submit_modal_confirm_send').prop('disabled', false).html('ยืนยันทำรายการ');
                } else {
                    Swal.fire({icon: 'warning',title: 'เตือน!',text: res.message})
                    $('#btn_submit_modal_confirm_send').prop('disabled', false).html('ยืนยันทำรายการ');
                }
            });
        }


        event.preventDefault();
    });

    // Modal การเลื่อนการจัดส่ง
    $("#modal_change_send").submit(function(event) {
        $('#btn_submit_modal_change_send').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> กำลังโหลด...');
        var values = {};
        $.each($(this).serializeArray(), function(i, field) {
            values[field.name] = field.value;
        });

        // check Validator
        if (!values.remark) {
            Swal.fire({icon: 'warning',title: 'เตือน!',text: 'จำเป็นต้องระบุเหตุผลการเลื่อนการจัดส่ง'})
            $('#btn_submit_modal_change_send').prop('disabled', false).html('ยืนยันทำรายการ');
        }else if (!values.date_send) {
            Swal.fire({icon: 'warning',title: 'เตือน!',text: 'จำเป็นต้องเลือกวันที่จัดส่ง'})
            $('#btn_submit_modal_change_send').prop('disabled', false).html('ยืนยันทำรายการ');
        }else if (!values.round) {
            Swal.fire({icon: 'warning',title: 'เตือน!',text: 'จำเป็นต้องเลือกรอบจัดส่ง'})
            $('#btn_submit_modal_change_send').prop('disabled', false).html('ยืนยันทำรายการ');
        }else{
            $.ajax({
                url: "{{ url('api/delivery/changeOrderDelivery') }}",
                type: "POST",
                data: $(this).serialize()
            }).done(function(res) {
                if (res.status == true) {
                    let url_delivery_list = `{{ route("delivery.list.delivery", ':id' ) }}`.replace(':id', values.order_id);
                    Swal.fire('สำเร็จ!',res.message,'success').then(() => (window.location = url_delivery_list))
                    $('#btn_submit_modal_change_send').prop('disabled', false).html('ยืนยันทำรายการ');
                } else {
                    Swal.fire({icon: 'warning',title: 'เตือน!',text: res.message})
                    $('#btn_submit_modal_change_send').prop('disabled', false).html('ยืนยันทำรายการ');
                }
            });
        }


        event.preventDefault();
    });


    // event onchange ประเภทรถ ยืนยันการจัดส่งสินค้า
    $("#truck_type_modal_confirm_send").change(function () {
        license_plate_modal_confirm_send()
	});

    let license_plate_modal_confirm_send = () => {
        let value = $('#truck_type_modal_confirm_send option:selected').val()
        if (!value) {
            Swal.fire({icon: 'warning',title: 'เตือน!',text: 'จำเป็นต้องเลือกประเภทรถ'})
        }
        $('#select_license_plate').show()
        // console.log($('#truck_type_modal_confirm_send option:selected').attr('data-type-name'));
        if($('#truck_type_modal_confirm_send option:selected').attr('data-type-name') == 'มารับเอง')
        {
            $.ajax({
                    url: `{{ url('api/delivery/master_truck/${value}') }}`,
                }).done(function (res) {

                    var selOpts = '';
                    $.each(res.data, function (i, data) {
                        selOpts += `<option selected value='${data.master_truck_id}'>${ data.license_plate}</option>`;
                    });
                    $('#license_plate_modal_confirm_send').append(selOpts);
                    // $('#license_plate').prop('disabled', false);
                });
            $('#license_plate_modal_confirm_send').prop('disabled', false);
            $('#select_license_plate').hide()
        }else{
            $('#select_license_plate').show()
            return new Promise((resolve, reject) => {
                $('#license_plate_modal_confirm_send option').remove();
                $('#license_plate_modal_confirm_send').append("<option value='' selected disabled hidden>- เลือก -</option>");
                $('#license_plate_modal_confirm_send').prop('disabled', true);

                $.ajax({
                    url: `{{ url('api/delivery/master_truck/${value}') }}`,
                }).done(function (res) {
                    var selOpts = '';
                    $.each(res.data, function (i, data) {
                        selOpts += `<option value='${data.master_truck_id}'>${ data.license_plate}</option>`;
                    });
                    $('#license_plate_modal_confirm_send').append(selOpts);
                    $('#license_plate_modal_confirm_send').prop('disabled', false);
                });
            });
        }


    }

    // event onchange ประเภทรถ การเลื่อนการจัดส่ง
    $("#truck_type_modal_change_send").change(function () {
        console.log($(this).find(':selected').attr('data-type-name'));
        if($(this).find(':selected').attr('data-type-name') == 'มารับเอง')
        {
            $.ajax({
                    url: `{{ url('api/delivery/master_truck/${this.value}') }}`,
                }).done(function (res) {

                    var selOpts = '';
                    $.each(res.data, function (i, data) {
                        selOpts += `<option selected value='${data.master_truck_id}'>${ data.license_plate}</option>`;
                    });
                    $('#license_plate_modal_change_send').append(selOpts);
                    // $('#license_plate').prop('disabled', false);
                });
            $('#license_plate_modal_change_send').prop('disabled', false);
            // console.log($('#license_plate').val())
            $('#select_license_change_plate').hide()
        }else{
            $('#select_license_change_plate').show()
            return new Promise((resolve, reject) => {
                $('#license_plate_modal_change_send option').remove();
                $('#license_plate_modal_change_send').append("<option value='' selected disabled hidden>- เลือก -</option>");
                $('#license_plate_modal_change_send').prop('disabled', true);

                $.ajax({
                    url: `{{ url('api/delivery/master_truck/${this.value}') }}`,
                }).done(function (res) {
                    var selOpts = '';
                    $.each(res.data, function (i, data) {
                        selOpts += `<option value='${data.master_truck_id}'>${ data.license_plate}</option>`;
                    });
                    $('#license_plate_modal_change_send').append(selOpts);
                    $('#license_plate_modal_change_send').prop('disabled', false);
                });
            });
        }

	});

</script>
@endsection
