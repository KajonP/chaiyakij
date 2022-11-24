<!-- Modal Create Merchant-->
<div class="modal fade" id="modal_create_other" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header none-border-bottom padding-modal">
        <h5 class="modal-title " >สินค้าอื่นๆ</h5>
      
      </div>
      <div class="modal-body">
       <form id="frm_createother" method="POST">
      <!-- frm_product -->
          <div class="frm_product">
      <div class="row  px-3">
                <div class="form-group col-md-6">
                    <label for="o_name">ชื่อสินค้า :</label> <span style="color: red; font-size: 15px;">*</span>
                    <div class="input-group   pl-0 pr-0 custom-templates">
    <input type="search" class="form-control  border typeahead_other"  data-provide="typeahead" name="o_name" id="search_other" placeholder="ค้นหา/เพิ่ม ชื่อสินค้า" required>

	</div> <input type="hidden" name="o_main_id" id="o_main_id"  value="">
                   
                </div>
                <div class="form-group col-md-6">
                    <label for="o_count_unit">หน่วยนับ :</label> <span style="color: red; font-size: 15px;">*</span>
                   <select name="o_count_unit" class="form-control " id="o_count_unit">
                   @foreach($items['count_unit'] as $key =>$unit)
                    <option value="{{$key}}">{{$unit}}</option>
                   @endforeach 
                   </select>
                </div>
               
    </div>
    <div class="row  px-3">
                  <div class="form-group col-md-6">
                    <label for="o_type">ประเภทสินค้า :</label>
                    <div class="input-group   pl-0 pr-0 custom-templates">
    <input type="search" class="form-control  border typeahead_other"  data-provide="typeahead" name="o_type" id="search_typeother" placeholder="ค้นหา/เพิ่ม ประเภทสินค้า" >

	</div> <input type="hidden" name="o_type_id" id="o_type_id"  value="">
                
                </div>
                <div class="form-group col-md-6">
                <div class="custom-control custom-radio btn-redio">
                <input type="radio" class="custom-control-input formula" id="o_calculation_formula_00" name="o_calculation_formula" value="00" checked >
                    <label class="custom-control-label" for="o_calculation_formula_00">สูตรคํานวณเสา</label>
                </div>
                <div class="custom-control custom-radio btn-redio">
                <input type="radio" class="custom-control-input formula" id="o_calculation_formula_01" name="o_calculation_formula" value="01" >
                    <label class="custom-control-label" for="o_calculation_formula_01">สูตรคํานวณแผ่นพื้น</label>
                </div>
                </div>
                </div>
                
    </div>
    <div class="row  px-3">
                  <div class="form-group col-md-6">
                    <label for="o_size">เลือกขนาดสินค้า :</label>
                    <select name="o_size" id="o_size"  class="form-control ">
                    </select>
                </div>
                <div class="form-group col-md-6">
               
                
    </div>
 
    <div class="row  px-3 pt-2 border-top">
            <div class="form-group col-md-4 ">
                    <label for="o_size_name">เพิ่มขนาดสินค้า :</label>
                    <input type="number" class="form-control" name="o_size_name" id="o_size_name" min="1">
                </div>
                <div class="form-group col-md-4">
                    <label for="o_size_unit">หน่วยขนาด  :</label>
                    <select name="o_size_unit" id="o_size_unit" class="form-control ">
                   @foreach($items['size_unit'] as $key =>$unit)
                    <option value="{{$key}}">{{$unit}}</option>
                   @endforeach 
                   </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="o_weight">น้ำหนัก (กรัม) </label>
                    <input type="number" class="form-control" name="o_weight" id="o_weight" min="1">
                </div>
               
    </div>
  
   
     
         <!-- end frm_product -->
  
       

    
        
     
         </form>
         </div>
      </div>
      <div class="modal-footer none-border-top justify-content-center">
      <button type="button" class="btn btn-danger px-4" data-dismiss="modal">ยกเลิก</button>
     <button type="button" class="btn btn-success px-5 " id="act_createother">ยืนยันทำรายการ</button>

      </div>
    </div>
  </div>
</div>

