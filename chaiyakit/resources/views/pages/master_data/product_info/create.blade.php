@extends('layouts.app')

@section('content')
<div class="form-main" id="product_info">
    <div class="container-fluid">
      <div class="row">
          <div class="col-12">
              <h2>เพิ่มข้อมูลสินค้า</h2>
          </div>
          <div class="col-12">
              <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a
                              onclick="window.history.go(-1); return false;">จัดการข้อมูลสินค้า</a>
                      </li>
                      <li class="breadcrumb-item active" aria-current="page">สร้างข้อมูลสินค้า</li>
                  </ol>
              </nav>
          </div>
      </div>
        <hr>

        <div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="inputEmail4">ชื่อสินค้า : </label> <span class="color-red">*</span>
                    <input type="text" class="form-control product_name" placeholder="ชื่อสินค้า" name="product_name">
                </div>

                <div class="form-group col-md-3">
                  <label for="inputEmail4"></label>
                  <div class="custom-control custom-radio btn-redio text-center">
                    <input type="radio" class="custom-control-input formula" id="pole_calculation_formula" name="calculation_formula" value="00" checked required>
                    <label class="custom-control-label" for="pole_calculation_formula">สูตรคํานวณเสา</label>
                  </div>
                </div>
                <div class="form-group col-md-3">
                  <label for="inputEmail4"></label>
                  <div class="custom-control custom-radio btn-redio">
                    <input type="radio" class="custom-control-input formula" id="slab_calculation_formula" name="calculation_formula" value="01" required>
                    <label class="custom-control-label" for="slab_calculation_formula">สูตรคํานวณแผ่นพื้น</label>
                  </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="inputCity">ประเภทสินค้า : </label>
                    <input type="text" class="form-control product_type" placeholder="ประเภทสินค้า" name="product_type">
                </div>
                @php
                  $i = 0;
                @endphp
                <div class="form-group col-md-3 top-btn-add"></div>
            </div>
            <div class="col-md-10">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="inputCity">ขนาดสินค้า : </label> <span class="color-red"> *</span>
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
                        <label for="inputState">น้ำหนัก (กรัม) : </label> <span class="color-red"> *</span>
                        <input type="text" class="form-control number" name="text_weight" maxlength="8" OnKeyPress="return chkNumber(this)" placeholder="กรอกน้ำหนัก (กรัม)">
                    </div>

                    <div class="form-group col-md-2 top-btn-del">
                        <button type="button" class="btn btn-secondary" id="add_type_product" onclick="product.add_form_input_product();"><i class="fa fa-plus"> </i></button>
                    </div>
                </div>
                <div class="add_form_input_type_product"></div>
            </div>
            <div class="form-group col-md-5 text-right">
                <button type="button" class="btn btn-secondary col-5" id="add_form_product" onclick="product.add_form_product();">เพิ่มรายการ</button>
            </div>

            <hr>

            <form id="product">
              <meta name="csrf-token" content="{{ csrf_token() }}">
              <div class="add_form_product"></div>

              <div class="col-12 text-center mt-5">
                  <a onclick="window.history.go(-1); return false;" type="button" class="btn btn-danger text-white">ยกเลิก</a>
                  <button type="button" class="btn btn-success" onclick="product.create_product();">ยืนยันทำรายการ</button>
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
        var product = {
            data: false,
            data_check_product_type: false,
            data_add: [],
            data_row: 1,

            add_form_input_product:() => {
              var productname = true;
              if ($("input[name$='product_name']").val() == "") {
                Swal.fire({
                    icon: 'warning',
                    title: "กรุณากรอก ชื่อสินค้า",
                    confirmButtonText : "OK",
                });
              }else {
                $.ajax({
                  url: window.location.origin + '/api/master-data/check_product_name/'+$("input[name$='product_name']").val(),
                  type: 'get',
                  dataType: "json",
                  success: function(data) {
                    // console.log(data.length)
                    if (data.length > 0) {
                      Swal.fire({
                          icon: 'warning',
                          title: "มีสินค้านี้อยู่ในระบบแล้ว",
                          confirmButtonText : "OK",
                      });
                      productname = false;
                      // return false;
                    }
                    if (productname == true) {
                      if($("input[name$='text_product_size']").val() != "" && $("input[name$='text_weight']").val() != "" && $('select[name=text_unit]').val() != "" && $( "input[name='calculation_formula']:checked" ).val() != "") {
                          var input_product = $(".add_form_input_type_product");
                          var x = 1;
                          var unit = $('select[name=text_unit]').val();
                          $(input_product).append( `<div class="form-row remove_product">
                                                      <div class="form-group col-md-3">
                                                          <input type="text" class="form-control" name="text_product_size[]" value="`+$("input[name$='text_product_size']").val()+`" disabled>
                                                      </div>
                                                      <div class="form-group col-md-3">
                                                        <input type="text" class="form-control text-center" name="unit[]" value="`+$("#text_unit option:selected").text()+`" disabled>
                                                        <input type="hidden" class="form-control" name="text_unit[]" value="`+unit+`" disabled>
                                                      </div>
                                                      <div class="form-group col-md-3">
                                                          <input type="text" class="form-control" name="text_weight[]" value="`+$("input[name$='text_weight']").val()+`" disabled>
                                                      </div>
                                                      <div class="form-group col-md-2">
                                                          <button type="button" class="btn btn-danger remove" onclick="product.remove_row(`+product.data_row+`);" ><i class="fa fa-trash"> </i></button>
                                                      </div>
                                                  </div>`);

                          var type_product = [];
                          var product_size=[];
                          $('[name="text_product_size[]"]').each(function() {
                              product_size.push($(this).val());
                          });

                          var unit=[];
                          $('[name="text_unit[]"]').each(function() {
                              unit.push($(this).val());
                          });

                          var unit_name=[];
                          $('[name="unit[]"]').each(function() {
                              unit_name.push($(this).val());
                          });

                          var weight=[];
                          $('[name="text_weight[]"]').each(function() {
                              weight.push($(this).val());
                          });

                          for (var i = 0; i < product_size.length; i++) {
                              type_product[i] = [product_size[i],weight[i],unit[i],unit_name[i]];
                          }
                          // console.log(type_product);

                          var f = 1;
                          var data_product = {
                              'id' : product.data_add.length == 0 ? 1 : product.data_add[product.data_add.length-1].id + 1 ,
                              "product_name" : $("input[name$='product_name']").val(),
                              "product_type_a" : $("input[name$='product_type']").val() == "" ? "":$("input[name$='product_type']").val(),
                              "formula" : $( "input[name='calculation_formula']:checked" ).val(),
                              "product_type_b" : type_product,
                          }
                          // product.add_form_product(data_product);
                          product.data = {
                              "data_product" : data_product,
                          };

                          $("input[name$='text_product_size']").val("");
                          $('select[name=text_unit]').val("");
                          $("input[name$='text_weight']").val("");
                          product.data_row++;
                      }
                      else{
                          Swal.fire({
                              icon: 'warning',
                              title: "กรุณากรอก ขนาดสินค้า และ น้ำหนักสินค้า",
                              confirmButtonText : "OK",
                          });
                      }
                    }
                  }
                });
              }



            },

            remove_row:(id) =>{
              $(".add_form_input_type_product").on("click",".remove", function(e){
                  $(this).parent('div').parent('div').remove();

                  for (var i = product.data.data_product.product_type_b.length - 1; i >= 0; --i) {
                      if (i == id) {
                          product.data.data_product.product_type_b.splice(i,1);
                      }
                  }

                  product.data = false;
              })
            },

            add_form_product:(d) => {
              // console.log(d);

              if ($("input[name$='product_type']").val() == "") {
                $(".product_type ").attr('disabled','disabled');
              }else {
                product.data_check_product_type = $("input[name$='product_type']").val();
              }

              var title = '';
                var ck = true;
                if ($("input[name$='product_name']").val() == "") {
                  ck = false;
                  title = "กรุณากรอกข้อมูลให้ครบ";
                }

                // $( ".product_type" ).hasClass( "disabled" ) == false
                if (product.data_check_product_type != "") {
                  if ($("input[name$='product_type']").val() == "") {
                    ck = false;
                    title = "กรุณากรอกข้อมูลให้ครบ";
                    $(".product_type ").attr('disabled',false);
                  }
                }

                if ($( "input[name='calculation_formula']:checked" ).val() == "") {
                  ck = false;
                  title = "กรุณาเลือสูตรคํานวณ";
                }

                if (product.data == false) {
                  ck = false;
                  title = "กรุณากรอกประเภทสินค้า";
                }

                if (product.data != false) {
                  if (product.data.data_product.product_type_b.length <= 0) {
                    ck = false;
                    title = "กรุณากรอกข้อมูลให้ครบ";
                  }
                }

                if ($("input[name$='text_product_size']").val() != "" && $("input[name$='text_weight']").val() != "") {
                    ck = false;
                    title = "กรุณากดปุ่ม "+"&nbsp;&nbsp;"+' <button type="button" class="btn btn-secondary"><i class="fa fa-plus"> </i></button> '+"&nbsp;&nbsp;"+" เพื่อเพิ่มข้อมูล";
                }


                if (ck == false) {
                  Swal.fire({
                      icon: 'warning',
                      title: title,
                      confirmButtonText : "OK",
                  });
                }else {
                  var data = product.data.data_product;
                  var stringb = '';
                  var str = '';
                  var del_form_product = $('.add_form_product');
                  var x = 1;
                  var y = 1;
                  x++;

                  stringb = `<div>
                  <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="inputCity">ประเภทสินค้า</label>
                        <input type="text" class="form-control" placeholder="ประเภทสินค้า" name="product_type[]" value="`+data.product_type_a+`" readonly>
                        <input type="hidden" name="product_name[]" value="`+data.product_name+`">
                    </div>

                    <div class="form-group col-md-2 top-btn-del">
                        <button type="button" class="btn btn-danger del_form_product" id="del_form_product" onclick="product.remove_div(`+ (product.data_add.length+1) +`);" ><i class="fa fa-trash"> </i> ลบประเภทสินค้า</button>
                    </div>
                  </div>
                  <div class="col-md-9">`;
                  for (var i = 0; i < data.product_type_b.length; i++) {
                      str += `
                        <div class="form-row">
                          <div class="form-group col-md-3">
                              <label for="inputCity">ขนาดสินค้า :</label>
                              <input type="text" class="form-control" name="product_size[]" value="`+data.product_type_b[i][0]+`" maxlength="8" OnKeyPress="return chkNumber(this)" placeholder="กรอกขนาดสินค้า" readonly>
                          </div>
                          <div class="form-group col-md-3">
                              <label for="inputCity">หน่วย : </label> <span class="color-red"> *</span>
                              <select class="form-control" name="unit" id="unit" disabled>
                                <option value="" >`+data.product_type_b[i][3]+`</option>
                              </select>
                          </div>
                          <div class="form-group col-md-3">
                              <label for="inputState">น้ำหนัก (กรัม) :</label>
                              <input type="text" class="form-control number" name="weight[]" value="`+data.product_type_b[i][1]+`" maxlength="8" OnKeyPress="return chkNumber(this)" placeholder="กรอกน้ำหนัก (กรัม)" readonly>
                          </div>
                        </div>`;


                  }
                  stringb +=  str;
                  stringb += `</div><hr></div>`;



                  // console.log(product.data);
                  product.data_add.push(product.data.data_product);

                  $.each(product.data_add, function( key, value ) {
                     product.data_add[key].formula = $("input[name=calculation_formula]:checked").val();
                  });


                  $('.add_form_product').append(stringb);
                  product.data = false;
                  $(".remove_product").remove('div');
                  // $("input[name$='product_name']").val("");
                  $("input[name$='product_type']").val("");
                  y++;
                  $(".product_name ").attr('disabled','disabled');
                  // $("#pole_calculation_formula ").attr('disabled','disabled');
                }



            },

            remove_div:(remove_id) =>{
              $('.add_form_product').on("click",".del_form_product", function(e){
                  $(this).parent('div').parent('div').parent('div').remove();
                  // x--;

                  for (var i = product.data_add.length - 1; i >= 0; --i) {
                      if (product.data_add[i].id == remove_id) {
                          product.data_add.splice(i,1);
                      }
                  }

                  if (product.data_add.length == 0) {
                    $(".product_name ").attr('disabled',false);
                  }

              })
            },

            create_product:() => {

              if (product.data_add.length <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: "กรุณากดเพิ่มรายการ",
                    confirmButtonText : "OK",
                });
              }else {

                var pro_check = true;
                var pro_check2 = true;

                if (product.data_add[0].product_type_a == "") {
                  pro_check = false;
                } else {
                  pro_check = true;
                }

                if (product.data.data_product) {
                  if (product.data.data_product.product_type_a == "") {
                    pro_check2 = false;
                  }else {
                    pro_check2 = true;
                  }
                }else {
                  pro_check2 = pro_check;
                }

                if (pro_check != pro_check2) {
                  Swal.fire({
                      icon: 'warning',
                      title: "กรุณากรอกประเภทสินค้า แล้วกดเพิ่มรายการ",
                      confirmButtonText : "OK",
                  });
                }else {
                  if (product.data.data_product) {
                    Swal.fire({
                        title: 'มีข้อมูลรายละเอียดสินค้ายังไม่ได้เพิ่ม ?',
                        text: "คุณต้องการเพิ่มรายละเอียดสินค้าหรือไม่ !",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'เพิ่ม',
                        cancelButtonText: 'ไม่เพิ่ม',
                      }).then((result) => {
                        if (result.value) {
                          Swal.fire({
                              title: 'Loading..',
                              onOpen: () => {
                                  swal.showLoading()
                              }
                          });
                          product.add_form_product();
                          $.ajax({
                            url: "{{ url('api/master-data/product_info') }}",
                            headers: {
                               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                             },
                            type: 'post',
                            data:{
                              'data': product.data_add,
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
                        else {
                          Swal.fire({
                              title: 'Loading..',
                              onOpen: () => {
                                  swal.showLoading()
                              }
                          });
                          $.ajax({
                            url: "{{ url('api/master-data/product_info') }}",
                            headers: {
                               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                             },
                            type: 'post',
                            data:{
                              'data': product.data_add,
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
                      })
                  }
                  else {
                    if ($("input[name$='text_product_size']").val() != "" && $("input[name$='text_weight']").val() != "") {
                      Swal.fire({
                          icon: 'warning',
                          title: "กรุณากรอกเพิ่มประเภทสินค้า",
                          confirmButtonText : "OK",
                      });
                    }else {
                      Swal.fire({
                          title: 'Loading..',
                          onOpen: () => {
                              swal.showLoading()
                          }
                      });
                      $.ajax({
                        url: "{{ url('api/master-data/product_info') }}",
                        headers: {
                           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                         },
                        type: 'post',
                        data:{
                          'data': product.data_add,
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
                }




              }
            },

        }

        function chkNumber(ele){
        	var vchar = String.fromCharCode(event.keyCode);
        	if ((vchar<'0' || vchar>'9') && (vchar != '.')) return false;
        	ele.onKeyPress=vchar;
      	}

        $(function () {
          $(".formula").click(function(e) {
            $.each(product.data_add, function( key, value ) {
               product.data_add[key].formula = $("input[name=calculation_formula]:checked").val();
            });
          });
        });


    </script>
@endsection
