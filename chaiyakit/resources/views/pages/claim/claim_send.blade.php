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
            <li class="breadcrumb-item active" aria-current="page">การคืน / เคลมสินค้า</li>
          </ol>
        </nav>
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-md-6 align-self-end">
        <h2>ใบสั่งซื้อเลขที่ {{ $data['Order']->order_number  }} / รายการที่จัดส่ง
          {{  $data['OrderDelivery']->order_delivery_number }}</h2>
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
    {{-- <div class="row">
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
    </div>
    <div class="row">
      <div class="form-group col-md-3">
        <label for="" class="label-blue">ร้านค้า :</label>
        <br>
        <span>{{ $data['Order']->name_merchant }}</span>
      </div>
      <div class="form-group col-md-3">
        <label for="" class="label-blue">หน่วยงาน :</label>
        <br>
        <span>{{ $data['Order']->department_name }}</span>
      </div>
      <div class="form-group col-md-6">
        <label for="" class="label-blue">ที่อยู่ :</label>
        <br>
        <span>{{ $data['Order']->address }}</span>
      </div>
    </div>
    <div class="row">
      <div class="form-group col-md-3">
        <label for="" class="label-blue">เบอร์โทรศัพท์ :</label>
        <br>
        <span>{{ $data['Order']->phone_number }}</span>
      </div>
      <div class="form-group col-md-3">
        <label for="" class="label-blue">เลขประจำตัวภาษีอากร :</label>
        <br>
        <span>{{ $data['Order']->tax_number }}</span>
      </div>
      <div class="form-group col-md-6">
        <label for="" class="label-blue">จำนวนเงินรวมทั้งสิ้น (บาท) :</label>
        <br>
        <span>{{ number_format($data['Order']->grand_total,2) }}</span>
      </div>
    </div> --}}
    @php
      // dd($data['OrderDeliveryItem']);
    @endphp
    <div class="row mt-3">
      <div class="col-12 form-content mb-3 table-responsive">
        <form id="form_claim_send" method="POST">
          <input type="hidden" name="order_id" id="order_id" value="{{ $order_id }}" />
          <input type="hidden" name="delivery_id" value="{{ $delivery_id }}" />
          <input type="hidden" name="data" value="{{ $data['OrderDeliveryItem'] }}" />
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
                <th>ราคาต่อหน่วย เพิ่มเติม</th>
                <th>รวมเป็นเงิน</th>
                <th>จำนวนสินค้าที่ส่ง</th>
                <th>น้ำหนัก</th>
                <th>จำนวนสินค้าคืน / เคลม</th>
                <th>หมายเหตุ</th>
              </tr>
            </thead>

            <tbody>
              @foreach ($data['OrderDeliveryItem'] as $key => $item)

              <tr class="text-center">
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="customCheck{{$key}}" name='send_ids[]'
                      value="{{ $item->order_item_id }}" {{ ($item->delivery_item_qty - $item->sum_product_return_qty == 0) ? 'disabled' : ''}}>
                    <label class="custom-control-label" for="customCheck{{$key}}"></label>
                  </div>
                </td>
                <td>{{ $item->product_name }}</td> {{-- สินค้า --}}
                <td>{{ $item->product_type_name }}</td> {{-- ประเภทสินค้า --}}
                <td>{{ number_format($item->product_size_name,2) .' '. config('sizeunit')[$item->product_size_unit] }}</td> {{-- ขนาด --}}
                <input type="hidden" name="product_size_name[]"
                  value="{{ number_format($item->product_size_name,2) .' '. config('sizeunit')[$item->product_size_unit] }}" />
                <input type="hidden" name="product_size_int[]"
                  value="{{ $item->product_size_name }}" />
                <input type="hidden" name="product_size_unit[]"
                  value="{{ config('sizeunit')[$item->product_size_unit] }}" />
                <td>{{ number_format($item->price,2) }}</td> {{-- ราคาต่อหน่วย --}}
                <td>{{ number_format($item->addition,2) }}</td> {{-- ราคาต่อหน่วย --}}
                <td>{{ number_format(((($item->price + $item->addition)*$item->product_size_name)*$item->delivery_item_qty),2) }}</td> {{-- รวมเป็นเงิน --}}
                <input type="hidden" name="price[]" value="{{ ((($item->price + $item->addition)*$item->product_size_name)*$item->delivery_item_qty) }}" />
                <input type="hidden" name="additiona[]" value="{{ ($item->price + $item->addition) }}" />
                <td>
                    {{ number_format( $item->delivery_item_qty ) }}</td> {{-- จำนวนสินค้าที่ส่ง --}}
                <td>
                  {{ ($item->product_weight < 1000) ? number_format($item->product_weight).' '.config('sizeunit')['04'] : number_format($item->product_weight/1000,2).' '.config('sizeunit')['05'] }}
                </td> {{-- น้ำหนัก --}}
                <input type="hidden" name="product_weight[]"
                  value="{{ ($item->product_weight < 1000) ? number_format($item->product_weight).' '.config('sizeunit')['04'] : number_format($item->product_weight/1000,2).' '.config('sizeunit')['05'] }}" />
                <input type="hidden" name="product_weight_int[]" value="{{ $item->product_weight }}" />
                <td>
                    <input type="number" class="form-control"
                    onkeyup="check_qty_prduct(this,{{ $item->delivery_item_qty - $item->sum_product_return_qty }})" name="qty_send[]" min="1"
                    max="{{ $item->delivery_item_qty - $item->sum_product_return_qty }}" {{ ($item->delivery_item_qty - $item->sum_product_return_qty == 0) ? 'disabled' : ''}}
                    value="{{ $item->delivery_item_qty - $item->sum_product_return_qty }}" required>
                </td> {{-- ส่งสินค้า --}}
                <td><input type="text" class="form-control" name="note[]" value="" {{ ($item->delivery_item_qty - $item->sum_product_return_qty == 0) ? 'disabled' : ''}} required>
                </td> {{-- หมายเหตุ --}}

              </tr>
              @endforeach

            </tbody>
          </table>
          <hr>
          <b>หมายเหตุ :</b> จำนวนสินค้าที่คืน/เคลม อาจจะไม่เท่ากับจำนวนที่จัดส่ง <br> &emsp;&emsp;&emsp;&emsp;&ensp;เนื่องจากเคยมีการทำรายการ คืน/เคลม ไปแล้ว

          <div class="row">
            <div class="col-12 text-center mt-5">
              <button class="btn btn-info" type="button" id="btn_return">คืนสินค้า</button>
              <button class="btn btn-primary" type="button" id="btn_claim">เคลมสินค้า</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- เคลมสินค้า  --}}
  <div class="modal fade model_confirm" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">เคลมสินค้า</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <div class="row">
            <div class="form-group col-md-3">
              <label for="" class="label-blue">ใบสั่งซื้อเลขที่ :</label>
              <br>
              <span> {{ $data['Order']->order_number  }} / {{  $data['OrderDelivery']->order_delivery_number }}</span>
            </div>
            <div class="form-group col-md-3">
              <label for="" class="label-blue">ร้านค้า :</label>
              <br>
              <span>{{ $data['Order']->name_merchant }}</span>
            </div>
            <div class="form-group col-md-3">
              <label for="" class="label-blue">วันที่สั่งซื้อ :</label>
              <br>
              <span>{{ formatDateThat($data['Order']->created_date) }}</span>
            </div>
          </div>


          <div class="row">
            <div class="col-12">
              <table class="table dt-responsive nowrap" id="table_claim" width="100%"></table>
            </div>
          </div>
          <hr>

          <div class="row">
            <div class="form-group col-md-7"></div>
            <div class="col-md-5">
              <div class="form-group row ">
                <label class="col-md-6 label-blue">รายการสินค้าทั้งหมด</label>
                <div class="col-md-6">
                  <span class="order_all"></span>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-7"></div>
            <div class="col-md-5">
              <div class="form-group row ">
                <label class="col-md-6 label-blue">น้ำหนักสินค้าที่เลือกทั้งหมด</label>
                <div class="col-md-6">
                  <span class="weight_all"></span>
                </div>
              </div>
            </div>
          </div>
          <hr>

          <div class="row">
            <div class="form-group col-md-3">
              <label for="">วันที่จัดส่ง :</label> <span class="color-red">*</span>
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
                <select class="form-control license_plate" name="license_plate" id="license_plate" disabled>
                  <option value='' selected disabled hidden>- เลือก -</option>
                </select>
              </div>
            <div class="form-group col-md-3">
              <label for="">รอบจัดส่ง :</label> <span class="color-red">*</span>
              <select class="form-control" name="round">
                <option value='' selected disabled hidden>- เลือก -</option>
                @foreach ($data['MasterRound'] as $item)
                <option value="{{$item->master_round_id}}">{{ $item->name .' เวลา '. $item->round_time}}
                </option>
                @endforeach
              </select>
            </div>

          </div>

        </div>
        <div class="modal-footer">
          <a href="{{ route('claim.list.order_claim',$order_id) }}" type="button"
            class="btn btn-danger text-white">ยกเลิก</a>
          <button class="btn btn-primary" type="button" id="btn_claim_confirm">ยืนยัน</button>
        </div>
      </div>
    </div>
  </div>

  {{-- คืนสินค้า  --}}
  <div class="modal fade model_return" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">คืนสินค้า</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <div class="row">
            <div class="form-group col-md-3">
              <label for="" class="label-blue">ใบสั่งซื้อเลขที่ :</label>
              <br>
              <span>{{ $data['Order']->order_number  }} / {{  $data['OrderDelivery']->order_delivery_number }}</span>
            </div>
            <div class="form-group col-md-3">
              <label for="" class="label-blue">ร้านค้า :</label>
              <br>
              <span>{{ $data['Order']->name_merchant }}</span>
            </div>
            <div class="form-group col-md-3">
              <label for="" class="label-blue">วันที่สั่งซื้อ :</label>
              <br>
              <span>{{ formatDateThat($data['Order']->created_date) }}</span>
            </div>
          </div>


          <div class="row">
            <div class="col-12">
              <table class="table dt-responsive nowrap" id="table_return" width="100%"></table>
            </div>
          </div>
          <hr>

          <div class="row">
            <div class="form-group col-md-7"></div>
            <div class="col-md-5">
              <div class="form-group row ">
                <label class="col-md-6 label-blue">รายการสินค้าทั้งหมด</label>
                <div class="col-md-6">
                  <span class="order_all"></span>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-7"></div>
            <div class="col-md-5">
              <div class="form-group row ">
                <label class="col-md-6 label-blue">จำนวนเงินรวมทั้งสิ้น</label>
                <div class="col-md-6">
                  <span class="amount_all"></span>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-7"></div>
            <div class="col-md-5">
              <div class="form-group row ">
                <label class="col-md-6 label-blue">น้ำหนักสินค้าที่เลือกทั้งหมด</label>
                <div class="col-md-6">
                  <span class="weight_all"></span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <a href="{{ route('claim.list.order_claim',$order_id) }}" type="button"
            class="btn btn-danger text-white">ยกเลิก</a>
          <button class="btn btn-primary" type="button" id="btn_return_confirm">ยืนยัน</button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script>
  var claim_return = {
      data: false,
    }
    $('#datepicker').datepicker({
        uiLibrary: 'bootstrap4',
        format: 'dd-mm-yyyy',
        minDate: moment().format('D-M-Y')
    });

    $("#checkAll").click(function(){
        $('input:checkbox').not(this).not(":disabled").prop('checked', this.checked);
    });
    //////////////////////////////////////////////////////////  claim //////////////////////////////////////////////////////////////////////////////////
    $("#btn_claim").click(function(event) {
      var qty_send=[];
      var note=[];
      var product_size_name=[];
      var product_size_unit=[];
      var product_weight=[];
      var send_ids=[];
      var product_weight_int=[];

      $('[name="send_ids[]"]').each(function() {

          if ($(this).prop("checked")) {

            send_ids.push($(this).val());

            $('[name="qty_send[]"]').each(function() {
                qty_send.push($(this).val());
            });

            $('[name="note[]"]').each(function() {
                note.push($(this).val());
            });

            $('[name="product_size_name[]"]').each(function() {
                product_size_name.push($(this).val());
            });

            $('[name="product_size_unit[]"]').each(function() {
                product_size_unit.push($(this).val());
            });

            $('[name="product_weight[]"]').each(function() {
                product_weight.push($(this).val());
            });

            $('[name="product_weight_int[]"]').each(function() {
                product_weight_int.push($(this).val());
            });

          }
      });


        // $('#btn_claim_confirm').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> กำลังโหลด...');

        if (send_ids.length > 0) {
          $('.model_confirm').modal('show');
          var data = JSON.parse($("input[name$='data']").val());

          var data2 = [];
          var amount = 0;
          var weight = 0;

          for (var i = 0; i < data.length; i++) {
            data[i].product_size_name = product_size_name[i];
            data[i].product_weight = product_weight[i];
            data[i].note = note[i];
            data[i].qty = qty_send[i];
            data[i].size_unit = product_size_unit[i];
            data[i].product_weight_int = product_weight_int[i]
          }

          for (var u = 0; u < data.length; u++) {
            for (var y = 0; y < send_ids.length; y++) {
              if (send_ids[y] == data[u].order_item_id) {
                data2.push(data[u]);
                amount = (data[u].total_price*1) + amount;
                weight = (data[u].product_weight_int*1) + weight;
              }
            }
          }

          $('.order_all').text(data2.length+" "+"รายการ");
          // $('.amount_all').text((amount*1).toLocaleString(undefined, {minimumFractionDigits: 2}));
          $('.weight_all').text((weight*1) < 1000 ? weight.toLocaleString(undefined, {minimumFractionDigits: 2})+" "+"กรัม" : ((weight*1)/1000).toLocaleString(undefined, {minimumFractionDigits: 2})+" "+"กิโลกรัม" );
          $('#table_claim').DataTable({
            // scrollX: true,
            // scrollY: "330px",
            scrollCollapse: true,
            paging: false,
            info: false,
            ordering: false,
            searching: false,
            autoWidth: false,
            data: data2,
            destroy: true,
            columns: [
              {
                title: "#",
                data: function(a, b, c, d) {
                  return d.row + 1;
                },
                width: "%"
              },
              {
                title: "สินค้า",
                data: function(e) {
                  return e.product_name ;
                },
              },
              {
                title: "ประเภทสินค้า",data: function(e) {
                  return e.product_type_name ;
                },
              },
              {
                title: "ขนาด",data: function(e) {
                  return e.product_size_name ;
                },
              },
              {
                title: "ราคาต่อหน่วย",data: function(e) {
                  return (e.price*1).toLocaleString(undefined, {minimumFractionDigits: 2});
                },
              },
              {
                title: "รวมเป็นเงิน",data: function(e) {
                  return (e.total_price*1).toLocaleString(undefined, {minimumFractionDigits: 2});
                },
              },
              {
                title: "จำนวนสินค้าที่ส่ง",data: function(e) {
                  return (e.delivery_item_qty*1).toLocaleString(undefined, {minimumFractionDigits: 2});
                },
              },
              {
                title: "น้ำหนัก",data: function(e) {
                  return e.product_weight  ;
                },
              },
              {
                title: "จำนวนสินค้าคืน / เคลม",data: function(e) {
                  return e.qty;
                },
              },
              {
                title: "หมายเหตุ",data: function(e) {
                  return e.note ;
                },
              },
            ],
          });

          var data_claim = {
            'order_id' : $("input[name$='order_id']").val(),
            'delivery_id' : $("input[name$='delivery_id']").val(),
            'data_claim_return' : data2,

            'date_send' : "",
            'round' : "",
            'truck_type' : "",
            'license_plate' : "",
          };


          claim_return.data = data_claim;

        }else {
          Swal.fire({icon: 'warning',title: 'เตือน!',text: "กรุณาเลือกสินค้า"})
        }


        event.preventDefault();
    });

    $( ".license_plate" ).change(function() {
      if ($("select[name=license_plate]").val() != null) {
          claim_return.data.date_send = $("input[name$='date_send']").val();
          claim_return.data.round = $("select[name='round']").val();
          claim_return.data.truck_type = $("select[name='truck_type']").val();
          claim_return.data.license_plate = $("select[name=license_plate]").val();
      }
    });

    $("#btn_claim_confirm").click(function(event) {

      claim_return.data.date_send = $("input[name$='date_send']").val();
      claim_return.data.round = $("select[name='round']").val();
      claim_return.data.truck_type = $("select[name='truck_type']").val();
      claim_return.data.license_plate = $("select[name=license_plate]").val();

        $('#btn_claim_confirm').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> กำลังโหลด...');
        $.ajax({
            url: "{{ url('api/claim/createOrderClaim') }}",
            type: "POST",
            data: claim_return.data
        }).done(function(res) {
            if (res.status == true) {
                let url_delivery_list = `{{ route("claim.list.order_claim", ":id") }}`.replace(':id', $('#order_id').val());
                Swal.fire('สำเร็จ!',res.message,'success').then(() => (window.location = url_delivery_list))
                // enabled button
                $('#btn_claim_confirm').prop('disabled', false).html('ยืนยันทำรายการ');
            } else {
                Swal.fire({icon: 'warning',title: 'เตือน!',text: res.message})
                // enabled button
                $('#btn_claim_confirm').prop('disabled', false).html('ยืนยันทำรายการ');
            }
        });
    });

    ////////////////////////////////////////////////////////// end claim //////////////////////////////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////// return //////////////////////////////////////////////////////////////////////////////////
    $("#btn_return").click(function(event) {
      var qty_send_return = [];
      var note_return = [];
      var product_size_name_return = [];
      var product_size_unit_return = [];
      var product_weight_return = [];
      var send_ids_return = [];
      var product_weight_int_return = [];
      var price = [];
      var additiona = [];
      var product_size_int = [];

      $('[name="send_ids[]"]').each(function() {

          if ($(this).prop("checked")) {

            send_ids_return.push($(this).val());

            $('[name="qty_send[]"]').each(function() {
                qty_send_return.push($(this).val());
            });

            $('[name="note[]"]').each(function() {
                note_return.push($(this).val());
            });

            $('[name="product_size_name[]"]').each(function() {
                product_size_name_return.push($(this).val());
            });

            $('[name="product_size_unit[]"]').each(function() {
                product_size_unit_return.push($(this).val());
            });

            $('[name="product_weight[]"]').each(function() {
                product_weight_return.push($(this).val());
            });

            $('[name="product_weight_int[]"]').each(function() {
                product_weight_int_return.push($(this).val());
            });

            $('[name="price[]"]').each(function() {
                price.push($(this).val());
            });

            $('[name="additiona[]"]').each(function() {
                additiona.push($(this).val());
            });

            $('[name="product_size_int[]"]').each(function() {
                product_size_int.push($(this).val());
            });

          }
      });


        // $('#btn_claim_confirm').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> กำลังโหลด...');

        if (send_ids_return.length > 0) {
          $('.model_return').modal('show');
          var data_return = JSON.parse($("input[name$='data']").val());

          var data2_return = [];
          var amount_return = 0;
          var weight_return = 0;

          for (var i = 0; i < data_return.length; i++) {
            data_return[i].product_size_name = product_size_name_return[i];
            data_return[i].product_weight = product_weight_return[i];
            data_return[i].note = note_return[i];
            data_return[i].qty = qty_send_return[i];
            data_return[i].size_unit = product_size_unit_return[i];
            data_return[i].product_weight_int = product_weight_int_return[i]
            data_return[i].total_price = price[i]
            data_return[i].additiona = additiona[i] // ราคาต่หน่วยบาวกับราคาเพิ่มเติม
            data_return[i].product_size_int = product_size_int[i]
          }
          // console.log(data_return);
          for (var u = 0; u < data_return.length; u++) {
            for (var y = 0; y < send_ids_return.length; y++) {
              if (send_ids_return[y] == data_return[u].order_item_id) {
                data2_return.push(data_return[u]);
                amount_return = ( (((data_return[u].additiona*1) * (data_return[u].product_size_int*1)) * (data_return[u].qty*1))  ) + amount_return;
                weight_return = (data_return[u].product_weight_int*1) + weight_return;
              }
            }
          }

          $('.order_all').text(data2_return.length+" "+"รายการ");
          $('.amount_all').text((amount_return*1).toLocaleString(undefined, {minimumFractionDigits: 2}));
          $('.weight_all').text((weight_return*1) < 1000 ? weight_return.toLocaleString(undefined, {minimumFractionDigits: 2})+" "+"กรัม" : ((weight_return*1)/1000).toLocaleString(undefined, {minimumFractionDigits: 2})+" "+"กิโลกรัม" );
          $('#table_return').DataTable({
            // scrollX: true,
            // scrollY: "330px",
            scrollCollapse: true,
            paging: false,
            info: false,
            ordering: false,
            searching: false,
            autoWidth: false,
            data: data2_return,
            destroy: true,
            columns: [
              {
                title: "#",
                data: function(a, b, c, d) {
                  return d.row + 1;
                },
                width: "%"
              },
              {
                title: "สินค้า",
                data: function(e) {
                  return e.product_name ;
                },
              },
              {
                title: "ประเภทสินค้า",data: function(e) {
                  return e.product_type_name ;
                },
              },
              {
                title: "ขนาด",data: function(e) {
                  return e.product_size_name ;
                },
              },
              {
                title: "ราคาต่อหน่วย",data: function(e) {
                  return (e.price*1).toLocaleString(undefined, {minimumFractionDigits: 2});
                },
              },
              {
                title: "ราคาต่อหน่วย เพิ่มเติม",data: function(e) {
                  return ((e.additiona*1) - (e.price*1)).toLocaleString(undefined, {minimumFractionDigits: 2});
                },
              },

              {
                title: "น้ำหนัก",data: function(e) {
                  return e.product_weight  ;
                },
              },
              {
                title: "จำนวนสินค้าคืน / เคลม",data: function(e) {
                  return e.qty;
                },
              },
              {
                title: "หมายเหตุ",data: function(e) {
                  return e.note ;
                },
              },
            ],
          });

          var data_claim = {
            'order_id' : $("input[name$='order_id']").val(),
            'delivery_id' : $("input[name$='delivery_id']").val(),
            'data_claim_return' : data2_return,
          };


          claim_return.data = data_claim;

        }else {
          Swal.fire({icon: 'warning',title: 'เตือน!',text: "กรุณาเลือกสินค้า"})
        }


        event.preventDefault();
    });

    $("#btn_return_confirm").click(function(event) {

        $('#btn_return_confirm').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> กำลังโหลด...');
        $.ajax({
            url: "{{ url('api/claim/createOrderreturn') }}",
            type: "POST",
            data: claim_return.data
        }).done(function(res) {
            if (res.status == true) {
                let url_delivery_list = `{{ route("claim.list.order_claim", ":id") }}`.replace(':id', $('#order_id').val());
                Swal.fire('สำเร็จ!',res.message,'success').then(() => (window.location = url_delivery_list))
                // enabled button
                $('#btn_return_confirm').prop('disabled', false).html('ยืนยันทำรายการ');
            } else {
                Swal.fire({icon: 'warning',title: 'เตือน!',text: res.message})
                // enabled button
                $('#btn_return_confirm').prop('disabled', false).html('ยืนยันทำรายการ');
            }
        });
    });

    ////////////////////////////////////////////////////////// end return //////////////////////////////////////////////////////////////////////////////////
    $("#truck_type").change(function () {
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

</script>
<script>
  let check_qty_prduct = (e , max) =>{
    if (e.value > max) {
      e.value = max
    }
  }
</script>
@endsection
