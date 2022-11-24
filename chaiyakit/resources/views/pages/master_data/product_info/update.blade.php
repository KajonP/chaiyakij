@extends('layouts.app')

@section('content')
  @php
    // dd($data);
  @endphp
<div class="form-main" id="product_info">
  <input type="hidden" name="id_product" value="{{$data}}">
    <div class="container-fluid">
      <div class="row">
          <div class="col-12 col-md-6">
              <h2>แก้ไขข้อมูลสินค้า</h2>
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
                              onclick="window.history.go(-1); return false;">จัดการข้อมูลสินค้า</a>
                      </li>
                      <li class="breadcrumb-item active" aria-current="page">แก้ไขข้อมูลสินค้า</li>
                  </ol>
              </nav>
          </div>
      </div>
        <hr>

        <div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="inputEmail4">ชื่อสินค้า : </label> <span class="color-red">*</span>
                    <input type="email" class="form-control" placeholder="ชื่อสินค้า" name="product_name" readonly>
                </div>
                <div class="form-group col-md-3">
                  <label for="inputEmail4"></label>
                  <div class="custom-control custom-radio btn-redio text-center">
                    <input type="radio" class="custom-control-input formula" id="pole_calculation_formula1" name="text_calculation_formula" value="00">
                    <label class="custom-control-label" for="pole_calculation_formula1">สูตรคํานวณเสา</label>
                  </div>
                </div>
                <div class="form-group col-md-3">
                  <label for="inputEmail4"></label>
                  <div class="custom-control custom-radio btn-redio">
                    <input type="radio" class="custom-control-input formula" id="slab_calculation_formula2" name="text_calculation_formula" value="01">
                    <label class="custom-control-label" for="slab_calculation_formula2">สูตรคํานวณแผ่นพื้น</label>
                  </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="inputCity">ประเภทสินค้า : </label>
                    <input type="text" class="form-control product_type" placeholder="ประเภทสินค้า" name="product_type">
                </div>

                <div class="form-group col-md-3 top-btn-add">
                    {{-- <button type="button" class="btn btn-secondary" id="add_form_product" onclick="product_update.add_form_product();"><i class="fa fa-plus"> </i> เพิ่มประเภทสินค้า</button> --}}
                </div>
            </div>
            <div class="col-md-9">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="inputCity">ขนาดสินค้า : </label> <span class="color-red">*</span>
                        <input type="text" class="form-control" name="text_product_size" maxlength="8" OnKeyPress="return chkNumber(this)" placeholder="กรอกขนาดสินค้า">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="inputCity">หน่วยขนาด : </label> <span class="color-red"> *</span>
                        <select class="form-control" name="text_unit" id="text_unit">
                          <option value="">---- หน่วย -----</option>
                          <option value="00">ตร.ม.</option>
                          <option value="01">เมตร</option>
                          <option value="02">ศอก</option>
                          <option value="03">วา</option>
                          <option value="04">กรัม</option>
                          <option value="05">กิโลกรัม</option>
                          <option value="06">ตัน</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="inputState">น้ำหนัก (กรัม) : </label> <span class="color-red">*</span>
                        <input type="text" class="form-control number" name="text_weight" maxlength="8" OnKeyPress="return chkNumber(this)" placeholder="กรอกน้ำหนัก (กรัม)" />
                    </div>
                    <div class="form-group col-md-2 top-btn-del">
                        <button type="button" class="btn btn-secondary" id="add_type_product" onclick="product_update.add_form_input_product();"><i class="fa fa-plus"> </i></button>
                    </div>
                </div>
                <div class="add_form_input_type_product"></div>
            </div>
            <div class="form-group col-md-5 text-right">
                <button type="button" class="btn btn-secondary col-5" id="add_form_product" onclick="product_update.add_form_product();">เพิ่มรายการ</button>
            </div>

            <hr>

            <form id="product">
              <meta name="csrf-token" content="{{ csrf_token() }}">
              <div class="add_form_product"></div>

              <div class="col-12 text-center mt-5">
                  <a onclick="window.history.go(-1); return false;" type="button" class="btn btn-danger text-white">ยกเลิก</a>
                  <button type="button" class="btn btn-success" onclick="product_update.update_product();">ยืนยันทำรายการ</button>
              </div>
            </form>


        </div>
    </div>
</div>
</div>

@endsection

@section('js')

    <script type="text/javascript">
        var weight = new Cleave('.number', {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand'
        });
        var product_update = {
            data: false,
            data_add: false,
            data_check: true,
            row_data: 0,
            get_product_update:() =>{
              Swal.fire({
                  title: 'Loading..',
                  onOpen: () => {
                      swal.showLoading()
                  }
              });
              var id = $("input[name$='id_product']").val();
              $.ajax({
                url: window.location.origin + '/api/master-data/get_product/'+id,
                type: 'get',
                dataType: "json",
                success: function(data) {
                  // console.log(data.length)
                  if (data.length > 0) {
                    var data_edit = [];
                    for (var u = 0; u < data.length; u++) {
                      var data_product = {
                          'id' : u + 1 ,
                          'del' : 0 ,
                          "product_id" : data[u].product_id,
                          "product_name" : data[u].product_name,
                          "product_type_id" : data[u].product_type_id,
                          "product_type_a" : data[u].product_type,
                          "product_type_b" : data[u][0],
                          "product_formula" : data[u].product_formula,
                          "order_item" : data[u].order_item,
                      }
                      data_edit.push(data_product);
                    }

                    if (data[0].order_item == false) {
                      $( "#delete" ).prop( "disabled", true );
                    }
                    $("input[name$='product_name']").val(data[0].product_name);
                    product_update.data = { "data_product" : data_edit};
                    product_update.row_data = product_update.data.data_product.length;
                    product_update.add_form_product();
                  }
                  swal.close();

                }
              });
            },

            add_form_input_product:() => {
                if($("input[name$='text_product_size']").val() != ''){
                    var input_product = $(".add_form_input_type_product");
                    var x = 1;
                    x++;

                    var optionselect = '';
                    if ($("select[name=text_unit]").val() == "") {
                       optionselect += `<option value="" selected>---- หน่วย -----</option>`;
                    }else {
                      optionselect += `<option value="">---- หน่วย -----</option>`;
                    }
                    if ($("select[name=text_unit]").val() == "00") {
                      optionselect += `<option value="00" selected>ตร.ม.</option>`;
                    }else {
                      optionselect += `<option value="00">ตร.ม.</option>`;
                    }
                    if ($("select[name=text_unit]").val() == "01") {
                      optionselect += `<option value="01" selected>เมตร</option>`;
                    }else {
                      optionselect += `<option value="01">เมตร</option>`;
                    }
                    if ($("select[name=text_unit]").val() == "02") {
                      optionselect += `<option value="02" selected>ศอก</option>`;
                    }else {
                      optionselect += `<option value="02">ศอก</option>`;
                    }
                    if ($("select[name=text_unit]").val() == "03") {
                      optionselect += `<option value="03" selected>วา</option>`;
                    }else {
                      optionselect += `<option value="03">วา</option>`;
                    }
                    if ($("select[name=text_unit]").val() == "04") {
                      optionselect += `<option value="04" selected>กรัม</option>`;
                    }else {
                      optionselect += `<option value="04">กรัม</option>`;
                    }
                    if ($("select[name=text_unit]").val() == "05") {
                      optionselect += `<option value="05" selected>กิโลกรัม</option>`;
                    }else {
                      optionselect += `<option value="05">กิโลกรัม</option>`;
                    }
                    if ($("select[name=text_unit]").val() == "06") {
                      optionselect += `<option value="06" selected>ตัน</option>`;
                    }else {
                      optionselect += `<option value="06">ตัน</option>`;
                    }

                    $(input_product).append( `<div class="form-row remove_product">
                                                <div class="form-group col-md-3">
                                                    <input type="text" class="form-control" name="text_product_size[]" value="`+$("input[name$='text_product_size']").val()+`" disabled>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <select class="form-control" name="text_unit[]" id="text_unit">
                                                      `+ optionselect +`
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <input type="text" class="form-control" name="text_weight[]" value="`+ ($("input[name$='text_weight']").val()*1).toLocaleString(undefined, {minimumFractionDigits: 2}) +`" disabled>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <button type="button" class="btn btn-danger remove" ><i class="fa fa-trash"> </i></button>
                                                </div>
                                            </div>`);

                    $(input_product).on("click",".remove", function(e){
                        $(this).parent('div').parent('div').remove(); x--;
                    })

                    var type_product = [];
                    var product_size=[];
                    $('[name="text_product_size[]"]').each(function() {
                        product_size.push($(this).val());
                    });

                    var weight=[];
                    $('[name="text_weight[]"]').each(function() {
                        weight.push($(this).val());
                    });

                    var unit=[];
                    $('[name="text_unit[]"]').each(function() {
                        unit.push($(this).val());
                    });
                    // console.log(unit);
                    for (var i = 0; i < product_size.length; i++) {
                        type_product[i] = [null,product_size[i],weight[i],0,0,unit[i]];
                    }
                    // console.log(type_product);

                    var product = []
                    var data_product = {
                        'id' : product_update.data.data_product.length == 0 ? 1 : product_update.data.data_product[product_update.data.data_product.length-1].id +1 ,
                        'del' : 0 ,
                        "product_id" : product_update.data.data_product[0].product_id,
                        "product_formula" : product_update.data.data_product[0].product_formula,
                        "product_type_id" : null,
                        "product_name" : $("input[name$='product_name']").val(),
                        "product_type_a" : $("input[name$='product_type']").val(),
                        "product_type_b" : type_product,
                    }
                    product.push(data_product);
                    product_update.data_add = {
                        "data_product" : product,
                    };

                    $("input[name$='text_product_size']").val("");
                    $("input[name$='text_weight']").val("");
                    $("select[name=text_unit]").val("");
                    product_update.data_check = true;
                }else{
                    Swal.fire({
                        icon: 'warning',
                        title: "กรุณากรอกขนาดสินค้า",
                        confirmButtonText : "OK",
                    });
                }
            },

            add_form_product:(d) => {
              var ck = true;
              var check_product_type = true;
              if ($("input[name$='product_name']").val() == "") {
                ck = false;
              }

              if (product_update.data.data_product[0].product_type_a == "") {
                $(".product_type ").attr('disabled','disabled');
              }

              if (product_update.data_add != false && product_update.data.data_product[0].product_type_a != "") {
                if ($("input[name$='product_type']").val() == "") {
                  ck = false;
                  $(".product_type ").attr('disabled',false);
                }
              }

              if (product_update.data_check == false) {
                ck = false;
              }


              if (ck == false) {
                Swal.fire({
                    icon: 'warning',
                    title: "กรุณากรอกข้อมูลให้ครบ",
                    confirmButtonText : "OK",
                });
              }else {
                if (product_update.data_add != false) {
                  product_update.data.data_product.push(product_update.data_add.data_product[0]);
                }



                  var data = product_update.data;
                  var stringb = '';
                  var del_form_product = $('.add_form_product');
                  var x = 1;
                  x++;
                  // console.log(data.data_product);
                  for (var i = 0; i < data.data_product.length; i++) {
                    // console.log(data.data_product[i].del);
                      if (data.data_product[i].del == 0) {

                        var disabled1 =" ";
                        if (data.data_product[i].order_item == true ) {
                           disabled1 = "disabled";
                        }

                        if (disabled1 == "disabled") {
                          $('#delete').addClass('delete');
                        }

                        if (data.data_product[i].product_type_a == "") {
                          data.data_product[i].product_type_a = $("input[name$='product_type']").val();
                        }

                        if (data.data_product[i].product_formula == 00) {
                          $('input[name=text_calculation_formula][value=00]').attr('checked', true);
                        }else {
                          $('input[name=text_calculation_formula][value=01]').attr('checked', true);
                        }

                        if (disabled1 == "disabled") {
                          $(".formula").attr('disabled','disabled');
                        }

                        stringb += `<div>
                        <div class="form-row">
                          <div class="form-group col-md-4">
                              <label for="inputCity">ประเภทสินค้า : </label>
                              <input type="text" class="form-control" placeholder="ประเภทสินค้า" name="product_type[]" value="`+data.data_product[i].product_type_a+`" readonly>
                              <input type="hidden" name="product_name[]" value="`+data.data_product[i].product_name+`">
                          </div>

                          <div class="form-group col-md-2 top-btn-del">
                              <button type="button" class="btn btn-danger del_form_product__`+data.data_product[i].id+`"  onclick="product_update.remove_div(`+data.data_product[i].id+`);" `+disabled1+`><i class="fa fa-trash"> </i> ลบประเภทสินค้า</button>
                          </div>
                        </div>
                        <div class="col-md-9">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="inputCity">ขนาดสินค้า : </label> <span class="color-red">*</span>
                                <input type="text" class="form-control" name="text_product_size_b`+data.data_product[i].id+`" maxlength="8" OnKeyPress="return chkNumber(this)" placeholder="กรอกขนาดสินค้า">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="inputCity">หน่วย : </label> <span class="color-red"> *</span>
                                <select class="form-control" name="text_unit_b`+data.data_product[i].id+`" id="text_unit_b`+data.data_product[i].id+`">
                                  <option value="">---- หน่วย -----</option>
                                  <option value="00">ตร.ม.</option>
                                  <option value="01">เมตร</option>
                                  <option value="02">ศอก</option>
                                  <option value="03">วา</option>
                                  <option value="04">กรัม</option>
                                  <option value="05">กิโลกรัม</option>
                                  <option value="06">ตัน</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="inputState">น้ำหนัก (กรัม) : </label> <span class="color-red">*</span>
                                <input type="text" class="form-control number" name="text_weight_b`+data.data_product[i].id+`" value="" maxlength="8"  onkeyup="return isNumber(event,$(this).val(),`+data.data_product[i].id+`)"  placeholder="กรอกน้ำหนัก (กรัม)">
                            </div>
                            <div class="form-group col-md-2 top-btn-del">
                                <button type="button" class="btn btn-secondary" onclick="product_update.add_input_product(`+data.data_product[i].id+`);" ><i class="fa fa-plus"> </i></button>
                            </div>
                        </div>`;
                        var str = '';
                        var y = '';
                        for (var x = 0; x < data.data_product[i].product_type_b.length; x++) {
                          if (data.data_product[i].product_type_b[x][4] == 0) {
                            var disabled =" ";
                            if (data.data_product[i].product_type_b[x][3] == 1 ) {
                              disabled ="disabled";
                            }
                             y = data.data_product[i].id;
                              str += `
                                <div class="form-row remove_row_edit`+data.data_product[i].id+`">
                                  <div class="form-group col-md-3">
                                      <input type="text" class="form-control" name="product_size[]" value="`+(data.data_product[i].product_type_b[x][1]*1).toLocaleString(undefined, {minimumFractionDigits: 2})+`" maxlength="8" OnKeyPress="return chkNumber(this)" placeholder="กรอกขนาดสินค้า" readonly>
                                  </div>
                                  <div class="form-group col-md-3">
                                      <select class="form-control" name="text_unit_b[]" onchange="product_update.add_new_option( $(this).val(),`+ i +','+ x +` )" `+disabled+`> `;
                                      if (data.data_product[i].product_type_b[x][5]) {
                                        str += `<option value="" selected>---- หน่วย -----</option>`;
                                      }else {
                                        str += `<option value="">---- หน่วย -----</option>`;
                                      }
                                      if (data.data_product[i].product_type_b[x][5] == "00") {
                                        str += `<option value="00" selected>ตร.ม.</option>`;
                                      }else {
                                        str += `<option value="00">ตร.ม.</option>`;
                                      }
                                      if (data.data_product[i].product_type_b[x][5] == "01") {
                                        str += `<option value="01" selected>เมตร</option>`;
                                      }else {
                                        str += `<option value="01">เมตร</option>`;
                                      }
                                      if (data.data_product[i].product_type_b[x][5] == "02") {
                                        str += `<option value="02" selected>ศอก</option>`;
                                      }else {
                                        str += `<option value="02">ศอก</option>`;
                                      }
                                      if (data.data_product[i].product_type_b[x][5] == "03") {
                                        str += `<option value="03" selected>วา</option>`;
                                      }else {
                                        str += `<option value="03">วา</option>`;
                                      }
                                      if (data.data_product[i].product_type_b[x][5] == "04") {
                                        str += `<option value="04" selected>กรัม</option>`;
                                      }else {
                                        str += `<option value="04">กรัม</option>`;
                                      }
                                      if (data.data_product[i].product_type_b[x][5] == "05") {
                                        str += `<option value="05" selected>กิโลกรัม</option>`;
                                      }else {
                                        str += `<option value="05">กิโลกรัม</option>`;
                                      }
                                      if (data.data_product[i].product_type_b[x][5] == "06") {
                                        str += `<option value="06" selected>ตัน</option>`;
                                      }else {
                                        str += `<option value="06">ตัน</option>`;
                                      }
                                      // console.log(data.data_product[i].product_type_b[x]);
                                  var te_weight = '';
                                  if (data.data_product[i].product_type_b[x][0] == null) {
                                    te_weight = data.data_product[i].product_type_b[x][2];
                                  }else {
                                    te_weight = (data.data_product[i].product_type_b[x][2]*1).toLocaleString(undefined, {minimumFractionDigits: 2});
                                  }

                              str += `</select>
                                  </div>
                                  <div class="form-group col-md-3">
                                      <input type="text" class="form-control number" name="weight[]" onkeyup="product_update.add_new_str( $(this).val(),`+ i +','+ x +`)" value="`+ te_weight +`"  maxlength="8" OnKeyPress="return chkNumber(this)"  placeholder="กรอกน้ำหนัก (กรัม)">
                                  </div>
                                  <div class="form-group col-md-2">
                                      <button type="button" class="btn btn-danger remove__`+data.data_product[i].id+`" onclick="product_update.remove_input_product(`+ y +','+ x +`);" `+disabled+`><i class="fa fa-trash"> </i></button>
                                  </div>
                                </div>`;
                          }
                        }
                        stringb +=  str;
                        stringb += `<div class="add_type_product__`+data.data_product[i].id+`"></div></div><hr></div>`;
                      }
                  }

                  $('.add_form_product').html(stringb);
                  $(".remove_product").remove('div');
                  $("input[name$='product_name']").val(data.data_product[0].product_name);
                  $("input[name$='product_type']").val("");
                  // $("select[name=text_unit_b"+id+"]").val("");
                  product_update.data_check = false;
                }

            },

            add_new_option:(val,row,id) => {
              product_update.data.data_product[row].product_type_b[id][5] = val;
            },

            add_new_str:(val,row,id) => {
              product_update.data.data_product[row].product_type_b[id][2] = val;
            },

            remove_div:(remove_id) =>{
              $('.add_form_product').on("click",".del_form_product__"+remove_id+"", function(e){

                  // x--;
                  var de = product_update.data.data_product.length;
                  for (var y = 0; y < product_update.data.data_product.length; y++) {
                    if (product_update.data.data_product[y].del == 1) {
                      de--;
                    }
                  }
                  // console.log(de);
                  for (var i = product_update.data.data_product.length - 1; i >= 0; --i) {
                      if ( product_update.data.data_product[i].id == remove_id) {
                        if (de > 1) {
                          $(this).parent('div').parent('div').parent('div').remove();
                          product_update.data.data_product[i].del = 1;
                          de--;
                          for (var y = 0; y < product_update.data.data_product[i].product_type_b.length; y++) {
                            product_update.data.data_product[i].product_type_b[y][4] = 1;
                          }
                          // product_update.data.data_product.splice(i,1);
                        }else {
                          Swal.fire({
                              icon: 'warning',
                              title: "ต้องมี ประเภทสินค้า อย่างน้อย 1 รายการ",
                              confirmButtonText : "OK",
                          });
                        }

                           // product_update.data.data_product.splice(i,1);

                      }
                  }

              })
            },

            add_input_product:(id) => {
                if($("input[name$='text_product_size_b"+id+"']").val() != ''){
                    var input_product = $(".add_type_product__"+id+"");
                    var x = 1;
                    x++;
                    var u = product_update.data.data_product.length == 0 ? 1 : product_update.data.data_product[product_update.data.data_product.length-1].id;

                    var optionvalue = '';
                    if ($("select[name=text_unit_b"+id+"]").val()) {
                       optionvalue += `<option value="" selected>---- หน่วย -----</option>`;
                    }else {
                      optionvalue += `<option value="">---- หน่วย -----</option>`;
                    }
                    if ($("select[name=text_unit_b"+id+"]").val() == "00") {
                      optionvalue += `<option value="00" selected>ตร.ม.</option>`;
                    }else {
                      optionvalue += `<option value="00">ตร.ม.</option>`;
                    }
                    if ($("select[name=text_unit_b"+id+"]").val() == "01") {
                      optionvalue += `<option value="01" selected>เมตร</option>`;
                    }else {
                      optionvalue += `<option value="01">เมตร</option>`;
                    }
                    if ($("select[name=text_unit_b"+id+"]").val() == "02") {
                      optionvalue += `<option value="02" selected>ศอก</option>`;
                    }else {
                      optionvalue += `<option value="02">ศอก</option>`;
                    }
                    if ($("select[name=text_unit_b"+id+"]").val() == "03") {
                      optionvalue += `<option value="03" selected>วา</option>`;
                    }else {
                      optionvalue += `<option value="03">วา</option>`;
                    }
                    if ($("select[name=text_unit_b"+id+"]").val() == "04") {
                      optionvalue += `<option value="04" selected>กรัม</option>`;
                    }else {
                      optionvalue += `<option value="04">กรัม</option>`;
                    }
                    if ($("select[name=text_unit_b"+id+"]").val() == "05") {
                      optionvalue += `<option value="05" selected>กิโลกรัม</option>`;
                    }else {
                      optionvalue += `<option value="05">กิโลกรัม</option>`;
                    }
                    if ($("select[name=text_unit_b"+id+"]").val() == "06") {
                      optionvalue += `<option value="06" selected>ตัน</option>`;
                    }else {
                      optionvalue += `<option value="06">ตัน</option>`;
                    }

                    $(input_product).append( `<div class="form-row remove_product">
                                                <div class="form-group col-md-3">
                                                    <input type="text" class="form-control" name="product_size`+id+`[]" value="`+$("input[name$='text_product_size_b"+id+"']").val()+`" readonly>
                                                </div>
                                                <div class="form-group col-md-3">
                                                  <select class="form-control" name="text_unit_b`+id+`[]" id="text_unit_b">
                                                  `+optionvalue+`
                                                  </select>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <input type="text" class="form-control number" name="weight`+id+`[]" value="`+($("input[name$='text_weight_b"+id+"']").val()*1).toLocaleString(undefined, {minimumFractionDigits: 2})+`" >
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <button type="button" class="btn btn-danger remove__`+id+`" onclick="product_update.remove_input_product(`+id+','+ u+`);" ><i class="fa fa-trash"> </i></button>
                                                </div>
                                            </div>`);


                    var type_product = [];
                    var product_size=[];
                    $('[name="product_size'+id+'[]"]').each(function() {
                        product_size.push($(this).val());
                    });

                    var weight=[];
                    $('[name="weight'+id+'[]"]').each(function() {
                        weight.push(($(this).val()).toLocaleString(undefined, {minimumFractionDigits: 2}));
                    });

                    var unit=[];
                    $('[name="text_unit_b'+id+'[]"]').each(function() {
                        unit.push($(this).val());
                    });

                    for (var i = 0; i < product_size.length; i++) {
                        type_product = [null,product_size[i],weight[i],0,0,unit[i]];
                    }
                    // console.log(product_update.data.data_product);
                    product_update.data.data_product.filter(function( obj ) {
                      if (obj.id == id) {
                        obj.product_type_b.push(type_product);
                      }
                    });

                    $("input[name$='text_product_size_b"+id+"']").val("");
                    $("input[name$='text_weight_b"+id+"']").val("");
                    // $("select[name=text_unit_b"+id+"]").val("");

                }else{
                    Swal.fire({
                        icon: 'warning',
                        title: "กรุณากรอกขนาดสินค้า",
                        confirmButtonText : "OK",
                    });
                }
            },

            remove_input_product:(row,id) => {

              $(".remove_row_edit"+row+"").on("click",".remove__"+row+"", function(e){

                var del = product_update.data.data_product[row-1].product_type_b.length;
                    for (var u = 0; u < product_update.data.data_product[row-1].product_type_b.length; u++) {
                      if (product_update.data.data_product[row-1].product_type_b[u][4] == 1) {
                        del-- ;
                      }
                    }

                  for (var i = product_update.data.data_product.length - 1; i >= 0; --i) {
                      if (product_update.data.data_product[i].id == row) {
                          // product_update.data.data_product[i].product_type_b.splice(id,1);
                        if (del > 1) {
                            $(this).parent('div').parent('div').remove();
                            product_update.data.data_product[i].product_type_b[id][4] = 1;
                        }else {
                          Swal.fire({
                              icon: 'warning',
                              title: "ต้องมี ขนาดสินค้า และ น้ำหนัก อย่างน้อย 1 รายการ",
                              confirmButtonText : "OK",
                          });
                        }
                        // console.log(del);

                      }
                  }
              })

              $(".add_type_product__"+row+"").on("click",".remove__"+row+"", function(e){
                var del2 = 0;
                var del2 = product_update.data.data_product[row-1].product_type_b.length;
                    for (var u = 0; u < product_update.data.data_product[row-1].product_type_b.length; u++) {
                      if (product_update.data.data_product[row-1].product_type_b[u][4] == 1) {
                        del2-- ;
                      }
                    }
                  for (var i = product_update.data.data_product.length - 1; i >= 0; --i) {
                      if (product_update.data.data_product[i].id == row) {
                          // product_update.data.data_product[i].product_type_b.splice(id-1,1);

                          if (del2 > 0) {
                            $(this).parent('div').parent('div').remove();
                            product_update.data.data_product[i].product_type_b[id][4] = 1;
                          }else {
                            Swal.fire({
                                icon: 'warning',
                                title: "ต้องมี ขนาดสินค้า และ น้ำหนัก อย่างน้อย 1 รายการ",
                                confirmButtonText : "OK",
                            });
                          }
                      }
                  }
              })

            },

            update_product:() => {
              Swal.fire({
                  title: 'Loading..',
                  onOpen: () => {
                      swal.showLoading()
                  }
              });
              let id = product_update.data.data_product[0].product_id;
              $.ajax({
                url: "{{ url('api/master-data/product_info/${id}') }}",
                headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 },
                type: 'PUT',
                data:{
                  'data': product_update.data.data_product,
                },
                dataType: "json",
                success: function(data) {
                  if (data.status == true) {
                      Swal.fire('สำเร็จ!',data.message,'success').then(() => (window.location = "/master-data/product_information"))
                  } else {
                      Swal.fire({icon: 'warning',title: 'เตือน!',text: data.message})
                  }

                }
              });
            }

        }

        function chkNumber(ele){
        	var vchar = String.fromCharCode(event.keyCode);
        	if ((vchar<'0' || vchar>'9') && (vchar != '.')) return false;
        	ele.onKeyPress=vchar;
      	}

        $(function () {
          product_update.get_product_update();

          $(".formula").click(function(e) {
            $.each(product_update.data.data_product, function( key, value ) {
              product_update.data.data_product[key].product_formula = $("input[name=text_calculation_formula]:checked").val();
            });
          });

          $( "#delete" ).click(function(d) {
              var dele = $(this).hasClass('delete');
              // console.log(dele);
              if (dele) {
                Swal.fire({
                    icon: 'warning',
                    title: "ไม่สามารถลบข้อมูลทั้งหมดได้",
                    confirmButtonText : "OK",
                });
                return false;
              }else {
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
                    Swal.fire({
                        title: 'Loading..',
                        onOpen: () => {
                            swal.showLoading()
                        }
                    });
                    $.ajax({
                        url: window.location.origin + '/api/master-data/product_info/'+product_update.data.data_product[0].product_id,
                        type: "DELETE",
                    }).done(function(res) {
                        if (res.status == true) {
                            Swal.fire('สำเร็จ!',res.message,'success').then(() => (window.location = "/master-data/product_information"))
                        } else {
                            Swal.fire({icon: 'warning',title: 'เตือน!',text: res.message})
                        }
                    });
                }
                });
                return false;
              }

          });
        });

    </script>
@endsection
