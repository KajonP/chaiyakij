@extends('layouts.app')
@section('content')

<div class="row justify-content-center">
    <div class="col-12 col-md-6 text-center mt-5">
        <h2 style="font-weight: bold;">เมนูหลักไชยกิจคอนกรีต</h2>
    </div>
    <div class="col-md-12 ">
        <div class="row mt-5">
            <div onclick="window.location.href='{{ route('orders') }}'" data-toggle="collapse" aria-expanded="false" class="col-2 card ml-auto mr-auto mb-5 mt-1" rel="tooltip"
                data-placement="right" title="จัดการใบสั่งซื้อสินค้า">
            <img class="my-4 rounded mx-auto d-block" src="{{ asset('assets/image/ico_menu01_01.png') }}" alt="" width="70px">
            <span class="ml-auto mr-auto mb-5 mt-1 font-weight-bold">จัดการใบสั่งซื้อ</span>
            </div>
            @hasanyrole('super-admin|manager|admin')
            <div onclick="window.location.href='{{ route('delivery') }}'" data-toggle="collapse" aria-expanded="false" class="col-md-2 card ml-auto mr-auto mb-5 mt-1" rel="tooltip"
                data-placement="right" title="การจัดส่งสินค้า">
            <img class="my-4 rounded mx-auto d-block" src="{{ asset('assets/image/ico_menu02_01.png') }}" alt="" width="70px">
            <span class="ml-auto mr-auto mb-5 mt-1 font-weight-bold">จัดส่งสินค้า</span>
            </div>

            <div onclick="window.location.href='{{ route('claim') }}'" data-toggle="collapse" aria-expanded="false" class="col-md-2 card ml-auto mr-auto mb-5 mt-1" rel="tooltip"
                data-placement="right" title="คืน / เคลม สินค้า">
            <img class="my-4 rounded mx-auto d-block" src="{{ asset('assets/image/ico_menu03_01.png') }}" alt="" width="70px">
            <span class="ml-auto mr-auto mb-5 mt-1 font-weight-bold">คืน/เคลม สินค้า</span>
            </div>

            <div href="#data_management" data-toggle="collapse" class="col-md-2 card text-center ml-auto mr-auto mb-5 mt-1 " rel="tooltip"
                data-placement="right" title="จัดการข้อมูล">
            <img class="my-4 rounded mx-auto d-block" src="{{ asset('assets/image/ico_menu04_01.png') }}" alt="" width="70px">
            <span class="ml-auto mr-auto mb-5 mt-1 font-weight-bold">จัดการข้อมูล</span>
            </div>
            @endhasanyrole
        </div>
    </div>
</div>

@endsection