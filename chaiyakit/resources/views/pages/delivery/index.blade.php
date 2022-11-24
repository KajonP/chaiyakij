@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-12 col-md-6 text-center mt-5 align-self-end">
        <h2>การจัดส่งสินค้า</h2>
    </div>
    <div class="col-md-12 mb-5">
        <div class="row justify-content-center mt-5">
            <a href="{{ route('delivery.calendar') }}" class="col-5 col-md-2 card mr-2 p-4 align-items-center">
                <img class="my-3" src="{{ asset('assets/image/ico_menu07_01.png') }}" alt="" width="80px">
                <span>ปฎิทินการจัดส่งสินค้า</span>
            </a>
            <a href="{{ route('delivery.list') }}" class="col-5 col-md-2 card ml-2 p-4 align-items-center">
                <img class="my-3" src="{{ asset('assets/image/ico_menu02_01.png') }}" alt="" width="80px">
                <span>การจัดส่งสินค้า</span>
            </a>
        </div>
    </div>
</div>

@endsection
