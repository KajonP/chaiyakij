@extends('layouts.app')



@section('content')

<div class="form-main" id="page-claim-order-detail">

    <div class="container-fluid">

        <div class="row">

            <div class="col-12">

                <nav aria-label="breadcrumb">

                    <ol class="breadcrumb">

                        <li class="breadcrumb-item">

                            <a href="{{ route('claim') }}">เมนูการคืน/เคลม สินค้า</a>

                        </li>

                        <li class="breadcrumb-item active" aria-current="page">รายการคืน/เคลมสินค้า</li>

                    </ol>

                </nav>

            </div>

        </div>

        <div class="row">

            <div class="col-12 col-md-6 align-self-end">

                <h2>ใบสั่งซื้อเลขที่ : {{ $data['Order']->order_number }}</h2>

            </div>

            <div class="col-12 col-md-6 text-right">

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




        <div class="row mt-5">

            <div class="col-12 form-content mb-3 table-responsive">

                <table class="table table-striped dt-responsive nowrap" style="width:100%" id="table">

                    <thead>

                        <tr class="text-center">

                            <th>#</th>

                            <th>เลขที่รายการคืน/เคลม</th>

                            <th>วันที่ทำรายการ</th>

                            <th>จำนวนสินค้าคืน/เคลม</th>

                            <th>สถานะคืน/เคลม</th>

                            <th>จัดการ</th>

                        </tr>

                    </thead>

                    <tbody>

                      @if (count($data['OrderDelivery']) > 0)
                        @foreach ($data['OrderDelivery'] as $key => $item)
                            <tr class="text-center">
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->order_delivery_number }}</td>
                                <td>{{ formatDateThat($item->created_date) }}</td>
                                <td>{{ number_format( $item->item) }}</td>
                                <td> @if ($item->delivery_type == '01')
                                    <span class="badge-pill text-white py-1"
                                        style="font-size:12px; background-color:red">{{ config('status.delivery_type')[$item->delivery_type] }}</span>
                                    @else
                                    <span class="badge-pill text-white py-1"
                                        style="font-size:12px; background-color:#EE6C98">{{ config('status.delivery_type')[$item->delivery_type] }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('claim.list.detail',['order_id' => $data['Order']->order_id, 'delivery_id' => $item->order_delivery_id]) }}"
                                        type="button" class="btn btn-dark"> ดูรายละเอียด
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                      @else
                          <tr class="text-center">
                              <td>{{" - "}}</td>
                              <td>{{" - "}}</td>
                              <td>{{" - "}}</td>
                              <td>{{" - "}}</td>
                              <td>{{" - "}}</td>
                              <td>{{" - "}}</td>
                          </tr>
                      @endif

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>



@endsection



@section('js')

<script>



</script>

@endsection
