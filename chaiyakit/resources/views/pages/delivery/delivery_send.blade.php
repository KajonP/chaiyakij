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
                <h2>จัดการสินค้าค้างส่ง : เลขที่รายการ {{ $data['Order']->order_number .'/'. $data['OrderDelivery']->order_delivery_number }}</h2>
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
                <label for="">จำนวนสินค้าที่สั่งทั้งหมด :</label>
                <br>
                <span class="c-0095dd">{{ number_format($data['Order']->qty_all) }}</span>
            </div>
            <div class="form-group col-md-3">
                <label for="">จำนวนสินค้าที่ส่งแล้ว :</label>
                <br>
                <span class="c-000000">{{ number_format($data['Order']->sum_order_delivery_item) }}</span>
            </div>
            <div class="form-group col-md-4">
                <label for="">จำนวนสินค้าค้างส่ง :</label>
                <br>
                <span
                    class="c-ff871e">{{  number_format($data['Order']->qty_all - $data['Order']->sum_order_delivery_item) }}</span>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12 form-content mb-3 table-responsive">
                <form id="form_delivery_send" method="POST">
                    <input type="hidden" name="order_id" id="order_id" value="{{ $order_id }}" />
                    <input type="hidden" name="delivery_id" id="delivery_id" value="{{ $delivery_id }}" />
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
                                <th>ขนาดความยาว</th>


                                <th>น้ำหนักรวม</th>

                                <th>หมายเหตุ</th>
                                <th>สินค้าค้างส่ง</th>
                                <th>ส่งสินค้า</th>
                                <th>น้ำหนักจัดส่ง</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['OrderItem'] as $key => $item)
                            <tr class="text-center item_{{$key}}">
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck{{$key}}"
                                            name='send_ids[]' value="{{ $item->order_item_id }}"
                                            {{ ($item->qty - $item->sum_order_delivery_item == 0) ? 'disabled' : ''}}>
                                        <label class="custom-control-label" for="customCheck{{$key}}"></label>
                                    </div>
                                </td>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->product_type_name }}</td>
                                <td>{{ $item->product_size_name_text }}</td>


                                <td>{{ $item->product_weight_text }}
                                </td>


                                <td>{{ $item->remark }}</td>
                                <td>{{ number_format($item->qty - $item->sum_order_delivery_item) }}</td>
                                <td><input type="number" class="form-control qty_delivery"  onblur="updateItem({{$key}})"
                                        name="qty_send[{{ $item->order_item_id }}]" min="1"
                                        max="{{ $item->qty - $item->sum_order_delivery_item }}"
                                        {{ ($item->qty - $item->sum_order_delivery_item == 0) ? 'disabled' : ''}}
                                        value="{{ $item->qty - $item->sum_order_delivery_item }}" required>
                                </td>
                                <td><span class="wg_{{$key}}">{{ $item->product_weight_text }}</span><input type="hidden" name="wg" class="wg_delivery" value="{{ $item->product_size_weight}}"></td>
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
                            <label for="">วันที่จัดส่ง :</label>  <span style="color: red; font-size: 15px;">*</span>
                            <input type="text" autocomplete="off" class="form-control date" name="date_send" id="datepicker" value="" />
                        </div>
                        <div class="form-group col-md-3">
                            <label for="">ประเภทรถ :</label>
                            <select class="form-control" name="truck_type" id="truck_type">
                                <option value='' selected disabled hidden>- เลือก -</option>
                                @foreach ($data['MasterTruckType'] as $item)
                                <option data-type-name="{{$item->type}}" value="{{$item->master_truck_type_id}}">
                                    @if($item->type == 'มารับเอง')
                                    {{ $item->type}}
                                    @else
                                    {{ $item->type. ' (จำนวน : '. $item->count .' คัน)'}}
                                    @endif
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3" id="select_license_plate">
                            <label for="">ป้ายทะเบียน :</label>
                            <select class="form-control" name="license_plate" id="license_plate" disabled>
                                <option value='' selected disabled hidden>- เลือก -</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="">รอบจัดส่ง :</label>  <span style="color: red; font-size: 15px;">*</span>
                            <select class="form-control" name="round">
                                <option value='' selected disabled hidden>- เลือก -</option>
                                @foreach ($data['MasterRound'] as $item)
                                <option value="{{$item->master_round_id}}">{{ $item->name .' เวลา '. $item->round_time}}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 text-center mt-5">
                        @if ($ref === 'order')
                            <a href="{{ route('orders.detail',['id' => $order_id]) }}" type="button"
                                class="btn btn-secondary p-2" ><i class="fas fa-chevron-left"></i> กลับไปหน้าสั่งซื้อ</a>
                        @else
                        <a href="{{ route('delivery.list.delivery',$order_id) }}" type="button"
                                class="btn btn-danger text-white p-2" >ยกเลิก</a>
                        @endif
                            <button class="btn btn-success p-2" type="submit">ยืนยันทำรายการ</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('pages.loading')
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

    $("#form_delivery_send").submit(function(event) {
        var check_item=0;
        $("input[type='checkbox']").each(function () {
				if($(this).prop("checked"))
				{
					check_item++;
				}

			});

            if(check_item ==0){
                Swal.fire({icon: 'warning',title: 'เตือน!',text: 'จำเป็นต้องเลือกส่งสินค้าอย่างน้อย 1 รายการ'})
                return false;
            }

        // disabled button
        $(':input[type="submit"]').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> กำลังโหลด...');
        $.ajax({
            url: "{{ url('api/delivery/createOrderDelivery') }}",
            type: "POST",
            data: $(this).serialize()
        }).done(function(res) {
            $('#loading_page').modal('hide');
            if (res.status == true) {
                let url_delivery_list;
                if('order' == '{{$ref}}'){
                url_delivery_list = `{{ route("orders.detail", [':id', ':delivery_id'] ) }}`.replace(':id', $('#order_id').val()).replace(':delivery_id', $('#delivery_id').val());
                }else{
                url_delivery_list = `{{ route("delivery.list.delivery", ':id' ) }}`.replace(':id', $('#order_id').val());
                }
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
        // console.log($(this).find(':selected').attr('data-type-name'))
        if($(this).find(':selected').attr('data-type-name') == 'มารับเอง')
        {
            $.ajax({
                    url: `{{ url('api/delivery/master_truck/${this.value}') }}`,
                }).done(function (res) {

                    var selOpts = '';
                    $.each(res.data, function (i, data) {
                        selOpts += `<option selected value='${data.master_truck_id}'>${ data.license_plate}</option>`;
                    });
                    $('#license_plate').append(selOpts);
                    // $('#license_plate').prop('disabled', false);
                });
            $('#license_plate').prop('disabled', false);
            // console.log($('#license_plate').val())
            $('#select_license_plate').hide()
        }else{
            $('#select_license_plate').show()
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
        }

    });
   function updateItem(i) {
        var row_input=[];
		$('.item_'+i).find("td input").each(function(index,val) {
            textVal = this.value;
            inputName = $(this).attr("name");


            if(inputName.indexOf('ty_send') >0){
                row_input['qty_send']=textVal;
            }else{
                row_input[inputName]=textVal;
            }

        });
      console.log(row_input);
        var qty =row_input['qty_send'];
        var  weight_g=row_input['wg'];
         var total_weight =convertWeightUnit(weight_g * qty);
        $('.wg_'+i).html(total_weight['number']+' '+total_weight['unit']);
    };


    function convertWeightUnit(weight_g){
			if(weight_g < 1000)
			return {'number':numeral(weight_g).format('0,0.00'),'unit':'กรัม'};
			else
			return {'number':numeral(weight_g / 1000).format('0,0.00'),'unit':'กิโลกรัม'};

	}
</script>
@endsection
