@extends('layouts.app')

@section('content')
<div class="form-main" id="page-delivery-order-detail">
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
                        <li class="breadcrumb-item active" aria-current="page">ดูใบสั่งซื้อ</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-6 align-self-end">
                <h2>ใบสั่งซื้อเลขที่ : {{ $data['Order']->order_number }}</h2>
            </div>
            <div class="col-12 col-md-6 text-right">
                @if($data['Order']->staus_send != '04')
                 <a href="{{ route("delivery.list.delivery", $data['Order']->order_id) }}" class="btn btn-lg btn-primary">ดูรายการบิลย่อย</a>
                @endif
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
            <div class="form-group col-md-2">
                <label for="">จำนวนสินค้าค้างส่ง :</label>
                <br>
                <span class="c-ff871e">{{  number_format($data['Order']->qty_all - $data['Order']->sum_order_delivery_item) }}</span>
            </div>
            <div class="form-group col-md-2">
                <label for="">จำนวนสินค้าเคลม ค้างส่ง :</label>
                <br>
                <span class="c-ff871e">{{  number_format($data['Order']->sum_order_delivery_item_claim) }}</span>
            </div>
            <div class="form-group col-md-2 text-center">
                <label for="">สถานะการจัดส่ง :</label>
                <br>
                @switch($data['Order']->staus_send)
                @case('00')
                <span class="badge-pill text-white py-1" style="background-color:#FF871E">สั่งซื้อสินค้า</span>
                @break
                @case('01')
                <span class="badge-pill text-white py-1" style="background-color:#0095DD">ดำเนินการจัดส่ง</span>
                @break
                @case('02')
                <span class="badge-pill text-white py-1" style="background-color:#EE6C98">เคลม</span>
                @break
                @case('03')
                <span class="badge-pill text-white py-1" style="background-color:#27C671">ส่งสำเร็จ</span>
                @break
                @endswitch

            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12 form-content mb-3 table-responsive">
                <table class="table table-striped dt-responsive nowrap" style="width:100%" id="table">
                    <thead>
                        <tr class="text-center">
                            <th>#</th>
                            <th>สินค้า</th>
                            <th>ประเภทสินค้า</th>
                            <th>ขนาด</th>
                            <th>ราคาต่อหน่วย</th>
                            <th>รวมเป็นเงิน</th>
                            <th>หมายเหตุ</th>
                            <th>น้ำหนัก</th>
                            <th>จำนวนที่สั่ง</th>
                            <th>ส่งสินค้า</th>
                            <th>สินค้าค้างส่ง</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['OrderItem'] as $key => $item)
                        <tr class="text-center">
                            <td>{{$key + 1}}</td>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->product_type_name }}</td>
                            <td>{{ $item->product_size_name }}</td>
                            <td>{{ number_format($item->price,2) }}</td>
                            <td>{{ number_format($item->total_price,2) }}</td>
                            <td>{{ $item->remark }}</td>
                            <td>{{ number_format($item->product_weight) }}</td>
                            <td>{{ number_format($item->qty) }}</td>
                            <td><span class="c-0095dd">{{ number_format($item->sum_order_delivery_item) }}</span></td>
                            <td><span class="c-ff871e">{{ number_format($item->qty - $item->sum_order_delivery_item) }}</span></td>
                        </tr>
                        @endforeach
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
