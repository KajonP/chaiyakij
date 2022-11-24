<!-- Modal Create Merchant-->
<div class="modal fade" id="modal_create_cus" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header none-border-bottom padding-modal">
        <h5 class="modal-title frm_profile" >สร้างข้อมูลร้านค้า</h5>
        <h5 class="modal-title frm_price" >กำหนดราคาขายสินค้า</h5>
      </div>
      <div class="modal-body">
       <form id="frm_createmerchant" method="POST">
      <!-- frm_profile -->
          <div class="frm_profile">
            <div class="row  px-3">
                <div class="form-group col-md-6">
                    <label for="name_merchant">ชื่อร้านค้า :</label> <span style="color: red; font-size: 15px;">*</span>
                    <input type="text" class="form-control " name="name_merchant" id="add_name_merchant" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="tax_number">เลขประจำตัวภาษีอากร :</label>
                    <input type="text" class="form-control" name="tax_number" id="add_tax_number" pattern="[0-9]" maxlength="13" onkeypress="return isNumber(event)">
                </div>
                {{-- <div class="form-group col-md-6">
                    <label for="name_department">ชื่อหน่วยงาน :</label>
                    <input type="text" class="form-control " name="name_department" id="add_name_department">
                </div> --}}
            </div>
            <div class="row  px-3">

                <div class="form-group col-md-6">
                    <label for="phone_number">เบอร์โทรศัพท์1 :</label>
                    <input type="text" class="form-control number" name="phone_number" id="add_phone_number" pattern="[0-9]" maxlength="10" onkeypress="return isNumber(event)">
                </div>
                <div class="form-group col-md-6">
                    <label for="tax_number">เบอร์โทรศัพท์2 :</label>
                    <input type="text" class="form-control" name="phone_number2" id="add_phone_number2" pattern="[0-9]" maxlength="13" onkeypress="return isNumber(event)">
                </div>
            </div>
            <div class="row  px-3">

                <div class="form-group col-md-6">
                    <label for="phone_number">เบอร์โทรศัพท์3 :</label>
                    <input type="text" class="form-control number" name="phone_number3" id="phone_number3" pattern="[0-9]" maxlength="10" onkeypress="return isNumber(event)">
                </div>
                <div class="form-group col-md-6">
                    <label for="tax_number">เบอร์โทรศัพท์4 :</label>
                    <input type="text" class="form-control" name="phone_number4" id="add_phone_number4" pattern="[0-9]" maxlength="13" onkeypress="return isNumber(event)">
                </div>
            </div>
            <div class="row  px-3">
                <div class="form-group col-md-12">
                    <label for="phone_number">fax :</label>
                    <input type="text" class="form-control number" name="fax" id="fax" pattern="[0-9]" maxlength="10" onkeypress="return isNumber(event)">
                </div>
            </div>
            <div class="row  px-3">
                <div class="form-group col-md-12">
                    <label for="address">ที่อยู่ :</label>
                    <textarea class="form-control" class="form-control " name="address" id="add_address" rows="3"></textarea>
                </div>
            </div>
            <div class="row  px-3">
                <div class="form-group col-md-6">
                    <label for="latitude">พิกัด GPS ละติจูด :</label>
                    <input type="text" class="form-control " name="latitude" id="add_latitude"  placeholder="latitude">
                </div>
                <div class="form-group col-md-6">
                    <label for="longitude">พิกัด GPS ลองติจูด :</label>
                    <input type="text" class="form-control " name="longitude" id="add_longitude"  placeholder="longitude">
                </div>
            </div>
            <div class="row  px-3">
                <div class="form-group col-md-12">
                    <label for="link_google_map">Link Google Map :</label>
                    <input type="text" class="form-control " name="link_google_map" id="add_link_google_map"  >
                </div>
            </div>
            <div class="row  px-3">
                <div class="form-group col-md-12">
                    <label for="remark">หมายเหตุ :</label>
                    <input type="text" class="form-control " name="remark" id="add_remark"  >
                </div>
            </div>
        </div>
         <!-- end frm_profile -->
        <!-- frm_price -->
        <div class="frm_price " >
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
      <th scope="col" >ราคาขาย : หน่วย</th>

    </tr>
  </thead>
  <tbody>
 @foreach($row->spec as $value)
    <tr>
      <td><input type="text" class="form-control p-1" value="{{$value->type_name}}" readonly></td>
      <td><input type="text" class="form-control p-1" value="{{ $row->formula == '01' ? '0.35x':''}}{{sprintf('%g',$value->size_name)}} {{$items['size_unit'][$value->size_unit]}}" readonly></td>
      <td> <input type="number" class="form-control number text-right" name="u_price[{{$value->master_product_main_id}}][{{ isset($value->master_product_type_id) ? $value->master_product_type_id :'0'}}][{{$value->master_product_size_id}}]" ></td>
    </tr>
    @endforeach
    </tbody>
</table>
        </div>
</div>
        @endforeach
        </div>
         <!-- end frm_price -->
         </form>
      </div>
      <div class="modal-footer none-border-top justify-content-center">
      <button type="button" class="btn btn-danger px-4" data-dismiss="modal">ยกเลิก</button>
     <button type="button" class="btn btn-success px-5 frm_profile" id="act_next">ถัดไป <i class="fas fa-chevron-right "></i></button>
     <button type="button" class="btn btn-success px-5 frm_price" id="act_create">ยืนยันทำรายการ</button>

      </div>
    </div>
  </div>
</div>

