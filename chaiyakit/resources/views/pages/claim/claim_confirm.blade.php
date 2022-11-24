@extends('layouts.app')

@section('content')
<div class="form-main" id="page-claim-detail">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('claim') }}">เมนูการคืน / เคลมสินค้า</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('claim.list.order_claim',$order_id) }}">รายการจัดส่ง</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">ยืนยัน เคลมสินค้า</li>
                    </ol>
                </nav>
            </div>
        </div>

        <hr>
        <div class="row">
            <div class="form-group col-md-3">
                <label for="" class="label-blue">ใบสั่งซื้อเลขที่ :</label>
                <br>
                <span>ใบสั่งซื้อเลขที่ {{ $data['Order']->order_number  }} / รายการที่จัดส่ง {{  $data['OrderDeliveryItem'][0]->order_delivery_id }}</span>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-3">
                <label for="" class="label-blue">ร้านค้า :</label>
                <br>
                <span>{{ $data['Order']->name_department }}</span>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-3">
                <label for="" class="label-blue">วันที่สั่งซื้อ :</label>
                <br>
                <span>{{ formatDateThat($data['Order']->created_date) }}</span>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 form-content mb-3 table-responsive">
                <form id="form_claim_send" method="POST">
                    <input type="hidden" name="order_id" id="order_id" value="{{ $order_id }}" />
                    <input type="hidden" name="delivery_id" value="{{ $delivery_id }}" />
                    <table class="table table-striped dt-responsive nowrap" style="width:100%" id="table">
                        <thead>
                            <tr class="text-center">
                                <th>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkAll">
                                        <label class="custom-control-label" for="checkAll"></label>
                                    </div>
                                </th>
                                <th>สินค้า</th>
                                <th>ประเภทสินค้า</th>
                                <th>ขนาด</th>
                                <th>ราคาต่อหน่วย</th>
                                <th>รวมเป็นเงิน</th>
                                <th>จำนวนสินค้าที่ส่ง</th>
                                <th>น้ำหนัก</th>
                                <th>จำนวนสินค้าเคลม</th>
                                <th>หมายเหตุ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['OrderDeliveryItem'] as $key => $item)
                            <tr class="text-center">
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck{{$key}}"
                                            name='send_ids[]' value="{{ $item->order_item_id }}"
                                            {{ ($item->delivery_item_qty == 0) ? 'disabled' : ''}}>
                                        <label class="custom-control-label" for="customCheck{{$key}}"></label>
                                    </div>
                                </td>
                                <td>{{ $item->product_name }}</td> {{-- สินค้า --}}
                                <td>{{ $item->product_type_name }}</td> {{-- ประเภทสินค้า --}}
                                <td>{{ number_format($item->product_size_name,2) .' '. config('sizeunit')[$item->product_size_unit] }}</td> {{-- ขนาด --}}
                                <td>{{ number_format($item->price,2) }}</td> {{-- ราคาต่อหน่วย --}}
                                <td>{{ number_format($item->total_price,2) }}</td> {{-- รวมเป็นเงิน --}}
                                <td>{{ number_format( $item->delivery_item_qty ) }}</td> {{-- จำนวนสินค้าที่ส่ง --}}
                                <td>{{ ($item->product_weight < 1000) ? number_format($item->product_weight).' '.config('sizeunit')['04'] : number_format($item->product_weight/1000,2).' '.config('sizeunit')['05'] }}</td> {{-- น้ำหนัก --}}
                                <td><input type="number" class="form-control"
                                        name="qty_send[]" min="1"
                                        max="{{ $item->delivery_item_qty }}"
                                        {{ ($item->delivery_item_qty == 0) ? 'disabled' : ''}}
                                        value="{{ $item->delivery_item_qty }}" required>
                                </td> {{-- ส่งสินค้า --}}
                                <td><input type="text" class="form-control" name="note[]" value="" required>
                                </td> {{-- หมายเหตุ --}}

                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                    <hr>
                    <div class="row">
                        <div class="col-12 my-3">
                            <h5 class="title-0">รายละเอียดการจัดส่ง :</h5>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="">วันที่จัดส่ง :</label>
                            <input type="text" class="form-control date" name="date_send" id="datepicker" value="" />
                        </div>
                        <div class="form-group col-md-3">
                            <label for="">รอบจัดส่ง :</label>
                            <select class="form-control" name="round">
                                <option value='' selected disabled hidden>- เลือก -</option>
                                @foreach ($data['MasterRound'] as $item)
                                <option value="{{$item->master_round_id}}">{{ $item->name .' เวลา '. $item->round_time}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="">ประเภทรถ :</label>
                            <select class="form-control" name="truck_type" id="truck_type">
                                <option value='' selected disabled hidden>- เลือก -</option>
                                @foreach ($data['MasterTruckType'] as $item)
                                <option value="{{$item->master_truck_type_id}}">
                                    {{ $item->type. ' (จำนวน : '. $item->count .' คัน)'}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="">ป้ายทะเบียน :</label>
                            <select class="form-control" name="license_plate" id="license_plate" disabled>
                                <option value='' selected disabled hidden>- เลือก -</option>
                            </select>
                        </div>
                        <div class="col-12 text-center mt-5">
                            <a href="{{ route('claim.list.order_claim',$order_id) }}" type="button"
                                class="btn btn-danger text-white">ยกเลิก</a>
                            <button class="btn btn-primary" type="button" id="btn_claim">เคลมสินค้า</button>
                        </div>
                    </div>
                </form>
            </div>
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
    $("#checkAll").click(function(){
        $('input:checkbox').not(this).not(":disabled").prop('checked', this.checked);
    });

    $("#btn_return").click(function(event) {
        // disabled button
        var data_return = $("#form_claim_send").serializeArray();
        $(':input[type="submit"]').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> กำลังโหลด...');
        $.ajax({
            url: "{{ url('api/claim/createOrderreturn') }}",
            type: "POST",
            data: data_return
        }).done(function(res) {
            if (res.status == true) {
                let url_delivery_list = `{{ route("delivery.list.delivery", ':id' ) }}`.replace(':id', $('#order_id').val());
                Swal.fire('สำเร็จ!',res.message,'success').then(() => (window.location = url_delivery_list))
                // enabled button
                $(':input[type="submit"]').prop('disabled', false).html('ยืนยันทำรายการ');
            } else {
                Swal.fire({icon: 'warning',title: 'เตือน!',text: res.message})
                // enabled button
                $(':input[type="submit"]').prop('disabled', false).html('ยืนยันทำรายการ');
            }
        });
        event.preventDefault();
    });

    $("#btn_claim").click(function(event) {
        // disabled button
        var data_claim = $("#form_claim_send").serializeArray();
        $(':input[type="submit"]').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> กำลังโหลด...');
        $.ajax({
            url: "{{ url('api/claim/createOrderClaim') }}",
            type: "POST",
            data: data_claim
        }).done(function(res) {
            if (res.status == true) {
                let url_delivery_list = `{{ route("claim.list.order_claim", ':id' ) }}`.replace(':id', $('#order_id').val());
                Swal.fire('สำเร็จ!',res.message,'success').then(() => (window.location = url_delivery_list))
                // enabled button
                $(':input[type="submit"]').prop('disabled', false).html('ยืนยันทำรายการ');
            } else {
                Swal.fire({icon: 'warning',title: 'เตือน!',text: res.message})
                // enabled button
                $(':input[type="submit"]').prop('disabled', false).html('ยืนยันทำรายการ');
            }
        });
        event.preventDefault();
    });

    $("#truck_type").change(function () {
      return new Promise((resolve, reject) => {
        $('#license_plate option').remove();
        $('#license_plate').append("<option value='' selected disabled hidden>- เลือก -</option>");
        $('#license_plate').prop('disabled', true);

        $.ajax({
          url: `{{ url('api/delivery/master_truck/${this.value}') }}`,
        }).done(function (res) {
                  var selOpts = '';
                  $.each(res.data, function (i, data) {
                      selOpts += `<option value='${data.master_truck_id}'>${ data.license_plate}</option>`;
                  });
                  $('#license_plate').append(selOpts);
                  $('#license_plate').prop('disabled', false);
              });
      });
    });

</script>
@endsection
