@extends('layouts.app')

@section('content')
<style>
  .box-new-order {
    border-radius: 4px;
    border: 1px solid #ced4da;
  }
</style>
<div class="form-main" id="delivery_car">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <h2>จัดการใบสั่งซื้อ</h2>
      </div>
      <div class="col-12">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a onclick="window.history.go(-1); return false;">จัดการใบสั่งซื้อ</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">สร้างใบสั่งซื้อ</li>
          </ol>
        </nav>
      </div>
    </div>

    <div class="form " id="form_orders">
      <!-- order head -->
      <div class="col-md-12 pl-0 pr-0">
        <div class="card card-order">
          <div class="card-header">
            สร้างใบสั่งซื้อ
          </div>
          <div class="card-body">
            <div class="row  pb-1">
              <div class="col-md-8">
                <div class="col-md-12 pl-0 pr-0">
                  <label for="search_merchant" class="font-weight-bold">ชื่อร้านค้า :</label>
                  <input type="hidden" id="merchant_id">
                </div>

                <div class="row">
                  <div class="input-group input-group-sm col-md-6 pl-0 pr-0 custom-templates">
                    <input type="search" autocomplete="off" class="form-control border-right-0 border typeahead"
                      data-provide="typeahead" id="search_merchant">
                    <span class="input-group-append">
                      <div class="input-group-text "><i class="fa fa-search"></i></div>
                    </span>
                  </div>


                  <div class="col-md-5 ml-3 pl-0 pr-0 d-inline">
                    <button type="button" class="btn btn-sm  btn-outline-primary" id="create_cus"><i
                        class="fa fa-plus"></i> สร้างร้านค้า</button>
                    <button type="button" class="btn btn-sm  btn-outline-primary" style="display:none"
                      id="update_cus"><i class="fa fa-edit"></i>
                      แก้ไขราคา</button>
                  </div>

                </div>
              </div>
              <div class="col-md-4 grid-divider">
                <div class="row mt-2 ">
                  <label class="font-weight-bold col-md-5 pl-0 pr-0">จำนวนเงิน<br>รวมทั้งสิ้น
                    (บาท)</label>
                  <h2 class="font-weight-bold text-primary col-md-7 pl-0 pr-0 text-right grand_total_text">
                    0.00</h2>
                </div>
              </div>
              <div class="col-md-8 pt-2">
                <div class="col-md-12 pl-0 pr-0">
                  <label for="search_merchant" class="font-weight-bold">หน่วยงาน :</label>
                </div>
                <div class="row">
                  <div class="input-group input-group-sm col-md-6 pl-0 pr-0 custom-templates">
                    <input type="text" name="department_name" id="department_name"
                      class="form-control border-right-0 border typeahead">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- end order head -->

      <!-- merchant -->
      {{-- <div class="col-md-12 pl-0 pr-0"> --}}
        <div class="row mt-3">

          <div class="col-md-4">
            <div class=" ">
              <span class="  w-100 color-blue">ร้านค้า :</span>
              {{-- <span class="w-100" id="name_merchant"></span> --}}
              <input type="text" name="name_merchant" id="name_merchant" class="form-control col" disabled >
            </div>

            <div class=" mt-2">
              <span class="  w-100 color-blue">เบอร์โทรศัพท์ (หลัก) :</span>
              {{-- <span class=" w-100" id="phone_number"></span> --}}
              <input type="text" name="phone_number" id="phone_number" class="form-control col" disabled >
            </div>

            <div class=" mt-2">
              <span class="  w-100 color-blue">เบอร์โทรศัพท์  (สำรอง 3) :</span>
              {{-- <span class=" w-100" id="phone_number4"></span> --}}
              <input type="text" name="phone_number4" id="phone_number4" class="form-control col" disabled >
            </div>
          </div>

          <div class="col-md-4">
            <div class=" ">
              <span class=" w-100 color-blue">หน่วยงาน :</span>
              {{-- <span class="w-100" id="name_department">-</span> --}}
              <input type="text" name="name_department" id="name_department" class="form-control col" disabled >
            </div>

            <div class=" mt-2">
              <span class="  w-100 color-blue">เบอร์โทรศัพท์ (สำรอง 1) :</span>
              {{-- <span class=" w-100" id="phone_number2"></span> --}}
              <input type="text" name="phone_number2" id="phone_number2" class="form-control col" disabled >
            </div>
            
            <div class=" mt-2">
              <span class="  w-100 color-blue">เบอร์โทรศัพท์ (หน่วยงาน) :</span>
              {{-- <span class=" w-100" id="fax"></span> --}}
              <input type="text" name="phone_department" id="phone_department" class="form-control col" disabled >
            </div>
            
          </div>
          <div class="col-md-4">
            <div class="">
              <span class=" w-100 color-blue">เลขประจำตัวภาษีอากร :</span>
              {{-- <span class=" w-100" id="tax_number"></span> --}}
              <input type="text" name="tax_number" id="tax_number" class="form-control col" disabled >
            </div>
            
            <div class=" mt-2">
              <span class="  w-100 color-blue">เบอร์โทรศัพท์  (สำรอง 2) :</span>
              {{-- <span class=" w-100" id="phone_number3"></span> --}}
              <input type="text" name="phone_number3" id="phone_number3" class="form-control col" disabled >
            </div>

            <div class=" mt-2">
              <span class="  w-100 color-blue">Fax :</span>
              {{-- <span class=" w-100" id="fax"></span> --}}
              <input type="text" name="fax" id="fax" class="form-control col" disabled >
            </div>
            
          </div>

          <div class="col-md-12 address">
            <div class=" mt-2">
              <span class=" w-100 color-blue">ที่อยู่ :</span>
              {{-- <span class=" w-100" id="address"></span> --}}
              <input type="text" name="address" id="address" class="form-control " disabled >
            </div>
          </div>

          <div class="col-md-12 add_depart" style="display: none;">
            <div class=" mt-2">
              <span class=" w-100 color-blue">ที่อยู่ หน่วยงาน :</span>
              {{-- <span class=" w-100" id="address"></span> --}}
              <input type="text" name="address_department" id="address_department" class="form-control " >
            </div>
          </div>
          

        </div>
      {{-- </div> --}}
      <!-- end merchant -->
      <!-- help bar -->
      <div class="col-md-12 mt-3">
        <div class="row p-1 helpbar">
          <span class="font-weight-bold pl-2">สั่งสินค้า</span>
          <span class="font-weight-bold pl-4">1) เลือกสินค้า</span>
          <span class="s1"></span>
          <span class="font-weight-bold pl-5">2) เลือกประเภทสินค้า</span>
          <span class="s2"></span>
          <span class="font-weight-bold pl-5">3) เลือกขนาด</span>
          <span class="s3"></span>
        </div>
      </div>
      <!-- end help bar -->
      <!-- product -->
      <div class="col-md-12 pl-0 pr-0">
        @foreach($items['main'] as $key => $value)
        <button type="button" class="btn btn-product  btn-lg mr-4 mt-3 items-main"
          data-main-id="{{$value[0]->master_product_main_id}}" data-main-name="{{$key}}">{{$key}}</button>
        @endforeach
        <button type="button" class="btn btn-product btn-lg mr-4 mt-3 items-main other_item" data-main-id="0"
          data-main-name="อื่นๆ">อื่นๆ</button>
      </div>
      <!-- end product -->
      <!-- type -->
      <div class="col-md-12   pl-0 pr-0">
        @foreach($items['type'] as $key => $value)
        <button type="button" class="btn btn-type  btn-lg mr-4 mt-3 items-type" data-type-name="{{$key}}"
          data-type-id="{{$value[0]->master_product_type_id}}">{{$key}}</button>
        @endforeach
      </div>
      <!-- end type -->
      <!-- size -->
      <div class="col-md-12 pl-0 pr-0">
        @foreach($items['size'] as $key => $value)
        <button type="button" class="btn btn-size  btn-lg mr-4 mt-3 items-size" data-size-name="{{$key}}"
          data-typesize-id="{{$value[0]->master_product_type_id}}">{{$key}}</button>
        @endforeach
      </div>
      <!-- end size -->
      <!-- help bar -->
      <div class="col-md-12 mt-3">
        <div class="row p-1 helpbar">
          <div class="row  col-md-12 ml-3 mt-2 mb-2">
            <div class="col-md-8">
              <span class="font-weight-bold  w-100">สรุปรายการที่เลือก :</span>
              <div class="col-md-12 pl-0">
                <button type="button" class="btn btn-product btn-sm-1 mr-2  " id="select_main"></button>
                <button type="button" class="btn btn-type btn-sm-1 mr-2 " id="select_type"></button>
                <button type="button" class="btn btn-size btn-sm-1 mr-2  " id="select_size"></button>

              </div>
            </div>
            <div class="col-md-4 grid-divider-odd">
              <div class="row mt-2 ">
                <label class="font-weight-bold col-md-6">ระบุจำนวนสินค้า<br>ที่ต้องการสั่ง</label>
                <div class="input-group input-group-lg col-md-5">
                  <input type="number" class="form-control color-blue-1" id="select_qty" value="1" min="1">
                </div>
              </div>
            </div>

          </div>
          <div class="row  col-md-12 ml-3 mt-2 mb-2">
            <div class="col-md-2 pr-0">
              <input type="number" class="form-control  number" id="select_price" value="" min="0"
                placeholder="ราคาต่อหน่วย	">
            </div>
            <div class="col-md-2 pr-0">
              <input type="number" class="form-control  number" id="select_addition_price_per_unit" value="" min="0"
                placeholder="ราคาเพิ่มเติมต่อหน่วย	">
            </div>
            <div class="col-md-4 pr-0">
              <input type="text" class="form-control " id="select_remark" placeholder="หมายเหตุสินค้า..." max="191">
            </div>
            <div class="col-md-4 float-right">
              <div class="float-right mr-4">
                <a href="javascript:void(0)" class="mr-2 text-decoration" id="clear_selects">ล้างข้อมูล</a>
                <button type="button" class="btn btn-success   " id="add_cart">สั่งรายการนี้</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- end select product -->
      <!-- list cart -->
      <div class="col-md-12 mt-3">
        <div class="row ">
          <table class="table table-border-order">
            <thead class="bg-light-1">
              <tr>
                <th scope="col">#</th>
                <th scope="col">สินค้า</th>
                <th scope="col">ประเภทสินค้า</th>
                <th scope="col">ขนาดความยาว</th>
                <th scope="col" class="text-right">จำนวนตารางเมตร</th>

                <th scope="col" class="text-right ">ราคา</th>
                <th scope="col" class="text-right">ราคาเพิ่มเติม</th>
                <th scope="col" class="text-center">จำนวนที่สั่ง</th>
                <th scope="col" class="text-right">น้ำหนัก</th>
                <th scope="col" class="text-right">รวมเป็นเงิน</th>
                <th scope="col">หมายเหตุ</th>
              </tr>
            </thead>
            <tbody id="order_items">

            </tbody>
            <tfoot>
              <tr class="bg-light-1">
                <th colspan="11" class="text-center">รายการสินค้าที่สั่งซื้อทั้งหมด <span id="total_item"
                    class="color-blue-1">0</span> รายการ
                  น้ำหนักสินค้าที่สั่งซื้อทั้งหมด <span id="total_weight" class="color-blue-1">0</span> <span
                    id="weight_type">กรัม</span></th>


              </tr>
            </tfoot>
          </table>
        </div>
      </div>
      <!-- end list cart -->
      <!-- summary -->
      <div class="col-md-12">
        <div class="row p-1">
          <div class="col-md-7 box-new-order">
            <div class="col-md-10">
              <div class="row form-row mb-4 mt-4">
                <label class="control-label col-md-3">วันที่จัดส่ง :</label>
                <div class="col-md-6">
                  <input type="text" autocomplete="off" class="form-control date" required id="datepicker"
                    name="datemarksend" value="">
                </div>
              </div>
              <div class="row form-row">
                <label class="control-label col-md-3">หมายเหตุบิล :</label>
                <textarea type="text" class="form-control col-md-9" id="noteorder" name="noteorder"></textarea>
              </div>
            </div>
          </div>
          <div class="col-md-5">
            <div class="row p-1">
              <span class="font-weight-bold col-md-5">รวมเป็นเงิน :</span>
              <span class="font-weight-bold text-right col-md-7 total_price_text">0.00</span>
            </div>
            @hasanyrole('super-admin|manager')
            <div class="row p-1">
              <div class="btn-group btn-group-toggle col-md-12" data-toggle="buttons">
                <label class="btn btn-primary-1 active" id="type_00">
                  <input type="radio" name="order_type" id="order_type_00" value="00" checked="checked"> ใบสั่งซื้อปกติ
                </label>
                <label class="btn btn-primary-1 " id="type_01">
                  <input type="radio" name="order_type" id="order_type_01" value="01"> ใบสั่งซื้อเหมา
                </label>

              </div>
            </div>
            @else
            <div class="row p-1">
              <div class="btn-group btn-group-toggle col-md-12" data-toggle="buttons">
                <label class="btn btn-primary-1 active" id="type_00">
                  <input type="radio" name="order_type" id="order_type_00" value="00" checked="checked">
                  เลือกภาษีมูลค่าเพิ่ม
                </label>
              </div>
            </div>
            @endhasanyrole
            <div class="row p-1">
              <span class="font-weight-bold col-md-5">รวมเป็นเงิน :</span>
              <span class="font-weight-bold text-right col-md-7 total_price_text">0.00</span>
            </div>
            <div class="row p-1">
              <span class="font-weight-bold col-md-5">ภาษีมูลค่าเพิ่ม <span
                  class="order_vat_text">{{$defaultVat->vat}}</span>% :</span>

              <span class="font-weight-bold text-right col-md-7 total_vat_text">0.00</span>
            </div>
            <div class="row p-1">
              <span class="font-weight-bold col-md-5">จำนวนเงินรวมทั้งสิ้น :</span>
              <span class="font-weight-bold text-right col-md-7 grand_total_text">0.00</span>
            </div>

          </div>
        </div>
      </div>
      <hr>
      <!-- end summary -->
      <!-- submit -->
      <div class="col-md-12">
        <div class="row p-1 ">
          <div class="mx-auto col-md-4">
            <button type="button" class="btn btn-outline-dark  py-2 px-4 " id="cancel_order"><i
                class="fas fa-times-circle"></i> ยกเลิกรายการ</button>
            <button type="button" class="btn btn-primary-2 ml-2 py-2 px-4 " id="create_cart">จัดการบิลย่อย <i
                class="fas fa-chevron-right "></i></button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@include('pages.orders.modal')
@include('pages.orders.create_merchant')

@include('pages.orders.addother')
@include('pages.loading')

<!-- Modal Update Merchant-->
<div class="modal fade" id="modal_update_cus" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">

    <div class="modal-content">

      <div class="modal-header none-border-bottom padding-modal">
        <h5 class="modal-title frm_profile">สร้างข้อมูลร้านค้า</h5>
        <h5 class="modal-title frm_price">กำหนดราคาขายสินค้า</h5>
      </div>
      <div class="modal-body">
        <form id="frm_updatemerchant">
          <div class="frm_price ">
            @foreach($products as $row)
            <div class="row px-3">
              <div class="col-md-12 bg-light-1 rounded p-2">
                <span class="text-dark ">ชื่อสินค้า :</span> <span class="color-blue-1">{{$row->name}}</span>
              </div>
              <div class="row px-3">
                <table class="table table-borderless ">
                  <thead>
                    <tr>
                      <th scope="col">ประเภทสินค้า</th>
                      <th scope="col">ขนาดสินค้า</th>
                      <th scope="col">ราคาขาย : หน่วย</th>

                    </tr>
                  </thead>
                  <tbody>
                    @foreach($row->spec as $value)
                    <tr>
                      <td>
                        <input type="text" class="form-control p-1" value="{{$value->type_name}}" readonly>
                      </td>
                      <td>
                        <input type="text" class="form-control p-1"
                          value="{{ $row->formula == '01' ? '0.35x':''}}{{sprintf('%g',$value->size_name)}} {{$items['size_unit'][$value->size_unit]}}"
                          readonly>
                      </td>
                      <td>
                        <input type="hidden"
                          name="u_id[{{$value->master_product_main_id}}][{{ isset($value->master_product_type_id) ? $value->master_product_type_id :'0'}}][{{$value->master_product_size_id}}]"
                          value="0">
                        <input type="number" class="form-control number text-right"
                          name="u_price[{{$value->master_product_main_id}}][{{ isset($value->master_product_type_id) ? $value->master_product_type_id :'0'}}][{{$value->master_product_size_id}}]">
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            @endforeach
        </form>
      </div>
      <!-- end frm_price -->
    </div>
    <div class="modal-footer none-border-top justify-content-center">
      <button type="button" class="btn btn-danger px-4" data-dismiss="modal">ยกเลิก</button>
      <button type="button" id="btn_frm_updatemerchant" class="btn btn-success px-5 frm_price">ยืนยันทำรายการ</button>
    </div>

  </div>

</div>
</div>

@endsection

@section('js')

<script type="text/javascript">
  $('#datepicker').datepicker({
        uiLibrary: 'bootstrap4',
        format: 'dd-mm-yyyy',
        minDate: moment().format('D-M-Y')
    });

	//order
var master_product_json = {!! json_encode($items) !!};
	//   console.log(master_product_json);

// var master_product_main_id='';
// var master_product_type_id='';
// var master_product_size_id='';
var selects={main:null,type:null,size:null,price:0,price_add:0,total_price:0,total_size:0,size_unit:'',qty:0,remark:'',weight_total:0};
var merchant=null;
var merchant_price=null;
var carts=[];
var carts_price=null;
var order_type='00';
var order_vat_no=null;
var order_status_vat=null;
var url_autocomplete = '{{ url("api/orders/autocomplete") }}';
var url_price = '{{ url("api/orders/getPrice") }}';

$('#select_main').hide();
$('#select_type').hide();
$('#select_size').hide();

getlocalStorage();
clearSelects();
$('.items-type').prop('disabled', true);
$('.items-size').prop('disabled', true);
$('.items-main').click(function()
{
	var id=$(this).data('main-id');
	var name=$(this).data('main-name');
	// console.log(id);
	// console.log(name);
	clearBtnType();
		clearBtnSize();
		clearSelects();
	if(id ==0){
		$(this).addClass( "active" );
	$(this).removeClass( "btn-disable" );
	setBtnActive(id,'.items-main','main','btn-disable');
	SetDisableBtnTypeNoneSelect(id,name);
	SetDisableBtnSizeNoneSelect(id,name);
		return;
	}
    if(merchant == null){
        alertError('กรุณาเลือกร้านค้า');
    }else{
        selects['type']=null;
        selects['size']=null;
        $(this).addClass( "active" );
        $(this).removeClass( "btn-disable" );
        setBtnActive(id,'.items-main','main','btn-disable');
        SetDisableBtnTypeNoneSelect(id,name);
        SetDisableBtnSizeNoneSelect(id,name);
        selects['main']=JSON.parse(JSON.stringify(master_product_json['main'][name][0]));
        if(selects['main']['status_detail'] =='01'){
            selects['type']=null;
            selects['size']=null;
        }
        $('#select_main').html(name);
        $('#select_main').show();
	// console.log(selects);
		}
});
$('.items-type').click(function()
{
	var id=$(this).data('type-id');
	var name=$(this).data('type-name');
	clearActiveBtnSize();
	$(this).addClass( "active" );
	$(this).removeClass( "btn-disable" );
	setBtnActive2(id,name,'.items-type','type','btn-disable',selects['main'].master_product_main_id);
	if(selects['main'] !=null){
	$('#select_type').html(name);
	$('#select_type').show();
	selects['size']=null;
	$('#select_size').hide();
	}

	$('#select_remark').val(name);


});

$('.items-size').click(function()
{
	var id=$(this).data('size-id');
	var name=$(this).data('size-name');
	// console.log(id);
	// console.log(name);
	$(this).addClass( "active" );
	$(this).removeClass( "btn-disable" );
	setBtnActive2(id,name,'.items-size','size','btn-disable',selects['main'].master_product_main_id);
	if(selects['main'] !=null){
		if(selects['main']['status_detail'] =='00' && selects['type'] !=null || selects['main']['status_detail'] =='01' ){
	$('#select_size').html(name);
	$('#select_size').show();
	try {


	if($.isEmptyObject(merchant_price[selects['size']['master_product_size_id']]) == false){
	selects['price']=merchant_price[selects['size']['master_product_size_id']]['price'];
	if(selects['price']>0){
	$('#select_price').val(selects['price']);
	}else{
		$('#select_price').val('');
	}
	}
} catch (error) {
	alertError('items-size action click | status_detail invalid | Size : '+name+' | '+error);
	}
		}
		// console.log(merchant_price);
		// console.log(selects);
	}

});

function setBtnActive(curr_id,class_name,target,new_class){
	$.each($(class_name), function( index, value ) {


		var id=$(this).data(target+'-id');
			var name=$(this).data(target+'-name');
			if(curr_id != id){
				$(this).removeClass( "active" ).addClass(new_class+'-'+target );

		}else{
			$(this).removeClass(new_class+'-'+target).addClass( "active" );


		}
			// console.log(id);
			// console.log(name);

	});

}
function setBtnActive2(curr_id,curr_name,class_name,target,new_class,main_product_id){

	$.each($(class_name), function( index, value ) {
			var name=$(this).data(target+'-name');
			var idtarget=$(this).data(target+'-id');

			if(curr_name != name){
			    $(this).removeClass( "active" ).addClass(new_class+'-'+target );
			}else{

				$(this).removeClass(new_class+'-'+target).addClass( "active" );
				if(selects['main'] ==null){
					alertError('กรุณาเลือกสินค้า');
				}else
				if(target=='size' && selects['main']['status_detail'] == '00' && selects['type']==null  ){
					alertError('กรุณาเลือกประเภทสินค้า');
				}else{
                    if(target == 'type')
                    {
                        setBtnsizeShow(curr_id,curr_name,main_product_id)
                        // $.each($('.items-size'),function(k,v){
                        //     if(idtarget != $(this).data('typesize-id'))
                        //     {
                        //         $(this).prop('disabled', true);
                        //     }else{
                        //         $(this).prop('disabled', false);
                        //     }
                        // })
                    }

					selects[target]=getValue(target,master_product_json[target][name]);
				}

			}
			// console.log(name);

	});

}

function setBtnsizeShow(curr_id,curr_name,main_product_id){

    $('.items-size').prop('disabled', true);
    $.ajax({
		url: '{{ route("oredrs.itemssize") }}',
		type: "get",
		data: {curr_name : curr_name,main_product_id:main_product_id}
	}).done(function(res) {
        $.each(res.name.size,function(k,v){
            $('.items-size[data-size-name="'+v+'"]').prop('disabled', false);
        });
	});
}
function SetDisableBtnTypeNoneSelect(curr_id,curr_name){
	$.each(master_product_json['type'], function( index, value ) {

		var curr_btn= $(".items-type[data-type-name='"+index+"']");
		var isFind=false;

	$.each(value, function( index1, value1 ) {

		if(value1['master_product_main_id'] ==curr_id){
		isFind=true;
		}
	});

	if(!isFind){
		curr_btn.attr("disabled", true);
	}else{
		curr_btn.attr("disabled", false);
	}
	});
}

function SetDisableBtnSizeNoneSelect(curr_id,curr_name){
    $('.items-size').prop('disabled', true);
    $.ajax({
		url: '{{ route("oredrs.itemssizenontype") }}',
		type: "get",
		data: {curr_id : curr_id}
	}).done(function(res) {
        $.each(res.name.size,function(k,v){
            $('.items-size[data-size-name="'+v+'"]').prop('disabled', false);
        });
	});

}

// backup code vre by  p'pong
// function SetDisableBtnSizeNoneSelect(curr_id,curr_name){
// 	$.each(master_product_json['size'], function( index, value ) {

// 		var curr_btn= $(".items-size[data-size-name='"+index+"']");
// 			var isFind=false;

// 			$.each(value, function( index1, value1 ) {


// 				if(value1['master_product_main_id'] ==curr_id){
// 				isFind=true;
// 				}
// 			});

// 			if(!isFind){
// 				curr_btn.attr("disabled", true);
// 			}else{
// 				curr_btn.attr("disabled", false);
// 			}
// 	});
// }


function getValue(type,data){
	var result=null;
	var	id=	selects['main']['master_product_main_id'];
	var	status_detail=selects['main']['status_detail'];
	if(status_detail == '00' && type=='size'){
	$.each(data, function( index, value ) {
		if(value['master_product_main_id'] == id && value['master_product_type_id'] == selects['type']['master_product_type_id']){
			result =value;
		}
	});
	}else{
		$.each(data, function( index, value ) {
			if(value['master_product_main_id'] == id){
				result =value;
			}
		});
	}
	return JSON.parse(JSON.stringify(result));
}
function clearBtnMain(){

	$.each($(".items-main"), function( index, value ) {
		$(this).attr("disabled", false);
		$(this).removeClass( "active" );
		$(this).removeClass( "btn-disable-main" );
	});

}
function clearBtnType(){

	$.each($(".items-type"), function( index, value ) {
		$(this).attr("disabled", false);
		$(this).removeClass( "active" );
		$(this).removeClass( "btn-disable-type" );
	});

}
function clearBtnSize(){
	$.each($(".items-size"), function( index, value ) {
		$(this).attr("disabled", false);
		$(this).removeClass( "active" );
		$(this).removeClass( "btn-disable-size" );
	});

}
function clearActiveBtnSize(){
	$.each($(".items-size"), function( index, value ) {

		$(this).removeClass( "active" );
		$(this).removeClass( "btn-disable-size" );
	});

}
function clearSelects(){
	$('#select_main').hide();
	$('#select_main').html('');
	$('#select_type').hide();
	$('#select_type').html('');
	$('#select_size').hide();
	$('#select_size').html('');
	$('#select_amount').val(1);
	$('#select_remark').val('');
	$('#select_price').val('');
	$('#select_addition_price_per_unit').val('');
	$('#select_qty').val(1);
}

function setMerchant(item){
	merchant=item;
	localStorage.setItem('merchant', JSON.stringify(item))
	  //  $('#name_merchant').html(item.name_merchant);
	  //  $('#phone_number').html(item.phone_number);
	  //  $('#phone_number2').html(item.phone_number2);
	  //  $('#phone_number3').html(item.phone_number3);
	  //  $('#phone_number4').html(item.phone_number4);
	  //  $('#fax').html(item.fax);
	  // //  $('#name_department').html(item.name_department);
	  //  $('#tax_number').html(item.tax_number);
	  //  $('#address').html(item.address);
	  //  $('#update_cus').css("display", "inline-block");


     $('#name_merchant').val(item.name_merchant);
	   $('#phone_number').val(item.phone_number);
	   $('#phone_number2').val(item.phone_number2);
	   $('#phone_number3').val(item.phone_number3);
	   $('#phone_number4').val(item.phone_number4);
	   $('#fax').val(item.fax);
	  //  $('#name_department').html(item.name_department);
	   $('#tax_number').val(item.tax_number);
	   $('#address').val(item.address);

	   $.get(url_price, { id: item.master_merchant_id }, function (data) {
		  merchant_price= data;
     });
}

//key department
$('#department_name').keyup(function(item){
    // $('#name_department').html($(this).val());
    $('#name_department').val($(this).val());
    if ($(this).val() != "") {
      // $('.address').prop( "disabled", false );
      $('.address').hide();
      $('.add_depart').css('display','block');
      $('#phone_department').prop( "disabled", false );
    }
    else{
      // $('.address').prop( "disabled", true );
      $('.address').show();
      $('.add_depart').css('display','none');
      $('#phone_department').prop( "disabled", true );
    }


})

function getlocalStorage(){
	merchant=	($.isEmptyObject(localStorage.getItem('merchant')) ==false ) ? JSON.parse(localStorage.getItem('merchant')) : null;
	carts=($.isEmptyObject(localStorage.getItem('carts')) ==false ) ? JSON.parse(localStorage.getItem('carts')) :[];
	carts_price=($.isEmptyObject(localStorage.getItem('carts_price')) ==false ) ? JSON.parse(localStorage.getItem('carts_price')): {total_price:0,vat:0,grand_total:0,total_weight:0,total_item:0,qty_all:0};
	order_type=($.isEmptyObject(localStorage.getItem('order_type')) ==false ) ? localStorage.getItem('order_type') : '00';
	order_vat_no=($.isEmptyObject(localStorage.getItem('order_vat_no')) ==false ) ? localStorage.getItem('order_vat_no') : {{$defaultVat->vat}};
	order_status_vat=($.isEmptyObject(localStorage.getItem('order_status_vat')) ==false ) ? localStorage.getItem('order_status_vat') : '00';

	if(merchant !=null){
	  //  $('#name_merchant').html(merchant.name_merchant);
	  //  $('#phone_number').html(merchant.phone_number);
	  //  $('#name_department').html(merchant.name_department);
	  //  $('#tax_number').html(merchant.tax_number);
	  //  $('#address').html(merchant.address);

     $('#name_merchant').val(merchant.name_merchant);
	   $('#phone_number').val(merchant.phone_number);
	   $('#name_department').val(merchant.name_department);
	   $('#tax_number').val(merchant.tax_number);
	   $('#address').val(merchant.address);

	   $('#update_cus').css("display", "inline-block");
	   $.get(url_price, { id: merchant.master_merchant_id }, function (data) {
		merchant_price= data;
		// console.log(merchant_price);
            });
		// console.log(merchant);
	}
	$('.total_price_text').html(numeral(carts_price['total_price']).format('0,0.00'));
	$('.total_vat_text').html(numeral(carts_price['vat']).format('0,0.00'));
		 $('.grand_total_text').html(numeral(carts_price['grand_total']).format('0,0.00'));
	 	$('#total_item').html(numeral( carts_price['total_item']).format('0,0'));
		var total_weight=convertWeightUnit( carts_price['total_weight']);
		$('#total_weight').html(total_weight['number']);
		$('#weight_type').html(total_weight['unit']);
		if(order_type =='01'){
			$('#order_type_00').removeAttr("checked");
			$('#order_type_01').attr('checked', 'checked');
			$('#type_00').removeClass( "active" );
			$('#type_01').addClass( "active" );
		}else{
			$('#order_type_00').attr('checked', 'checked');
			$('#order_type_01').removeAttr("checked");
			$('#type_00').addClass( "active" );
			$('#type_01').removeClass( "active" );
		}

		if(order_status_vat =='01'){
			$('#show_vat').hide();
            $('#order_type_00_vat').removeAttr("checked");
			$('#order_type_00_none').attr('checked', 'checked');
			$('#type_00_vat').removeClass( "active" );
			$('#type_00_none').addClass( "active" );

		}else{
			$('#show_vat').show();
            $('#order_type_00_vat').attr('checked', 'checked');
			$('#order_type_00_none').removeAttr("checked");

			$('#type_00_vat').addClass( "active" );
			$('#type_00_none').removeClass( "active" );
			$("input[name=vat][value='" + order_vat_no + "']").attr('checked', 'checked');
		}

		$('.order_vat_text').html(order_vat_no);
		calPrice();
		$.each(carts, function( index, value ) {
		addItemDisplay(index);

		});
}
$('#clear_selects').click(function(){
	clearSelects();
	clearBtnSize();
	clearBtnType();
	clearBtnMain();
	selects={main:null,type:null,size:null,price:0,price_add:0,total_price:0,total_size:0,size_unit:'',qty:0,remark:'',weight_total:0};
});

$('#search_merchant').typeahead({
	source:  function (term, process) {
	return $.get(url_autocomplete, { term: term }, function (data) {
			return process(data);
		});
	},
	autoSelect: true,
	displayText: function(item) {
		if(item.name_department){
			return item.name_merchant;
		}else{
			return item.name_merchant;
		}
		},
		afterSelect: function(item) {
			setMerchant(item);
		},
});

$("#add_cart").click(function() {
  var check_size_id = false;
  // console.log('carts',carts);
  // console.log('select',selects);
  // console.log('master_product_size_id = '+selects['size']['master_product_size_id']);
  $.each(carts, function(index, value) {
    // console.log('value = '+value['size']['master_product_size_id']);
    if(selects["size"] != null)
    {
        if (
        value["size"]["master_product_size_id"] ==
        selects["size"]["master_product_size_id"]
        ) {
        check_size_id = true;
        }
    }
  });

  selects["price"] = $("#select_price").val();
  if (selects["size"] == null) {
    alertError("กรุณาเลือกสินค้า");
    return;
  } else if (check_size_id == true) {
    alertError("ไม่สามารถเลือกสินค้าซ้ำซ้อนกันได้");
    return;
  } else if (selects["price"] == "") {
    alertError("กรุณากรอกราคาต่อหน่วย");
    return;
  }
  selects["price_add"] = $("#select_addition_price_per_unit").val();
  if (selects["price_add"] == "") {
    selects["price_add"] = 0;
  }
  selects["qty"] = $("#select_qty").val();

  if (selects["size"]["formula"] == "01") {
    selects["total_size"] = (0.35 * selects["size"]["size"]).toFixed(4);
    selects["total_price"] = (
      selects["total_size"] *
      (parseFloat(selects["price"]) + parseFloat(selects["price_add"])) *
      selects["qty"]
    ).toFixed(2);
  } else {
    selects["total_size"] = (selects["size"]["size"]*1).toFixed(2);
    selects["total_price"] = (
      (parseFloat(selects["price"]) + parseFloat(selects["price_add"])) *
      selects["total_size"] *
      selects["qty"]
    ).toFixed(2);
  }
  selects["size_unit"] = selects["size"]["size_unit"];
  selects["remark"] = $("#select_remark").val();
  selects["weight_total"] = selects["size"]["weight"] * selects["qty"];

  carts.push(JSON.parse(JSON.stringify(selects)));
  localStorage.setItem("carts", JSON.stringify(carts));
  clearSelects();
//   selects =
  clearBtnSize();
  clearBtnType();
  clearBtnMain();
  calPrice();
  addItemDisplay(carts.length - 1);
  $('.items-type').prop('disabled', true);
    $('.items-size').prop('disabled', true);
});

function calPrice() {
  var total_price = 0;
  var total_weight = 0;
  var qty_all = 0;
  $.each(carts, function(index, value) {
    total_price += parseFloat(value["total_price"]);
    total_weight += parseFloat(value["weight_total"]);
    qty_all += parseInt(value["qty"]);
  });
  carts_price["total_price"] = total_price.toFixed(2);
  carts_price["qty_all"] = qty_all;
  $(".total_price_text").html(numeral(total_price).format("0,0.00"));
  var vat_no = order_vat_no;
  var vat = total_price * (vat_no / 100);
  carts_price["vat"] = vat.toFixed(2);
  carts_price["grand_total"] = (total_price + vat).toFixed(2);
  $(".total_vat_text").html(numeral(vat).format("0,0.00"));
  $(".grand_total_text").html(
    numeral(carts_price["grand_total"]).format("0,0.00")
  );
  carts_price["total_item"] = carts.length;
  carts_price["total_weight"] = total_weight;
  $("#total_item").html(numeral(carts_price["total_item"]).format("0,0"));
  var total_weight = convertWeightUnit(carts_price["total_weight"]);
  $("#total_weight").html(total_weight["number"]);
  $("#weight_type").html(total_weight["unit"]);
  //  console.log(total_price);
  // console.log(total_price_text);
  localStorage.setItem("carts_price", JSON.stringify(carts_price));
}

function addItemDisplay(i) {
  var item = carts[i];
  var total_weight = convertWeightUnit(item["weight_total"]);
  var no = i + 1;
  var type_name =
    $.isEmptyObject(item["type"]) == false ? item["type"]["type_name"] : "";
  var total_size =
    item["size"]["formula"] == "01"
      ? numeral(item["total_size"]).format("0,0.0000") + " ตร.ม."
      : numeral(item["total_size"]).format("0,0.00") +
        " " +
        master_product_json["size_unit"][item["size_unit"]];
  var html =
        '<tr  class="item_' +
        i +
        '">' +
        ' <th scope="row">' +
        no +
        "</th>" +
        '<td class="color-blue-1">' +
        item["main"]["product_name"] +
        "</td>" +
        '<td class="color-blue-1">' +
        type_name +
        "</td>" +
        '<td class="color-blue-1">' +
        item["size"]["size_name"] +
        "</td>" +
        '<td  class="text-right total_size">' +
        // (item["formula"] != "00" ? '-' : total_size) +
        (item["size"]["formula"]  != "01" ? '-' : total_size) +
        "</td>" +
        '<td class="text-right"> <div class="col-10 float-right p-0"><input type="number" class="form-control form-control-sm text-right number " name="price_per_unit" value="' +
        item["price"] +
        '" min="0.01" onblur="updateItem(' +
        i +
        ')"></div></td>' +
        '<td class="text-right"> <div class="col-10 float-right p-0"><input type="number" class="form-control form-control-sm text-right number " name="addition_price_per_unit"  value="' +
        item["price_add"] +
        '" min="0" onblur="updateItem(' +
        i +
        ')"></div></td>' +
        '<td class="text-center color-blue-1"><div class="col-8 float-right p-0"><input type="number" class="form-control form-control-sm text-right number " name="qty"  value="' +
        item["qty"] +
        '" min="1" onblur="updateItem(' +
        i +
        ')"></div></td>' +
        '<td  class="text-right total_weight">' +
        total_weight["number"] +
        " " +
        total_weight["unit"] +
        "</td>" +
        '<td  class="text-right total_price">' +
        numeral(item["total_price"]).format("0,0.00") +
        "</td>" +
        '<td> <div class="form-group row p-0">' +
        '<div class="col-md-9">' +
        '<input type="text" class="form-control form-control-sm p-0 s_remark_1 " name="remark" max="191" value="' +
        item["remark"] +
        '" onblur="updateItem(' +
        i +
        ')">' +
        "</div>" +
        '<div class="col-md-2">' +
        '<button type="button" class="btn btn-outline-light p-0" onclick="removeItem(' +
        i +
        ')"><i class="fas fa-times-circle text-danger fa-lg"></i></button>' +
        "</div></div></td></tr>";

  $("#order_items").append(html);
}
function updateItem(i) {
  var item = carts[i];
  var row_input = [];
  $(".item_" + i)
    .find("td input")
    .each(function() {
      textVal = this.value;
      inputName = $(this).attr("name");
      row_input[inputName] = textVal;
    });

  item["price"] = row_input["price_per_unit"];
  item["price_add"] = row_input["addition_price_per_unit"];
  if (item["price_add"] == "") {
    item["price_add"] = 0;
  }
  item["qty"] = row_input["qty"];

  if (item["size"]["formula"] == "01") {

	console.log(item["size_unit"]);
    item["total_size"] = (0.35 * item["size"]["size"]).toFixed(4);
    item["total_price"] = (
      item["total_size"] *
      (parseFloat(item["price"]) + parseFloat(item["price_add"])) *
      item["qty"]
    ).toFixed(2);
  } else {
    item["total_size"] = item["size"]["size"];
    item["total_price"] = (
      (parseFloat(item["price"]) + parseFloat(item["price_add"])) *
      item["total_size"] *
      item["qty"]
    ).toFixed(2);
  }

  item["remark"] = row_input["remark"];
  item["weight_total"] = item["size"]["weight"] * item["qty"];
  var total_size =
    item["size"]["formula"] == "01"
      ? numeral(item["total_size"]).format("0,0.0000") + " ตร.ม."
      : numeral(item["total_size"]).format("0,0.00") +
        " " +
        master_product_json["size_unit"][item["size_unit"]];

  $(".item_" + i)
    .find(".total_size")
    .html(total_size);
  $(".item_" + i)
    .find(".total_price")
    .html(numeral(item["total_price"]).format("0,0.00"));
  var total_weight = convertWeightUnit(item["weight_total"]);
  $(".item_" + i)
    .find(".total_weight")
    .html(total_weight["number"] + " " + total_weight["unit"]);
  // 	console.log(row_input);
  // console.log(item);
  // console.log(carts);

  calPrice();
  localStorage.setItem("carts", JSON.stringify(carts));
}
function convertWeightUnit(weight_g) {
  if (weight_g < 1000)
    return { number: numeral(weight_g).format("0,0.00"), unit: "กรัม" };
  else
    return {
      number: numeral(weight_g / 1000).format("0,0.00"),
      unit: "กิโลกรัม"
    };
}
function removeItem(i) {
  carts.splice(i, 1);
  calPrice();
  localStorage.setItem("carts", JSON.stringify(carts));
  $("#order_items tr").remove();
  $.each(carts, function(index, value) {
    addItemDisplay(index);
  });
}
function clearOrder() {
  localStorage.removeItem("carts");
  localStorage.removeItem("order_type");
  localStorage.removeItem("carts_price");
  localStorage.removeItem("order_vat_no");
  localStorage.removeItem("order_status_vat");
  localStorage.removeItem("merchant");
  $("#search_merchant").val("");
}

$("#cancel_order").click(function() {
  $("#cancen_modal").modal("show");
});
$("#act_cancel").click(function() {
  clearOrder();
  window.location = "/orders/index";
});

$("#create_cart").click(function() {
  var check_price = true;
  var check_qty = true;
  var check_price_no = "";
  var check_qty_no = "";
  var datemarksend = $('[name="datemarksend"]').val()
  $.each(carts, function(index, value) {
    if (parseFloat(value["price"]) == 0) {
      check_price_no += index + 1 + ",";
      check_price = false;
    }
    if (parseFloat(value["qty"]) == 0) {
      check_qty_no += index + 1 + ",";
      check_qty = false;
    }
  });
  if (check_price == false) {
    alertError(
      "กรุณาใส่ราคาต่อหน่วย ในรายการที่ " +
        check_price_no.substr(0, check_price_no.length - 1)
    );
  } else if (check_qty == false) {
    alertError(
      "กรุณาใส่จำนวนที่สั่ง ในรายการที่ " +
        check_qty_no.substr(0, check_qty_no.length - 1)
    );
  } else if (carts.length == 0) {
    alertError("กรุณาเลือกสินค้า");
  } else if (merchant == null) {
    $("#name").addClass("input-error");
    alertError("กรุณาเลือกร้านค้า");
  } else if(datemarksend == ''){
    alertError("กรุณาเลือกวันที่จัดส่ง");
  } else {
    Swal.fire({
      title: "ยืนยันการทำรายการ?",
      text: "ท่านยืนยันการทำรายการนี้",
    //   icon: "warning",
	  reverseButtons: true,
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "ยืนยัน",
      cancelButtonText: "ยกเลิก"
    }).then(result => {
      if (result.value) {
        $("#loading_page").modal(
          {
            backdrop: "static"
          },
          "show"
        );
        $.ajax({
          url: '{{ url("orders/create") }}',
          type: "POST",
          data: {
            _token: "{{ csrf_token() }}",
            master_merchant_id: merchant["master_merchant_id"],
            carts: carts,
            total_price: carts_price["total_price"],
            vat: carts_price["vat"],
            grand_total: carts_price["grand_total"],
            total_weight: carts_price["total_weight"],
            qty_all: carts_price["qty_all"],
            order_vat_no: order_vat_no,
            order_status_vat: order_status_vat,
            order_type: order_type,
            department_name: $('#department_name').val(),
            datemarksend: datemarksend,
            noteorder: $('#noteorder').val(),
            department_status: $('#department_name').val() == "" ? "00":"01" ,
            phone_department: $('#phone_department').val(),
            address_department: $('#address_department').val(),
          }
        }).done(function(res) {
          $("#loading_page").modal("hide");
          if (res.success == true) {
            // Swal.fire({ type: 'success', title: 'สำเร็จ', text: 'บันทึกข้อมูลสำเร็จ' }).then(
            // () => {
            clearOrder();
            window.location =
              "/delivery/list/delivery/" +
              res.order_id +
              "/send/" +
              res.order_delivery_id +
              "?ref=order";
            //  })
          } else {
            var error = "";
            $.each(res.errors, function(index, value) {
              error += value + "<br>";
            });
            alertError(error);
          }
        });
      }
    });
  }
});
function reset_error_input() {
  $("#role").removeClass("input-error");
  $("#email").removeClass("input-error");
  $("#password").removeClass("input-error");
  $("#re_password").removeClass("input-error");
}
function alertError(text) {
    console.log(text);
  Swal.fire({
    type: "warning",
    title: "แจ้งเตือน",
    icon: "warning",
    dangerMode: true,
    html: text
  });
}
// end order
//view create
$("#create_cus").click(function() {
  clearInputCreate();
  $(".frm_price").hide();
  $(".frm_profile").show();
  $("#modal_create_cus").modal({ backdrop: "static" }, "show");
});
// ####################################################################################################################################################################
$("#update_cus").click(function() {
	getMasterMerchantPrice(merchant.master_merchant_id).then(function(data) {
		data.forEach(element => {
			$(`input[name="u_id[${element.master_product_main_id}][${element.master_product_type_id}][${element.master_product_size_id}]"]`).val(`${element.master_merchant_price_id}`)
			$(`input[name="u_price[${element.master_product_main_id}][${element.master_product_type_id}][${element.master_product_size_id}]"]`).val(`${element.price}`)
		});
  		$("#modal_update_cus").modal({ backdrop: "static" }, "show");
    });
});
let getMasterMerchantPrice = (master_merchant_id) => {
    return new Promise((resolve, reject) => {
        if (master_merchant_id) {
            let url_getMasterMerchantPrice = `{{ route("orders.getMasterMerchantPrice", ':id' ) }}`.replace(':id', master_merchant_id);
            $.ajax({
                url: url_getMasterMerchantPrice,
            }).done(function (res) {
               resolve(res.data)
            });
        }
    });
}
$('#btn_frm_updatemerchant').click(function() {
	$.ajax({
		url: '{{ url("/orders/update/merchant") }}',
		type: "POST",
		data: $("#frm_updatemerchant").serialize() + '&master_merchant_id='+merchant.master_merchant_id
	}).done(function(res) {
		// $('#loading_page').modal('hide');
		if (res.success == true) {
			$.get(url_price, { id: merchant.master_merchant_id }, function(data) {
				merchant_price = data;
			});

		} else {
			alertError(res.message);
		}
	});
	$("#modal_update_cus").modal("hide");
});

$("#act_next").click(function() {
  var check_frm = true;
  var patt = /^[0-9\s]*$/;
  $(".frm_profile")
    .find(".input-error")
    .removeClass("input-error");
  if ($("#add_name_merchant").val().length < 4) {
    $("#add_name_merchant").addClass("input-error");
    check_frm = false;
  }
  if (
    patt.test($("#add_tax_number").val()) == false &&
    $("#add_tax_number").val().length > 0
  ) {
    $("#add_tax_number").addClass("input-error");
    check_frm = false;
  } else if (
    $("#add_tax_number").val().length > 0 &&
    $("#add_tax_number").val().length != 13
  ) {
    $("#add_tax_number").addClass("input-error");
    check_frm = false;
  }
  if (
    $("#add_phone_number").val().length > 0 &&
    $("#add_phone_number").val().length < 9
  ) {
    $("#add_phone_number").addClass("input-error");
    check_frm = false;
  }

  if (check_frm == false) {
    return;
  }
  $(".frm_profile").hide();
  $(".frm_price").show();
});

$("#act_create").click(function() {
  // $('#loading_page').modal({
  // 	backdrop: 'static'
  // 	},'show');
  $.ajax({
    url: '{{ url("/orders/create/merchant") }}',
    type: "POST",
    data: $("#frm_createmerchant").serialize()
  }).done(function(res) {
    // $('#loading_page').modal('hide');
    if (res.success == true) {
      merchant = res.data;
      localStorage.setItem("merchant", JSON.stringify(merchant));
      // $("#name_merchant").html(merchant.name_merchant);
      // $("#phone_number").html(merchant.phone_number);
      // $("#name_department").html(merchant.name_department);
      // $("#tax_number").html(merchant.tax_number);
      // $("#address").html(merchant.address);

      $("#name_merchant").val(merchant.name_merchant);
      $("#phone_number").val(merchant.phone_number);
      $("#name_department").val(merchant.name_department);
      $("#tax_number").val(merchant.tax_number);
      $("#address").val(merchant.address);

      $("#update_cus").css("display", "inline-block");
      clearInputCreate();
      $.get(url_price, { id: merchant.master_merchant_id }, function(data) {
        merchant_price = data;
        // console.log(merchant_price);
      });
    } else {
      alertError(res.message);
    }
  });
  $("#modal_create_cus").modal("hide");
});
function clearInputCreate() {
  $("#frm_createmerchant .frm_profile input").val("");
  $('#frm_createmerchant .frm_price input[type="number"]').val("");

  // 	});
}
//end view create

//view modal
$("#type_00").click(function() {
  $("#order_type_00").attr("checked", "checked");
  $("#order_type_01").removeAttr("checked");
  $("#type_normal_modal").modal({ backdrop: "static" }, "show");
  order_type = "00";
  localStorage.setItem("order_type", order_type);
});

$('#type_01').click(function() {
	$('#order_type_00').removeAttr("checked");
	$('#order_type_01').attr('checked', 'checked');

	order_vat_no={{$defaultVat->vat}};
	order_status_vat='01';
	order_type='01';
	localStorage.setItem('order_type', order_type);
	localStorage.setItem('order_vat_no', order_vat_no);
	localStorage.setItem('order_status_vat', order_status_vat);
	calPrice();
	$('.order_vat_text').html(order_vat_no);
});

$("#type_00_vat").click(function() {
  $("#show_vat").show();
  $("#order_type_00_vat").attr("checked", "checked");
  $("#order_type_00_none").removeAttr("checked");
});
$("#type_00_none").click(function() {
  $("#show_vat").hide();
  $("#order_type_00_vat").removeAttr("checked");
  $("#order_type_00_none").attr("checked", "checked");
});

$("#act_select").click(function() {
  var selectedVat = $("input[name='vat']:checked").val();
  var selectedStatusVat = $("input[name='status_vat']:checked").val();
  if (selectedStatusVat == "01") {
    selectedVat = "0";
  }
  order_vat_no = selectedVat;
  order_status_vat = selectedStatusVat;
  localStorage.setItem("order_vat_no", order_vat_no);
  localStorage.setItem("order_status_vat", order_status_vat);
  $(".order_vat_text").html(selectedVat);
  calPrice();
  $("#type_normal_modal").modal("hide");
  // console.log(selectedVat);
  // console.log(order_status_vat);
});


//end view modal
// add other
var url_searchother = '{{ url("api/orders/searchOther") }}';
var url_searchtypeother = '{{ url("api/orders/searchTypeOther") }}';
var url_searchsizeother = '{{ url("api/orders/searchSizeOther") }}';
var cur_size=null;
var curr_formula='00';
var curr_status_detail='00';
$(".other_item").click(function() {
  $("#modal_create_other").modal({ backdrop: "static" }, "show");
});
$("#search_other").typeahead({
  source: function(term, process) {
    return $.get(url_searchother, { term: term }, function(data) {
      clearOther1();
      return process(data);
    });
  },

  selectOnBlur: true,
  showHintOnFocus: "all",
  displayText: function(item) {
    return item.name;
  },

  afterSelect: function(item) {
    $("#search_typeother").attr("disabled", false);
    $("#o_main_id").val(item.master_product_main_id);
    $("#o_count_unit").val(item.unit_count);
    $("#o_count_unit").attr("disabled", true);
    $(
      'input:radio[name="o_calculation_formula"][value="' + item.formula + '"]'
    ).attr("checked", true);
    $('input:radio[name="o_calculation_formula"]').attr("disabled", true);
    curr_formula = item.formula;
    curr_status_detail = item.status_detail;
    if (item.status_detail == "01") {
      $("#search_typeother").attr("disabled", true);
      getSize(item.master_product_main_id, null);
    }
  }
});

$("#search_typeother").typeahead({
  source: function(term, process) {
    return $.get(
      url_searchtypeother,
      { term: term, main_id: $("#o_main_id").val() },
      function(data) {
        clearOther2();
        return process(data);
      }
    );
  },

  selectOnBlur: true,
  showHintOnFocus: "all",
  displayText: function(item) {
    return item.name;
  },
  afterSelect: function(item) {
    $("#o_type_id").val(item.master_product_type_id);
    getSize(item.master_product_main_id, item.master_product_type_id);
  }
});

function getSize(main_id, type_id) {
  var $mySelect = $("#o_size");
  $.get(url_searchsizeother, { type_id: type_id, main_id: main_id }, function(
    data
  ) {
    cur_size = data;

    $mySelect.children("option").remove();
    var $option = $("<option/>", {
      value: 0,
      text: "ไม่เลือก"
    });
    $mySelect.append($option);
    $.each(data, function(key, value) {
      if (curr_status_detail == "01") {
        $("#o_type_id").val(value.master_product_type_id);
      }
      var name =
        numeral(value.name).format("0,0.00") +
        " " +
        master_product_json["size_unit"][value.size_unit];
      if (curr_formula == "01") {
        name = "0.35x" + numeral(value.name).format("0,0.0000") + " ตร.ม.";
      }
      var $option = $("<option/>", {
        value: value.master_product_size_id,
        text: name
      });
      $mySelect.append($option);
    });
  });
}
$("#o_size").change(function() {
  var value = $(this).val();
  if (value > 0) {
    $("#o_size_name").val("");
    $("#o_size_unit").val("00");
    $("#o_weight").val("");
  }
});
$("#o_size_name").change(function() {
  var value = $(this).val();
  if (value > 0) {
    $("#o_size").val(0);
  }
});
$("#act_createother").click(function() {
  var type_id = $("#o_type_id").val();
  var check_frm = true;

  $("#frm_createother")
    .find(".input-error")
    .removeClass("input-error");
  if ($("#search_other").val().length == 0) {
    $("#search_other").addClass("input-error");
    check_frm = false;
  }
  if (
    $("#search_other").val().length > 0 &&
    ($("#o_size").val() == 0 || $("#o_size").val() == "") &&
    $("#o_size_name").val() <= 0
  ) {
    $("#o_size_name").addClass("input-error");
    check_frm = false;
  }
  if (check_frm == false) {
    return;
  }
  // $('#loading_page').modal({
  // 	backdrop: 'static'
  // 	},'show');
  $.ajax({
    url: '{{ url("orders/create/other") }}',
    type: "POST",
    data: {
      _token: "{{ csrf_token() }}",
      main_id: $("#o_main_id").val(),
      main_name: $("#search_other").val(),
      main_count_unit: $("#o_count_unit").val(),
      type_id: type_id,
      formula: $('input:radio[name="o_calculation_formula"]:checked').val(),
      type_name: $("#search_typeother").val(),
      size_id: $("#o_size").val(),
      size_name: $("#o_size_name").val(),
      size_unit: $("#o_size_unit").val(),
      size_weight: $("#o_weight").val()
    }
  }).done(function(res) {
    // $('#loading_page').modal('hide');
    if (res.success == true) {
      var data = res.data;
      var status_detail = "01";

      $("#select_main").html($("#search_other").val());
      $("#select_main").show();

      if ($("#search_typeother").val() != "") {
        $("#select_type").html($("#search_typeother").val());
        $("#select_type").show();
        status_detail = "00";
        selects["type"] = {
          master_product_type_id: data.master_product_type_id,
          master_product_main_id: data.master_product_main_id,
          type_name: $("#search_typeother").val()
        };
      }
      $("#select_size").html(data.size_name);
      $("#select_size").show();

      selects["main"] = {
        master_product_main_id: data.master_product_main_id,
        status_detail: status_detail,
        product_name: $("#search_other").val()
      };
      selects["size"] = data;
      if (
        $.isEmptyObject(
          merchant_price[selects["size"]["master_product_size_id"]]
        ) == false
      ) {
        selects["price"] =
          merchant_price[selects["size"]["master_product_size_id"]]["price"];
        if (selects["price"] > 0) {
          $("#select_price").val(selects["price"]);
        } else {
          $("#select_price").val("");
        }
      }
      $("#modal_create_other").modal("hide");
    } else {
      var error = "";
      $.each(res.errors, function(index, value) {
        error += value + "<br>";
      });
      alertError(error);
    }
  });
});

//clearinput after close
$('#modal_create_other').on('hidden.bs.modal', function (e) {
			clearOther();
})

function clearOther(){
	$('#o_main_id').val('');
	$('#search_other').val('');
	$('#o_count_unit').val('00');
	$('input:radio[name="o_calculation_formula"]:checked').val();
	$('input:radio[name="o_calculation_formula"]').attr("disabled", false);
	$('#o_type_id').val('');
	$('#search_typeother').val('');
	$('#o_size').val('');
	$('#o_size_name').val('');
	$('#o_size_unit').val('00');
	$('#o_weight').val('');
	$('#search_typeother').attr("disabled", false);
	$('#o_count_unit').attr("disabled", false);
	$('#o_size').children('option').remove();
	$('#frm_createother').find('.input-error').removeClass('input-error');
	var $option = $("<option/>", {
    value: 0,
    text:  'ไม่เลือก'
  });
  $('#o_size').append($option);
	cur_size=null;
	curr_formula='00';
	curr_status_detail='00';
}
function clearOther1(){

	$('#o_count_unit').val('00');
	$('input:radio[name="o_calculation_formula"]:checked').val();
	$('input:radio[name="o_calculation_formula"]').attr("disabled", false);
	$('#o_type_id').val('');
	$('#search_typeother').val('');
	$('#o_size').val('');
	$('#o_size_name').val('');
	$('#o_size_unit').val('00');
	$('#o_weight').val('');
	$('#search_typeother').attr("disabled", false);
	$('#o_size').children('option').remove();
	$('#o_count_unit').attr("disabled", false);
	$('#frm_createother').find('.input-error').removeClass('input-error');
	var $option = $("<option/>", {
    value: 0,
    text:  'ไม่เลือก'
  });
  $('#o_size').append($option);
	cur_size=null;

}
function clearOther2(){

	$('#o_type_id').val('');
	$('#o_size').val('');
	$('#o_size_name').val('');
	$('#o_size_unit').val('00');
	$('#o_weight').val('');
	$('#o_size').children('option').remove();
	$('#frm_createother').find('.input-error').removeClass('input-error');
	var $option = $("<option/>", {
    value: 0,
    text:  'ไม่เลือก'
  });
  $('#o_size').append($option);
	cur_size=null;

}
// end add other
</script>
@endsection