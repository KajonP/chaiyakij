<style>
    @media print {
@page {size:portrait;max-height:100%; max-width:100%}

#printCyc{width:100%;
/* height:100%;    */
 }
}
</style>
<div id="printCyc">

    <?php 
       $OrderItem = array_chunk($data['OrderItem'],10);

    ?>
    @foreach ($OrderItem as $key => $item)
    <div style="margin-top: 35mm;">
        <div style="color: #000; font-size: 14px !important; margin-left: 25px;">ใบสั่งซื้อเลขที่ {{ $data['Order']->order_number }}</div>
    </div>
    <div style="margin-top: 6mm;">
        <span style="color: #000; margin-left: 160px;font-size: 19px !important;font-weight: 700;">{{ isset($data['Order']->department_name) ? $data['Order']->department_name : $data['Order']->name_merchant }}</span>
        <span style="color: #000; font-size: 18px !important;float: right;margin-right: 8rem;" >{{ $data['Order']->getOrderDate() }}</span>
    </div>
    <div style="margin-top: 6mm;" >
        <span style="color: #000; margin-left: 150px;font-size: 18px !important;">{{ $data['Order']->status_departmen == "00" ? ($data['Order']->address == '' ? '-': $data['Order']->address) : ($data['Order']->address == '' ? '-' : $data['Order']->address_department) }}</span>
        <span style="color: #000; font-size: 18px !important;float: right;margin-right: 8rem;" >{{ $data['Order']->status_departmen == "00" ? $data['Order']->phone_number : $data['Order']->phone_department }}</span>
    </div>

    <div style="margin-top: 20mm;height:71mm" >
        @foreach ($item as $item_key => $item_item)

        <div style="color: #000;bord; font-size: 16px !important;line-height: 9.5mm;">
            <span style="width: 10.7rem;" class="text-center">{{number_format($item_item->qty) }}</span>
            @php
                $str = $item_item->product_name." ".$item_item->product_size_name_text;
                $total_text = utf8_strlen($str);
                $name_pro = iconv_substr($item_item->product_name, 0, 3);
            @endphp
{{-- {{//(($total_text <= 22) ? '' :'font-size: 13px;')}} --}}
            <span style="width: 15.7rem;" class="text-center">{{ (($name_pro == 'เสา') ?  $item_item->product_name." ".$item_item->product_type_name : $item_item->product_name." ".$item_item->product_size_name_text) }}</span>
            <span style="width: 5rem;" class="text-center">{{($item_item->formula == '01'? round((($item_item->total_size*1)*($item_item->qty*1)),2).' ตร.ม.' : $item_item->total_size_text)}}</span>
            {{-- {{ //$item_item->total_size_text}} --}}
            {{-- {{//(($item_item->total_size*1)*($item_item->qty*1))}} $sizeunit--}}
            {{-- {{// number_format($item_item->price+$item_item->addition,2) }} --}}
            <span style="width: 9rem;" class="text-center "><span class="hid_price">{{number_format($item_item->price+$item_item->addition,2)}}</span></span>
            <span style="width: 11.2rem;" class="text-right " ><span class="hid_price">{{  number_format($item_item->total_price,2) }}</span></span>
            <span style="margin-left:3rem" class="text-left">@if ($item_item->remark == "" )  &nbsp; @else{{$item_item->remark}}@endif</span>
        </div>
        @endforeach
    </div>
        <div style="color: #000;bord; font-size: 16px !important;height:35.5mm;margin-top: 25.56mm;">
            <div style="margin-left: 28px;">
                <span style="width: 31.9rem;" class="text-left"> หมายเหตุ : {{$data['Order']->noteorder}} &nbsp;</span>
                <span style="width: 7rem;" class="text-right hid_price">ราคาสุทธิ</span>
                <span style="width: 11.4rem;" class="text-right hid_price"> {{ number_format($data['Order']->price_all,2) }}</span>
            </div>
            <div style="line-height: 11mm;">
                <span style="width: 40.9rem;" class="text-right hid_price">ภาษีมูลค่าเพิ่ม {{sprintf('%g',$data['Order']->vat_no)}}%</span>
                <span style="width: 11.4rem;" class="text-right hid_price"> {{ number_format($data['Order']->vat,2) }} </span>
            </div>
            <div style="line-height: 8mm;">
                <span style="width: 40.9rem;" class="text-right hid_price">จำนวนเงินคืนสินค้า</span>
                <span style="width: 11.4rem;" class="text-right hid_price"> {{ number_format($data['OrderSumPriceReturn'],2) }}</span>
            </div>
        </div>
        <div style="color: #000;bord; font-size: 16px !important;height:{{ $data['Order']->status_departmen == "00" ?  "11mm" :"11mm"}};margin-top: 13mm;">
            <span style="width: 52.5rem;" class="text-right hid_price"> {{ number_format($data['Order']->grand_total-$data['OrderSumPriceReturn'],2) }}</span>
        </div>
        <div style="color: #000;bord; font-size: 16px !important;margin-left: {{ $data['Order']->status_departmen == "00" ? "28px" : "500px"}};{{ $data['Order']->status_departmen == "00" ? "margin-top: 9mm;" : ""}}">
            <span style="width: 22rem;" class="text-center"> {{ $data['Order']->status_departmen == "00" ? "( ".$data['Order']->name_merchant." )" : $data['Order']->name_merchant}}</span>
        </div>
        
   
    <div style="page-break-after: always"></div>
    @endforeach

</div>