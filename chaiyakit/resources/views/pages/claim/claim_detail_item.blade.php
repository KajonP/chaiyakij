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
                        <li class="breadcrumb-item">
                            <a
                                href="{{ route('claim.list.claim_return',['id' => $data['Order']->order_id ] ) }}">รายการคืน/เคลมสินค้า</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">รายละเอียดสินค้า การคืน/เคลม</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-6 align-self-end">
                <h2></h2>
            </div>
            <div class="col-12 col-md-6 text-right">
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="form-group col-md-3">
                <label for="" class="label-blue">เลขที่รายการ : </label>
                <span>{{ $data['OrderDelivery']->order_delivery_number }}</span>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-3">
                <label for="" class="label-blue">ร้านค้า :</label>
                <span>{{ $data['Order']->name_merchant }}</span>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-3">
                <label for="" class="label-blue">วันที่สั่งซื้อ :</label>
                <span>{{ formatDateThat($data['Order']->created_date) }}</span>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12 form-content mb-3 table-responsive">
                <table class="table table-striped dt-responsive nowrap" style="width:100%" id="table">
                    <thead>
                        <tr class="text-center">
                            <th>#</th>
                            <th>สินค้า</th>
                            <th>ประเภทสินค้า</th>
                            <th>ขนาด</th>
                            <th>ราคาต่อหน่วย</th>
                            <th>จำนวน</th>
                            <th>หมายเหตุ</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($data['OrderDeliveryItem'] as $key => $item)
                          @if (count($data['OrderDeliveryItem']) > 0)
                            <tr class="text-center">
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->product_type_name }}</td>
                                <td>{{ number_format($item->product_size_name,2) .' '. config('sizeunit')[$item->product_size_unit] }} </td>
                                <td>{{ number_format($item->price,2) }}</td>
                                <td>{{ number_format($item->qty) }}</td>
                                <td>{{ $item->remark }}</td>
                            </tr>
                          @else
                            <tr class="text-center">
                                <td> - </td>
                                <td> - </td>
                                <td> - </td>
                                <td> - </td>
                                <td> - </td>
                                <td> - </td>
                                <td> - </td>
                            </tr>
                          @endif
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
