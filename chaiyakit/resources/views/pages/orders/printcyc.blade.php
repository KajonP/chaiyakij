<style>
    /* p.ex1 {
        border: 1px solid red; 
        padding-top: 100%;
    } */
</style>

<form method="post" action="#" id="printJS-form" class="d-none" style="">
    <?php 
        $page = count($data['OrderItem']) / 10;
        $page = ceil($page);
        $numitem1 = 0;
        $numitem2 = 10;

        function utf8_strlen($s) {
            $c = strlen($s); $l = 0;
            for ($i = 0; $i < $c; ++$i)
            if ((ord($s[$i]) & 0xC0) != 0x80) ++$l;
            return $l;
        }

        for ($c = 1; $c <= $page; $c++) {
    ?>
    @if ($c <= 1)
        <br><br><br><br><br>
    @else
        <br><br><br>
    @endif
    
    <p style="color: #000; margin-left: 20px; font-size: 16px !important;">
        &nbsp;&nbsp;&nbsp;ใบสั่งซื้อเลขที่ {{ $data['Order']->order_number }}
    </p>
  
    <p style="color: #000; font-size: 20px !important; margin-left: 165px;">
        {{ isset($data['Order']->department_name) ? $data['Order']->department_name : $data['Order']->name_merchant }}
    </p>
    <p style="color: #000; font-size: 20px !important; margin-left: 730px; margin-top: -45px;">
        {{ $data['Order']->getOrderDate() }}
    </p>
    
    <p style="color: #000; font-size: 20px !important; margin-left: 165px; padding-top: -30%;">
    @if ($data['Order']->status_departmen == "00")
        {{$data['Order']->address != "" ? $data['Order']->address : "-"}}
    @else
        {{ $data['Order']->address_department != ""  ? $data['Order']->address_department : "-" }}
    @endif
        {{-- {{ $data['Order']->status_departmen == "00" ? $data['Order']->address != "" ? $data['Order']->address : "-" : $data['Order']->address_department != ""  ? $data['Order']->address_department : "-" }} --}}
    </p>
    <p style="color: #000; font-size: 20px !important; margin-left: 730px; margin-top: -50px;">
    {{ $data['Order']->status_departmen == "00" ? $data['Order']->phone_number : $data['Order']->phone_department }} &nbsp;
    {{-- @if ($data['Order']->status_departmen == "00")
        {{ $data['Order']->phone_number == "" ? "-"  : $data['Order']->phone_number ? $data['Order']->phone_number:"-" }}
    @else
        {{ $data['Order']->status_departmen == "" ? "-"  : $data['Order']->status_departmen ? $data['Order']->status_departmen : "-" }}
    @endif --}}
    </p>
    @if ($c <= 1)
        <br><br>
        <table style="width: 100%; margin-top: -8px;" >
    @else
        <br>
        <br>
        <br>
        <table style="width: 100%; margin-top: 0px;" >
    @endif
        <?php
            $ro=0;
            $top=25;
            $da_or = 0;
        ?>
        @foreach ($data['OrderItem'] as $key => $item)
            <?php
                $ro = $key;
                
                if ($key > 0 && $key < 3) {
                    $top = $top - 11;
                }
                if ($key >= 3 && $key < 7) {
                    $top = $top - 7;
                }
                if ($key >= 6) {
                    $top = $top - 2;
                }
            ?>

            @if ($key >= $numitem1 && $key < $numitem2)
                <tr style="">
                    <td class="text-center" style="width: 17%;">
                        <p style="color: #000; font-size: 16px !important; line-height: 1.5px !important;  margin-top: {{($top)}}px !important; ">{{ number_format($item->qty) }}</p>
                    </td>
                    <td class="text-center" style="width: 20%;">
                        @php
                        $str = $item->product_name." ".$item->product_size_name_text;
                        $total_text = utf8_strlen($str);
                        $name_pro = iconv_substr($item->product_name, 0, 3);
                        @endphp
                        @if($name_pro == 'เสา')
                            @if($total_text <= 22)
                                <p style="color: #000; font-size: 16px !important; line-height: 1.5px !important; margin-top: {{$top}}px !important;">{{$item->product_name." ".$item->product_type_name }}</p>
                            @else
                                <p style="color: #000; font-size: 13px !important; line-height: 1.5px !important;margin-top: {{$top}}px !important;">{{$item->product_name." ".$item->product_type_name }}</p>
                            @endif
                        @else
                            @if($total_text <= 22)
                                <p style="color: #000; font-size: 16px !important; line-height: 1.5px !important; margin-top: {{$top}}px !important;">{{$item->product_name." ".$item->product_size_name_text }}</p>
                            @else
                                <p style="color: #000; font-size: 13px !important; line-height: 1.5px !important; margin-top: {{$top}}px !important;">{{$item->product_name." ".$item->product_size_name_text }}</p>
                            @endif
                        @endif
                    </td>
                    <td class="text-center" style="width: 15.6%;">
                        <p style="color: #000; font-size: 16px !important; line-height: 1.5px !important; margin-top: {{$top}}px !important;">{{ $item->total_size_text }}</p>
                    </td>
                    <td class="text-center" style="width: 13%;">
                        <p style="color: #000; font-size: 16px !important; line-height: 1.5px !important; margin-top: {{$top}}px !important;" class="price_hide">{{ number_format($item->price+$item->addition,2) }}</p>
                    </td>
                    <td align="right" style="width: 17.6%;">
                        <p style="color: #000; font-size: 16px !important; line-height: 1.5px !important; margin-top: {{$top}}px !important;" class="price_hide">{{ number_format($item->total_price,2) }}</p>
                    </td>
                    {{-- @if($name_pro == 'เสา')
                        <td class="text-center">
                            <p style="color: #000; font-size: 16px !important; line-height: 1.5px !important;"></p>
                        </td>
                    @else --}}
                    <td class="text-center">
                        <p style="color: #000; font-size: 16px !important; line-height: 1.5px !important; margin-top: {{$top}}px !important;">{{ $item->remark }}</p>
                    </td>
                    {{-- @endif --}}
                </tr>
                <?php
                    $da_or++;
                ?>
            @endif
            
        @endforeach
        <?php
            $numitem1 = $numitem1+10;
            $numitem2 = $numitem2+10;

            $order = $da_or;
            $order_totle = 10 - $order;
        ?>
        @for ($i = 1; $i <= $order_totle; $i++)
             <tr style="">
                <td class="text-center" style="width: 17%;"></td>
                <td class="text-center" style="width: 20%;"></td>
                <td class="text-center" style="width: 15.6%;"></td>
                <td class="text-center" style="width: 13%;"></td>
                <td align="right" style="width: 17.6%;"></td>
                <td class="text-center"></td>
            </tr>
        @endfor
    </table>
    
    @if ($c > 1)
        <br>
        <br>
        @if ($data['Order']->status_departmen == "00")
            <p style="height: 20px;"></p>
        @endif
        
    @endif
    
    <table style="width: 100%">
        <tr class="price_hide">
            <td rowspan="6" colspan="2" valign="top" style="column-width:420px; word-wrap: break-word;">
                <p style="color: #000; font-size: 18px !important; margin-top: -35px; margin-left: 25px;">
                    หมายเหตุ : {{$data['Order']->noteorder}} &nbsp;
                </p>
            </td>
            <td colspan="" style="line-height: 37px; width: 20%; " align="right" >
                <p style="color: #000; font-size: 18px !important; margin-top: -51px; " class="price_hide">ราคาสุทธิ</p>
            </td>
            <td class="text-right" style=" width: 20%;">
                <p style="color: #000; font-size: 18px !important; margin-top: -51px; " class="price_hide">
                    {{ number_format($data['Order']->price_all,2) }}</p>
            </td>
            <td class="" style="width: 17.3%;" ></td>
        </tr>
        {{-- //// --}}
        <tr class="price_hide2">
            <td valign="top" style="column-width:420px; word-wrap: break-word;">
                <p style="color: #000; font-size: 18px !important; margin-top: -35px; margin-left: 25px;">
                    หมายเหตุ : {{$data['Order']->noteorder}} &nbsp;
                </p>
            </td>
        </tr>
        {{-- //// --}}
        <tr class="price_hide">
            <td colspan="" style="line-height: 37px;" align="right">
                <p style="color: #000; margin-top: -55px; font-size: 18px !important;">ภาษีมูลค่าเพิ่ม {{sprintf('%g',$data['Order']->vat_no)}}%
                </p>
            </td>
            <td class="text-right" style="">
                <p style="color: #000; margin-top: -55px; font-size: 18px !important;"> {{ number_format($data['Order']->vat,2) }} </p>
            </td>
            <td class="" style=" "></td>
        </tr>
        <tr class="price_hide">
            <td colspan="" style="line-height: 37px; " align="right">
                <p style="color: #000; margin-top: -55px; font-size: 18px !important;">จำนวนเงินคืนสินค้า</p>
            </td>
            <td class="text-right" style="">
                <p style="color: #000; margin-top: -55px; font-size: 18px !important;">{{ number_format($data['OrderSumPriceReturn'],2) }}</p>
            </td>
            <td class="" style=" "></td>
        </tr>
        <tr class="price_hide">
            <td colspan=""></td>
            <td class=""></td>
            <td class="" style=" "></td>
        </tr>
        <tr class="price_hide">
            <td colspan=""></td>
            <td class=""></td>
            <td class="" style=" "></td>
        </tr>
        <tr class="price_hide">
            <td colspan="" style="" align="right"></td>
            <td class="text-right" style="width: 15%; ">
                <p style="color: #000; margin-top: -68px;  font-size: 18px !important ">{{ number_format($data['Order']->grand_total-$data['OrderSumPriceReturn'],2) }}</p>
            </td>
            <td class="" style=""></td>
        </tr>
    </table>

    @if ($data['Order']->status_departmen == "01")
        <table class="price_hide2" >
            <tr class="price_hide2">
                <td colspan="" style="width: 20%;" align="right"></td>
                <td class="text-right" style="width: 15%; "></td>
                <td class="" style="width: 17.3%; "></td>
            </tr>
            <tr class="price_hide2">
                <td colspan="" style="width: 20%" align="right"></td>
                <td class="text-right" style="width: 15%;"></td>
                <td class="" style="width: 17.3%; "></td>
            </tr>
        </table>
    @else
        <table class="price_hide2" height="180px">
            <tr class="price_hide2">
                <td colspan="" style="width: 20%;" align="right"></td>
                <td class="text-right" style="width: 15%; "></td>
                <td class="" style="width: 17.3%; "></td>
            </tr>
            <tr class="price_hide2">
                <td colspan="" style="width: 20%" align="right"></td>
                <td class="text-right" style="width: 15%;"></td>
                <td class="" style="width: 17.3%; "></td>
            </tr>
            <tr class="price_hide2">
                <td colspan=""></td>
                <td class=""></td>
                <td class="" style="width: 17.3%; "></td>
            </tr>
            <tr class="price_hide2">
                <td colspan=""></td>
                <td class=""></td>
                <td class="" style="width: 17.3%; "></td>
            </tr>
            <tr class="price_hide2">
                <td colspan="" style="width: 20%" align="right"></td>
                <td class="text-right" style="width: 15%; ">
                    <p style="color: #000; margin-top: 0px; "></p>
                </td>
                <td class="" style="width: 17.3%; "></td>
            </tr>
        </table>
    @endif
    
 

    <table style="width: 100%">
        <td class="" style="width: 10%; "></td>
        <td style="line-height: 37px; width: 20%;">
            <p style="color: #000; margin-top: -25px; margin-left: -20%; font-size: 22px !important ">
                {{ $data['Order']->status_departmen == "00" ? "( ".$data['Order']->name_merchant." )" : '' }}
            </p>
        </td>
        <td class="" style="width: 10%; "></td>
        <td class="price_hide" style="width: 20%; ">
            <p style="color: #000; margin-top: -70px; margin-left: -25%; font-size: 22px !important ">
                {{ $data['Order']->status_departmen == "01" ? $data['Order']->name_merchant : '' }}
            </p>
        </td>

        

        @if ($c > 1)
            <td class="price_hide2" style="width: 20%; ">
                <p style="color: #000; padding-top: 0px; margin-left: -25%; font-size: 22px !important ">
                    {{ $data['Order']->status_departmen == "01" ? $data['Order']->name_merchant : '' }}
                </p>
            </td>
        @else
            <td class="price_hide2" style="width: 20%; ">
                <p style="color: #000; padding-top: 0px; margin-left: -25%; font-size: 22px !important ">
                    {{ $data['Order']->status_departmen == "01" ? $data['Order']->name_merchant : '' }}
                </p>
            </td>
        @endif

    </table>
        


    <div style="page-break-after: always"></div>
    <?php } ?>
</form>

