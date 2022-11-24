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
						<li class="breadcrumb-item"><a
								onclick="window.history.go(-1); return false;">จัดการใบสั่งซื้อ</a>
						</li>
						<li class="breadcrumb-item active" aria-current="page">แก้ไขใบสั่งซื้อ {{$data['Order']->order_number}}</li>
					</ol>
				</nav>
			</div>
		</div>
        <hr>
        <!-- merchant -->
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
            </div>
        <!-- end merchant -->
        <div class="form " id="form_orders">
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
				<button type="button" class="btn btn-type  btn-lg mr-4 mt-3 items-type"
					data-type-name="{{$key}}"  data-type-id="{{$value[0]->master_product_type_id}}">{{$key}}</button>
				@endforeach
			</div>
			<!-- end type -->
			<!-- size -->
			<div class="col-md-12 pl-0 pr-0">
				@foreach($items['size'] as $key => $value)
				<button type="button" class="btn btn-size  btn-lg mr-4 mt-3 items-size"
					data-size-name="{{$key}}" data-typesize-id="{{$value[0]->master_product_type_id}}">{{$key}}</button>
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
									<input type="number" class="form-control color-blue-1" id="select_qty" value="1"
										min="1">
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
							<input type="number" class="form-control  number" id="select_addition_price_per_unit"
								value="" min="0" placeholder="ราคาเพิ่มเติมต่อหน่วย	">
						</div>
						<div class="col-md-4 pr-0">
							<input type="text" class="form-control " id="select_remark" placeholder="หมายเหตุสินค้า..."
								max="191">
						</div>
						<div class="col-md-4 float-right">
							<div class="float-right mr-4">
								<a href="javascript:void(0)" class="mr-2 text-decoration"
									id="clear_selects">ล้างข้อมูล</a>
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
                            @foreach ($data['OrderItem'] as $key => $item)
                            <tr  class="item_{{$key}}">
                                <th scope="row">{{$key+1}}</th>
                                <td class="color-blue-1">{{$item->product_name}}</th>
                                <td class="color-blue-1">{{$item->product_type_name}}</td>
                                <td class="color-blue-1">{{$item->product_size_name_text}}</td>
                                <td  class="text-right total_size">{{$item->formula == '00' ? '' :$item->total_size_text}}</td>
                                <td class="text-right">
                                    <div class="col-10 float-right p-0">
                                        <input type="number" class="form-control form-control-sm text-right number " name="price_per_unit" value="{{ $item->price }}" min="0.01" onblur="updateItem({{$key}})">
                                    </div>
                                </td>
                                <td class="text-right">
                                    <div class="col-10 float-right p-0">
                                        <input type="number" class="form-control form-control-sm text-right number " name="addition_price_per_unit" value="{{ $item->addition }}" min="0" onblur="updateItem({{$key}})">
                                    </div>
                                </td>
                                <td class="text-right">
                                    <div class="col-10 float-right p-0">
                                        <input type="number" class="form-control form-control-sm text-right number " name="qty" value="{{ $item->qty }}" min="1" onblur="updateItem({{$key}})">
                                    </div>
                                </td>
                                <td  class="text-right total_weight">
                                    {{ $item->product_weight_text }}
                                </td>
                                <td  class="text-right total_price">
                                    {{ number_format($item->total_price,2) }}
                                </td>
                                <td >
                                    <div class="form-group row p-0">
                                        <div class="col-md-9">
                                            <input type="text" class="form-control form-control-sm p-0 s_remark_1 " name="remark" max="191" value="{{ $item->remark }}" onblur="updateItem({{$key}})">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-outline-light p-0" onclick="removeItem({{$key}})">
                                                <i class="fas fa-times-circle text-danger fa-lg"></i>
                                            </button>
                                        </div>
                                    </div>

                                </td>

                            </tr>
                            @endforeach


						</tbody>
						<tfoot>
							<tr class="bg-light-1">
								<th colspan="11" class="text-center">รายการสินค้าที่สั่งซื้อทั้งหมด <span
										id="total_item" class="color-blue-1">0</span> รายการ
									น้ำหนักสินค้าที่สั่งซื้อทั้งหมด <span id="total_weight"
										class="color-blue-1">0</span> <span id="weight_type">กรัม</span></th>


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
                                        <input type="text" autocomplete="off" class="form-control date" required id="datepicker" name="datemarksend" value="{{date('d-m-Y',strtotime($data['Order']->datemarksend))}}">
                                    </div>
                            </div>
                            <div class="row form-row">
                                    <label class="control-label col-md-3">หมายเหตุบิล :</label>
                                    <textarea type="text" class="form-control col-md-9" id="noteorder" name="noteorder">{{$data['Order']->noteorder}}</textarea>
                            </div>
                        </div>
					</div>
					<div class="col-md-5">
						<div class="row p-1">
							<span class="font-weight-bold col-md-5">รวมเป็นเงิน :</span>
							<span class="font-weight-bold text-right col-md-7 total_price_text">{{number_format($carts_price['total_price'],2)}}</span>
						</div>
						@hasanyrole('super-admin|manager')
						<div class="row p-1">
							<div class="btn-group btn-group-toggle col-md-12" data-toggle="buttons">
								<label class="btn btn-primary-1 active" id="type_00">
									<input type="radio" name="order_type" id="order_type_00" value="00"
										checked="checked"> ใบสั่งซื้อปกติ
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
									<input type="radio" name="order_type" id="order_type_00" value="00"
										checked="checked"> เลือกภาษีมูลค่าเพิ่ม
								</label>
							</div>
						</div>
						@endhasanyrole
						<div class="row p-1">
							<span class="font-weight-bold col-md-5">รวมเป็นเงิน :</span>
							<span class="font-weight-bold text-right col-md-7 total_price_text">{{number_format($carts_price['total_price'],2)}}</span>
						</div>
						<div class="row p-1">
							<span class="font-weight-bold col-md-5">ภาษีมูลค่าเพิ่ม <span
									class="order_vat_text">{{$defaultVat->vat}}</span>% :</span>

							<span class="font-weight-bold text-right col-md-7 total_vat_text">{{number_format($carts_price['vat'],2)}}</span>
						</div>
						<div class="row p-1">
							<span class="font-weight-bold col-md-5">จำนวนเงินรวมทั้งสิ้น :</span>
							<span class="font-weight-bold text-right col-md-7 grand_total_text">{{number_format($carts_price['grand_total'],2)}}</span>
						</div>

					</div>
				</div>
            </div>
            <hr>
			<!-- end summary -->
            <!-- submit -->
            <input type="hidden" name="order_id" id="order_id" value="{{$data['Order']->order_id}}">
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
@include('pages.loading')


</div>

@endsection

@section('js')

<script type="text/javascript">
    $('#datepicker').datepicker({
        uiLibrary: 'bootstrap4',
        format: 'dd-mm-yyyy',
        minDate: moment().format('D-M-Y')
    });
    var master_product_json = {!! json_encode($items) !!};
    var selects={main:null,type:null,size:null,price:0,price_add:0,total_price:0,total_size:0,size_unit:'',qty:0,remark:'',weight_total:0};

    var carts_price= {!!json_encode($carts_price)!!};
    var order_type='00';
    var order_vat_no= {!! json_encode($data['Order']->vat_no) !!};
    var order_status_vat= {!! json_encode($data['Order']->status_vat) !!};
    var merchant=null;
    var merchant_price=null;
    var merchant_edit = {!! json_encode($data['merchant']) !!};
    var url_price = '{{ url("api/orders/getPrice") }}';
    var carts_old = {!! json_encode($carts) !!};
    var carts=carts_old;
    // carts.push(carts_old);
    console.log(order_vat_no);


    clearSelects();
    // console.log(merchant_edit);
    setMerchant(merchant_edit);

    $('#select_main').hide();
    $('#select_type').hide();
    $('#select_size').hide();

    $('.items-type').prop('disabled', true);
    $('.items-size').prop('disabled', true);

    // clear ค่าสินค้าที่เลืก
    function clearSelects()
    {
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

     // clear เลือกประเภทสินค้า
    function clearBtnType()
    {
        $.each($(".items-type"), function( index, value ) {
            $(this).attr("disabled", false);
            $(this).removeClass( "active" );
            $(this).removeClass( "btn-disable-type" );
        });

    }
    // clear เลือกขนาดสินค้า
    function clearBtnSize()
    {
        $.each($(".items-size"), function( index, value ) {
            $(this).attr("disabled", false);
            $(this).removeClass( "active" );
            $(this).removeClass( "btn-disable-size" );
        });
    }

    function clearBtnMain(){

        $.each($(".items-main"), function( index, value ) {
            $(this).attr("disabled", false);
            $(this).removeClass( "active" );
            $(this).removeClass( "btn-disable-main" );
        });

    }

    //เลือก ค่าสินค้าที่หลัก disabled เลือกสิ้นค้าที่ไม่ได้เลือก
    function setBtnActive(curr_id,class_name,target,new_class)
    {

        $.each($(class_name), function( index, value ) {

            var id=$(this).data(target+'-id');
            var name=$(this).data(target+'-name');
            if(curr_id != id){
                    $(this).removeClass( "active" ).addClass(new_class+'-'+target );

            }else{
                $(this).removeClass(new_class+'-'+target).addClass( "active" );
            }
        })
    }
    // disabled type ที่ไม่ได้อยู่ในรายการสินค้าหลัก
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
     // disabled size ที่ไม่ได้อยู่ในรายการประเภทสินค้า
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

     //เลือก ค่าประเภทสินค้า และ เลือกขนาด  disabled เลือกสินค้าที่ไม่ได้เลือก
    function setBtnActive2(curr_id,curr_name,class_name,target,new_class,main_product_id)
    {
        $.each($(class_name), function( index, value )
        {
            var name=$(this).data(target+'-name');
            var idtarget=$(this).data(target+'-id');

            if(curr_name != name)
            {
                $(this).removeClass( "active" ).addClass(new_class+'-'+target );
            }else
            {

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
                    }

                    selects[target]=getValue(target,master_product_json[target][name]);
                }
            }
        });

    }
    // disabled ขนาด ที่ไม่ได้อยู่ใน ประเภทสินค้า ที่เลือก
    function setBtnsizeShow(curr_id,curr_name,main_product_id)
    {
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

    function clearActiveBtnSize()
    {
        $.each($(".items-size"), function( index, value ) {
            $(this).removeClass( "active" );
            $(this).removeClass( "btn-disable-size" );
        });

    }

    function getValue(type,data)
    {
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

    function setMerchant(item){
        merchant=item;
        localStorage.setItem('merchant', JSON.stringify(item))
        $.get(url_price, { id: item.master_merchant_id }, function (data) {
            merchant_price= data;
        });
    }

    // เลือกสินค้าหลัก
    $('.items-main').click(function()
    {
        var id=$(this).data('main-id');
        var name=$(this).data('main-name');
        // console.log(id);
        // console.log(name);
        clearBtnType();
        clearBtnSize();
        clearSelects();
        if(id ==0)
        {
            $(this).addClass( "active" );
            $(this).removeClass( "btn-disable" );
            setBtnActive(id,'.items-main','main','btn-disable');
            SetDisableBtnTypeNoneSelect(id,name);
            SetDisableBtnSizeNoneSelect(id,name);
                return;
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

    // เลือกประเภทสินค้า
    $('.items-type').click(function()
    {
        var id=$(this).data('type-id');
        var name=$(this).data('type-name');
        clearActiveBtnSize();
        $(this).addClass( "active" );
        $(this).removeClass( "btn-disable" );
        setBtnActive2(id,name,'.items-type','type','btn-disable',selects['main'].master_product_main_id);
        console.log(selects['main']);
        if(selects['main'] !=null){
            $('#select_type').html(name);
            $('#select_type').show();
            selects['size']=null;
            $('#select_size').hide();
        }
        $('#select_remark').val(name);
    });
    // เลือกขนาดสินค้า
    $('.items-size').click(function()
    {
        var id=$(this).data('size-id');
        var name=$(this).data('size-name');
        // console.log(id);
        // console.log(name);
        $(this).addClass( "active" );
        $(this).removeClass( "btn-disable" );
        setBtnActive2(id,name,'.items-size','size','btn-disable',selects['main'].master_product_main_id);
        if(selects['main'] !=null)
        {
            if(selects['main']['status_detail'] =='00' && selects['type'] !=null || selects['main']['status_detail'] =='01' )
            {
                $('#select_size').html(name);
                $('#select_size').show();
                try
                {
                    if($.isEmptyObject(merchant_price[selects['size']['master_product_size_id']]) == false)
                    {
                        selects['price']=merchant_price[selects['size']['master_product_size_id']]['price'];
                            if(selects['price']>0){
                                $('#select_price').val(selects['price']);
                            }else
                            {
                                $('#select_price').val('');
                            }
                    }
                }catch (error)
                {
                    alertError('items-size action click | status_detail invalid | Size : '+name+' | '+error);
                }
            }
        }

    });

    $("#add_cart").click(function()
    {
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
        // localStorage.setItem("cartsedit", JSON.stringify(carts));
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

    function updateItem(i)
    {
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
        // localStorage.setItem("cartsedit", JSON.stringify(carts));
    }


    function calPrice()
    {
        var total_price = 0;
        var total_weight = 0;
        var qty_all = 0;
        $.each(carts, function(index, value) {
            total_price += parseFloat(value["total_price"]);
            total_weight += parseFloat(value["weight_total"]);
            qty_all += parseInt(value["qty"]);
        });
        // console.log(total_price.toFixed(2));
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

    function removeItem(i) {
        carts.splice(i, 1);
        calPrice();
        // localStorage.setItem("cartsedit", JSON.stringify(carts));
        $("#order_items tr").remove();
        $.each(carts, function(index, value) {
            addItemDisplay(index);
        });
    }

    function addItemDisplay(i)
    {
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
                ' <th scope="row" class="number_no">' +
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

    function convertWeightUnit(weight_g)
    {
        if (weight_g < 1000)
            return { number: numeral(weight_g).format("0,0.00"), unit: "กรัม" };
        else
            return {
            number: numeral(weight_g / 1000).format("0,0.00"),
            unit: "กิโลกรัม"
            };
    }
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
    $("#type_00").click(function() {
        $("#order_type_00").attr("checked", "checked");
        $("#order_type_01").removeAttr("checked");
        $("#type_normal_modal").modal({ backdrop: "static" }, "show");
        order_type = "00";
        // localStorage.setItem("order_type", order_type);
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

    $("#create_cart").click(function()
    {
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
                url: '{{ url("orders/update") }}',
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
                    order_id: $('#order_id').val(),
                }
                }).done(function(res) {
                $("#loading_page").modal("hide");
                console.log(res);
                if (res.success == true) {
                    // Swal.fire({ type: 'success', title: 'สำเร็จ', text: 'บันทึกข้อมูลสำเร็จ' }).then(
                    // () => {
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
    function alertError(text) {
        Swal.fire({
            type: "warning",
            title: "แจ้งเตือน",
            icon: "warning",
            dangerMode: true,
            html: text
        });
    }




</script>
@endsection
