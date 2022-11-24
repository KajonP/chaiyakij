@extends('layouts.app')
@section('css')
<style type="text/css" media="print">
    @page {
        size:  22cm 17.7cm landscape;
        margin: 0;
        /* margin-top: 1.8cm;
        margin-bottom: 1.8cm;
        margin-left: 1.8cm;
        margin-right: 1.8cm; */
    }

    @media print {
        /* label {
            display: none;
        } */

    }
    }

</style>
<style>
    #table ,td {
        /* border : solid 1px; */
        height: 37.7952755906px;
        line-height: 43px;
        font-size: 15px;
        padding-left: 10px;
        padding-right: 10px;
    }
</style>
@endsection
@section('content')
<div class="form-main" id="page-delivery-order-detail">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('orders') }}">จัดการใบสั่งซื้อ</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('orders.add') }}">สร้างใบสั่งซื้อ</a>
                        </li>
                        <li class="breadcrumb-item">
                        <a href="{{ route('delivery.list') }}">การจัดส่งสินค้า</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">สรุปการสั่งซื้อ</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-6 align-self-end">
                <h2>สรุปใบสั่งซื้อเลขที่ : {{ $data['Order']->order_number }}</h2>
            </div>
            <div class="col-12 col-md-6 text-right">
            <button class="btn btn-outline-secondary" onclick="btn_printJSOrder()"><i class="fas fa-print"></i>
                    Print Order</button>
            <button class="btn btn-outline-secondary" onclick="btn_printJS()"><i class="fas fa-print"></i>
                PeraPrint CYC</button>

            </div>
            <div class="col-12 col-md-12 text-right">
            <input id="price_hide" type="checkbox" /> ไม่แสดงเงิน
            </div>
        </div>
        <div id="printJSOrder-form">
            <div class="print-only">
                    <h2>สรุปใบสั่งซื้อเลขที่ : {{ $data['Order']->order_number }}</h2>
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

            <div class="row mt-5">
                <div class="col-12 form-content mb-3 table-responsive">
                    <table class="table table-striped dt-responsive nowrap" style="width:100%" id="table">
                        <thead>
                            <tr class="text-center">
                                <th>#</th>
                                <th>สินค้า</th>
                                <th>ประเภทสินค้า</th>
                                <th>ขนาดความยาว</th>
                                <th>จำนวนตารางเมตร</th>

                                <th>ราคาต่อหน่วย</th>
                                <th>ราคาเพิ่มเติมต่อหน่วย</th>
                                <th>รวมเป็นเงิน</th>
                                <th>หมายเหตุ</th>

                                <th>จำนวนที่สั่ง</th>
                                <th>น้ำหนัก</th>
                                <th>ส่งสินค้า</th>
                                <th>สินค้าค้างส่ง</th>
                                <th>คืนสินค้า</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['OrderItem'] as $key => $item)
                            <tr class="text-center">
                                <td>{{$key + 1}}</td>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->product_type_name }}</td>
                                <td>{{ $item->product_size_name_text }}</td>
                                <td>{{ $item->total_size_text}}</td>

                                <td>{{ number_format($item->price,2) }}</td>
                                <td>{{ number_format($item->addition,2) }}</td>
                                <td>{{ number_format($item->total_price,2) }}</td>
                                <td>{{ $item->remark }}</td>

                                <td>{{ number_format($item->qty) }}</td>
                                <td>{{ $item->product_weight_text }}</td>
                                <td><span class="c-0095dd">{{ number_format($data['order_item'][$item->order_item_id]['sum_order_delivery_item']) }}</span></td>
                                <td><span class="c-ff871e">{{ number_format($data['order_item'][$item->order_item_id]['qty'] - $data['order_item'][$item->order_item_id]['sum_order_delivery_item'] + $data['order_item'][$item->order_item_id]['return']) }}</span></td>
                                <td>{{ number_format($data['order_item'][$item->order_item_id]['return']) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-7 px-4">
            </div>
            <div class="col-5 px-4">
                <div class="row border p-2">
                    <div class="col-12">
                        <h1 class="title-1">จำนวนเงิน</h1>
                    </div>
                    <div class="col-12">
                        <span class="label-blue">รวมเงิน :</span>
                        <span class="float-right">{{ number_format($data['Order']->price_all,2) }}</span>
                    </div>
                    <div class="col-12">
                        <span class="label-blue">ภาษีมูลค่าเพิ่ม {{sprintf('%g',$data['Order']->vat_no)}}% :</span>
                        <span class="float-right">{{ number_format($data['Order']->vat,2) }}</span>
                    </div>
                    <div class="col-12">
                        <span class="label-blue">จำนวนเงินคืนสินค้า :</span>
                        <span class="float-right">{{ number_format($data['OrderSumPriceReturn'],2) }}</span>
                    </div>
                    <div class="col-12">
                        <span class="label-blue">จำนวนเงินรวมทั้งสิ้น :</span>
                        <span class="float-right">{{ number_format($data['Order']->grand_total-$data['OrderSumPriceReturn'],2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('pages.orders.printcyc')
@include('pages.orders.cyc')
@endsection

@section('js')
<script>
 $('.print-only').hide();
 $('#printCyc').hide()
let btn_printJSOrder = () =>{
        $('.print-only').show();
        var printContents = document.getElementById('printJSOrder-form').innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
        $('.print-only').hide();
    }
let btn_printJS = () =>{
    $('#printCyc').hide();
    if($('#price_hide').is(":checked")){
        $('.hid_price').hide();
    }else{
        $('.hid_price').show();
    }


        var printContents = document.getElementById('printCyc').innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
        // $('label').show();
    }
</script>
@endsection
