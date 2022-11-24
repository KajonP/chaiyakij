@extends('layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('css/print.min.css') }}">
@endsection
@section('content')
<div class="form-main" id="page-delivery-detail">
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
                        <li class="breadcrumb-item">
                            <a href="{{ route('delivery.list.delivery',$order_id) }}">รายการจัดส่ง</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a
                                href="{{ route('delivery.list.delivery.confirm',[$order_id,$delivery_id]) }}">จัดส่งสินค้าที่ค้าง</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">สรุปใบขนส่งสินค้า</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-6 align-self-end">
                <h2>สรุปใบขนส่งสินค้าเลขที่ :
                    {{ $data['Order']->order_number .'/'. $data['OrderDelivery']->order_delivery_number }}</h2>
            </div>
            <div class="col-12 col-md-6 d-inline-flex align-items-center justify-content-end">
                <div class="custom-control custom-checkbox pr-3">
                    <input type="checkbox" class="custom-control-input" id="show_amount" name='show_amount' checked>
                    <label class="custom-control-label" for="show_amount">แสดงจำนวนเงิน</label>
                </div>
                <button class="btn btn-outline-secondary" onclick="btn_printJS()"><i class="fas fa-print"></i>
                    Print</button>
            </div>
        </div>
        {{-- @include('pages.delivery.printcyc') --}}
        {{-- <form method="post" action="#" id="printJS-form" class="d-none">
            <table class="" style="table-layout: fixed; width: 699.21px">
                <tr>
                    <td colspan="2" class="pl-3" style="text-align:left!important;font-size:20px;">ใบสั่งซื้อเลขที่
                        {{ $data['Order']->order_number }}
        </td>
        <td colspan="2"></td>
        <td colspan="2" class="text-right pr-3" style="text-align:right!important;font-size:20px;">
            เลขที่การจัดส่ง
            {{ $data['OrderDelivery']->order_delivery_number }}</td>
        </tr>
        <tr>
            <td colspan="6"></td>
        </tr>
        <tr style="height: 35px;">
            <td colspan="1"></td>
            <td colspan="3" style="text-align:left;"><span>{{ $data['Order']->name_merchant }}</span></td>
            <td colspan="2"><span
                    style="white-space: nowrap;padding-left: 1rem">{{ formatDateThatNoTime(date("d-m-Y")) }}</span>
            </td>
        </tr>
        <tr style="height: 35px;">
            <td colspan="1"></td>
            <td colspan="3" style="text-align:left;">
                <span style="word-break: break-word;">{{ $data['Order']->address }}
                </span>
            </td>
            <td colspan="2"><span
                    style="white-space: nowrap;padding-left: 1rem">{{  $data['Order']->phone_number }}</span>
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @for ($i = 0; $i < 6; $i++) <tr class="text-center">
            @if (isset($data['OrderDeliveryItem'][$i]))
            <td>{{ number_format($data['OrderDeliveryItem'][$i]->delivery_item_qty) }}</td>
            <td>
                {{ $data['OrderDeliveryItem'][$i]->product_name .' '.number_format($data['OrderDeliveryItem'][$i]->product_size_name,2) .' '. config('sizeunit')[$data['OrderDeliveryItem'][$i]->product_size_unit] }}
            </td>
            <td>
                {{ number_format($data['OrderDeliveryItem'][$i]->total_size,2) .' '. config('sizeunit')[$data['OrderDeliveryItem'][$i]->size_unit]}}
            </td>
            <td>{{ number_format($data['OrderDeliveryItem'][$i]->price,2) }}</td>
            <td>
                <span class="show_amount">{{ number_format($data['OrderDeliveryItem'][$i]->total_price,2) }}</span>
            </td>
            <td>{{ $data['OrderDeliveryItem'][$i]->remark }}</td>
            @endif
            </tr>
            @endfor
            <tr class="">
                <td class=""></td>
                <td colspan="3" valign="top" rowspan="5" style="line-height:30px;text-align:left!important;">
                    <span>
                        {{ $data['OrderDelivery']->product_return_claim_id ? '**หมายเหตุ : เคลมสินค้า' : '' }}
                    </span>
                </td>
                <td class=""></td>
                <td class=""></td>
            </tr>
            <tr class="text-center">
                <td colspan="4"></td>
                <td><span class="show_amount">{{ number_format($data['Order']->grand_total,2) }}</span></td>
                <td></td>
            </tr>
            </table>
            </form> --}}
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
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="" class="label-blue">ที่อยู่ :</label>
                    <br>
                    <span>{{ $data['Order']->address }}</span>
                </div>
            </div> --}}

            <div class="row mt-3">
                <div class="col-12 form-content mb-3 table-responsive">
                    <table class="table table-striped dt-responsive nowrap" style="width:100%" id="table">
                        <thead>
                            <tr class="text-center">
                                <td>#</td>
                                <th>สินค้า</th>
                                <th>ประเภทสินค้า</th>
                                <th>ขนาดความยาว</th>
                                <th>ราคาต่อหน่วย</th>
                                <th>ราคาเพิ่มเติมต่อหน่วย</th>
                                <th>รวมเป็นเงิน</th>
                                <th>จำนวนตารางเมตร</th>
                                <th>หมายเหตุ</th>
                                <th>น้ำหนักรวม</th>
                                <th>ส่งสินค้า</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['OrderDeliveryItem'] as $key => $item)
                            <tr class="text-center">
                                <td>{{ $key+1 }}</td>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->product_type_name }}</td>
                                <td>{{ number_format($item->product_size_name,2) .' '. config('sizeunit')[$item->product_size_unit] }}
                                </td>
                                <td>{{ number_format($item->price,2) }}</td>
                                <td>{{ number_format($item->addition,2) }}</td>
                                <td>{{ number_format($item->total_price,2) }}</td>
                                <td>{{ number_format($item->total_size,2) .' '. config('sizeunit')[$item->size_unit]}}
                                </td>
                                <td>{{ $item->remark }}</td>
                                <td>{{ ($item->product_weight < 1000) ? number_format($item->product_weight).' '.config('sizeunit')['04'] : number_format($item->product_weight/1000,2).' '.config('sizeunit')['05'] }}
                                </td>
                                </td>
                                <td class="c-0095dd">{{ $item->delivery_item_qty }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <hr>

                </div>
            </div>
            <div class="row">
                <div class="col-7 px-4">
                    <div class="row border p-2">
                        <div class="col-12">
                            <h1 class="title-1">จัดการคิวรถ</h1>
                        </div>
                        <div class="col-12">
                            <span class="label-blue">น้ำหนักสินค้าที่เลือกทั้งหมด :</span>
                            <span
                                class="float-right">{{ ($data['OrderDelivery']->product_weight_all < 1000) ? number_format($data['OrderDelivery']->product_weight_all).' '.config('sizeunit')['04'] : number_format($data['OrderDelivery']->product_weight_all/1000,2).' '.config('sizeunit')['05'] }}</span>
                        </div>
                        <div class="col-12">
                            <span class="label-blue">วันที่จัดส่ง :</span>
                            <span
                                class="float-right">{{ formatDateThatNoTime($data['OrderDelivery']->date_schedule) .' '. $data['OrderDelivery']->round_name }}</span>
                        </div>
                        <div class="col-12">
                            <span class="label-blue">ประเภทรถ :</span>
                            <span
                                class="float-right">{{ $data['OrderDelivery']->type .' - '. $data['OrderDelivery']->license_plate  }}</span>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-5 px-4">
                <div class="row border p-2">
                    <div class="col-12">
                        <h1 class="title-1">จำนวนเงิน</h1>
                    </div>
                    <div class="col-12">
                        <span class="label-blue">รวมเงิน :</span>
                        <span class="float-right">{{ number_format($data['Order']->price_all,2) }}</span>
            </div>
            <div class="col-12">
                <span class="label-blue">ภาษีมูลค่าเพิ่ม 7% :</span>
                <span class="float-right">{{ number_format($data['Order']->vat_no,2) }}</span>
            </div>
            <div class="col-12">
                <span class="label-blue">จำนวนเงินรวมทั้งสิ้น :</span>
                <span class="float-right">{{ number_format($data['Order']->grand_total,2) }}</span>
            </div>
    </div>
</div> --}}
</div>
@include('pages.delivery.cyc')
</div>
{{-- @include('pages.orders.cyc') --}}
</div>
@endsection

@section('js')

{{-- <script src="{{ asset('js/print.min.js') }}"></script> --}}
<script type="text/javascript">
 $('#printCyc').hide()


    function btn_printJS(){
        $('#printCyc').hide();
        if($('#show_amount').is(":checked")){
            $('.hid_price').show();
        }else{
            $('.hid_price').hide();
        }

        var printContents = document.getElementById('printCyc').innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;

    }

</script>
@endsection