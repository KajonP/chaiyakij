@extends('layouts.app')

@section('content')
<div class="form-main" id="page-delivery-detail">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ url('master-data/itemmerchant') }}">จัดการข้อมูลขนส่งรายวันแยกเป็นร้านค้า</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{$merchant->name_merchant}}/{{$datefrom}}</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-12 align-self-end">
                <h2>สรุปการจัดส่งสินค้า : ร้านค้า {{$merchant->name_merchant}} : วันที่จัดส่ง {{$datefrom}}</h2>
            </div>
        </div>
        <hr>
        {{-- <div class="row">
            <div class="form-group col-md-3">
                <label for="" class="label-blue">วันที่จัดส่ง :</label>
                <br>
                <span>{{$datefrom}}</span>
            </div>
        </div> --}}

        <div class="row style2" style="background: #f4f4f5;padding-top: 1rem;">
            <div class="form-group col-md-3">
                <label for="">จำนวนสินค้าที่สั่งทั้งหมด :</label>
                <br>
                <span class="c-0095dd" id="qty_all"></span>
            </div>
            <div class="form-group col-md-3">
                <label for="">วันที่จัดส่ง :</label>
                <br>
                <span class="c-000000">{{$datefrom}}</span>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 form-content mb-3 table-responsive">
                    <table class="table table-striped dt-responsive nowrap" style="width:100%" id="table">
                        <thead>
                            <tr class="text-center">
                                <th>
                                    #
                                </th>
                                <th>สินค้า</th>
                                <th>ประเภทสินค้า</th>
                                <th>ขนาดความยาว</th>
                                <th>จำนวนตารางเมตร</th>


                                {{-- <th>น้ำหนักรวม</th> --}}

                                <th>สินค้าที่ส่ง</th>
                                {{-- <th>น้ำหนักจัดส่ง</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($Orders as $key => $item)
                            <tr class="text-center">
                                <td>{{$key+1}}</td>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->product_type_name }}</td>
                                <td>{{ ($item->formula == '01' ? '0.35x'.number_format($item->product_size_name) : number_format($item->product_size_name)) }}</td>
                                <td>{{ number_format($item->product_size_name_sum,2) }}</td>
                                {{-- <td>{{ $item->product_weight_text }}</td> --}}
                                <td><span class="item_qty">{{ number_format($item->qty) }}</span></td>
                                {{-- <td>{{ $item->product_weight_text }}</td> --}}
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                    <hr>
                    <div class="row">
                        <div class="col-12 text-center mt-5">

                        </div>
                    </div>

            </div>
        </div>
    </div>
</div>
@include('pages.loading')
@endsection

@section('js')
<script>
    cuntitem_qty()
    function cuntitem_qty()
    {
        var total =0
        var someArray = $('.item_qty')
        for (var i = 0; i < someArray.length; i++) {
            var str =  $(someArray[i]).html();
            var res = str.replace(",", "");
            var res1 = res.replace(",", "");
            var res2 = res1.replace(",", "");
            var res3 = res2.replace(",", "");
            var res4 = res3.replace(",", "");
            var res5 = res4.replace(",", "");
            var res6 = res5.replace(",", "");
            var res7 = res6.replace(",", "");
            total += Number(res7);
        }
        addCommasTotalAll(total)
    }
    function addCommasTotalAll(nStr)
    {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        // x2 = x.length > 1 ? '.' + x[1] : '.0';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        var total = x1;
        $('#qty_all').html(total)
    }
</script>
@endsection
