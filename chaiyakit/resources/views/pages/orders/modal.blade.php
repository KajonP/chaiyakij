<!-- Modal -->

<div class="modal fade" id="type_normal_modal" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header none-border-bottom padding-modal">
      @hasanyrole('super-admin|manager')
        <h2 class="modal-title" >ใบสั่งซื้อปกติ</h2>
        @else
        <h2 class="modal-title" >เลือกภาษี</h2>
        @endhasanyrole
      </div>
      <div class="modal-body">
      <div class="col-md-12">
      <div class="btn-group btn-group-toggle col-md-12" data-toggle="buttons" >
				<label class="btn btn-primary-1 {{ isset($data) ? ($data['Order']->status_vat == '00' ? 'active' : '')  : 'active'}}" id="type_00_vat">
    <input type="radio" name="status_vat" id="order_type_00_vat" value="00"> คิดภาษี
  </label>
  <label class="btn btn-primary-1 {{ isset($data) ? ($data['Order']->status_vat == '01' ? 'active' : '')  : ''}}" id="type_00_none">
    <input type="radio" name="status_vat" id="order_type_00_none" value="01" checked="checked"> ไม่คิดภาษี
  </label>
  </div>

    <div class="col-md-12 pt-3"  id="show_vat" style="{{ isset($data) ? ($data['Order']->status_vat == '01' ? 'display: none;"' : '')  : ''}}">
        @foreach($masterVat as $value)
        <div class="custom-control custom-radio">
        <input type="radio"  class="custom-control-input" name="vat"  id="vat_{{$value->master_vat_id}}" value="{{ $value->vat }}" {{ $value->is_default == '01' ? 'checked=checked' :''  }}>
        <label class="custom-control-label font-weight-bold" for="vat_{{$value->master_vat_id}}">ภาษี {{ $value->vat }} %</label>
        </div>
        @endforeach
    </div>

  </div>
      </div>
      <div class="modal-footer none-border-top justify-content-center">
        <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
        <button type="button" class="btn btn-success" id="act_select">ยืนยันทำรายการ</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="cancen_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">ยกเลิกรายการ</h5>

      </div>
      <div class="modal-body">
        ยืนยันยกเลิกรายการ ?
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-danger" data-dismiss="modal">ไม่</button>
        <button type="button" class="btn btn-success" id="act_cancel">ยืนยัน</button>
      </div>
    </div>
  </div>
</div>
