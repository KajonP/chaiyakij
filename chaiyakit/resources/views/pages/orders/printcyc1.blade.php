<style>
    p {
        margin-top: unset !important;
        margin-bottom: unset !important;
    }
</style>

<form method="post" action="#" id="printJS-form1" class="d-none" style="">
    <?php 
        $page = count($data['OrderItem']) / 10;
        $page = ceil($page);
        $numitem1 = 0;
        $numitem2 = 10;

        for ($c = 1; $c <= $page; $c++) {
    ?>
    <br><br><br>
    @if ($c > 1)
        <table style="width: 100%  margin-top: 10mm;" >
    @else
        <table style="width: 100% " >
    @endif
        {{-- <table border="1" style="width: 100%"> --}}
        <tr>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td colspan="6" style="color: #000; margin-right: 100px; font-size: 16px !important">&nbsp;&nbsp;&nbsp;ใบสั่งซื้อเลขที่ {{ $data['Order']->order_number }}</td>
        </tr>
        <tr>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td colspan="1" style="width: 155px; font-size: 18px !important"><label>ชื่อลูกค้า</label></td>
            <td colspan="4">
                <p style="margin-top: -43px; color: #000; font-size: 20px !important">
                    {{ isset($data['Order']->department_name) ? $data['Order']->department_name : $data['Order']->name_merchant }}
                </p>
            </td>

            <td  style="width: 350px; font-size: 20px !important">
                <p style="color: #000; margin-top: -43px; margin-left: 60px;">{{ $data['Order']->getOrderDate() }}</p>
            </td>
        </tr>
        <tr>
            <td colspan="1"><label>ที่อยู่</label></td>
            <td colspan="4">
                <p style="color: #000; margin-top: -30px; font-size: 20px !important">{{  $data['Order']->status_departmen == "00" ? $data['Order']->address : $data['Order']->address_department }}</p>
            </td>
            <td >
                <p style="color: #000; margin-top: -22px; margin-left: 60px; font-size: 20px !important">{{   $data['Order']->status_departmen == "00" ? $data['Order']->phone_number : $data['Order']->phone_department }}</p>
            </td>
        </tr>
        <tr>
            <td class="text-center"><label>จำนวนแผ่น</label></td>
            <td class="text-center"><label>ขนาดความยาว</label></td>
            <td class="text-center"><label>จำนวนตารางเมตร</label></td>
            <td class="text-center"><label>ตารางเมตรละ</label></td>
            <td class="text-center"><label>จำนวนเงิน</label></td>
            <td class="text-center"><label>หมายเหตุ</label></td>
        </tr>
        <tr>
            <td colspan="6">
              <table style="width: 100%; " >
    {{-- <table border="1" style="width: 100%"> --}}
                <?php
                  $ro=0;
                  $top=27;
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
                            <td class="text-center" style="width: 16%;">
                                <p style="color: #000; font-size: 16px !important; line-height: 1.5px !important;  margin-top: {{$top}}px !important; ">{{ number_format($item->qty) }}</p>
                            </td>
                            <td class="text-center" style="width: 20%;">
                                @php
                                $total_text = $item->product_name." ".$item->product_size_name_text;
                                $name_pro = iconv_substr($item->product_name, 0, 3);
                                @endphp
                                @if($name_pro == 'เสา')
                                    @if(strlen($total_text) <= 39)
                                        <p style="color: #000; font-size: 16px !important; line-height: 1.5px !important; margin-top: {{$top}}px !important;">{{$item->product_name." ".$item->product_type_name}}</p>
                                    @else
                                        <p style="color: #000; font-size: 12px !important; line-height: 1.5px !important;margin-top: {{$top}}px !important;">{{$item->product_name." ".$item->product_type_name}}</p>
                                    @endif
                                @else
                                    @if(strlen($total_text) <= 39)
                                        <p style="color: #000; font-size: 16px !important; line-height: 1.5px !important; margin-top: {{$top}}px !important;">{{$item->product_name." ".$item->product_size_name_text}}</p>
                                    @else
                                        <p style="color: #000; font-size: 12px !important; line-height: 1.5px !important; margin-top: {{$top}}px !important;">{{$item->product_name." ".$item->product_size_name_text}}</p>
                                    @endif
                                @endif
                            </td>
                            <td class="text-center" style="width: 16.6%;">
                                <p style="color: #000; font-size: 16px !important; line-height: 1.5px !important; margin-top: {{$top}}px !important;">{{ $item->total_size_text }}</p>
                            </td>
                            <td class="text-center" style="width: 13.4%;">
                                <p style="color: #000; font-size: 16px !important; line-height: 1.5px !important; margin-top: {{$top}}px !important;" class="price_hide">{{ number_format($item->price+$item->addition,2) }}</p>
                            </td>
                            <td align="right" style="width: 17.6%;">
                                <p style="color: #000; font-size: 16px !important; line-height: 1.5px !important; margin-top: {{$top}}px !important;" class="price_hide">{{ number_format($item->total_price,2) }}</p>
                            </td>
                            @if($name_pro == 'เสา')
                                <td class="text-center">
                                    <p style="color: #000; font-size: 16px !important; line-height: 1.5px !important;"></p>
                                </td>
                            @else
                                <td class="text-center">
                                    <p style="color: #000; font-size: 16px !important; line-height: 1.5px !important; margin-top: {{$top}}px !important;">{{ $item->remark }}</p>
                                </td>
                            @endif
                        </tr>
                        <?php
                            $da_or++;
                        ?>
                        
                    @endif
                    
                @endforeach
                <?php
                    $numitem1 = $numitem1+10;
                    $numitem2 = $numitem2+10;
                ?>
              </table>
            </td>
        </tr>
        <?php
          $order = $da_or;
          $order_totle = 10 - $order;

        ?>
        @for ($i = 1; $i <= $order_totle; $i++)
        <tr>
            <td colspan="6">
              <p style="line-height: 5px !important"></p>
            </td>
        </tr>
        @endfor

        <tr class="">
          @if($ro <= 5)
          <table style="width: 100% " border="1">
            <tr>
                <td rowspan="6" colspan="2" valign="top" style="line-height: 37px; width: 45%">
                    <p style="color: #000; font-size: 18px !important; margin-top: -35px; ">หมายเหตุ : {{$data['Order']->noteorder}}</p>
                </td>
                <td colspan="" style="line-height: 37px; width: 10%" align="right" >
                    <p style="color: #000; font-size: 18px !important; margin-top: -50px; " class="price_hide">ราคาสุทธิ</p>
                </td>
                <td class="text-right" style="width: 18.5%; ">
                    <p style="color: #000; font-size: 18px !important; margin-top: -50px; " class="price_hide">
                        {{ number_format($data['Order']->price_all,2) }}</p>
                </td>
                <td class="" style="width: 25%; " ></td>
            </tr>
            <tr class="price_hide">
                <td colspan="" style="line-height: 37px; width: 18%" align="right">
                    <p style="color: #000; margin-top: -45px; font-size: 18px !important; ">ภาษีมูลค่าเพิ่ม {{sprintf('%g',$data['Order']->vat_no)}}%
                    </p>
                </td>
                <td class="text-right" style="width: 15%;">
                    <p style="color: #000; margin-top: -45px; font-size: 18px !important; "> {{ number_format($data['Order']->vat,2) }} </p>
                </td>
                <td class="" style="width: 18%; "></td>
            </tr>
            <tr class="price_hide">
                <td colspan="" style="line-height: 37px; width: 18%" align="right">
                    <p style="color: #000; margin-top: -52px; font-size: 18px !important; ">จำนวนเงินคืนสินค้า</p>
                </td>
                <td class="text-right" style="width: 15%;">
                    <p style="color: #000; margin-top: -52px; font-size: 18px !important; ">{{ number_format($data['OrderSumPriceReturn'],2) }}</p>
                </td>
                <td class="" style="width: 18%; "></td>
            </tr>
            <tr class="price_hide">
                <td colspan=""></td>
                <td class=""></td>
                <td class="" style="width: 18%; "></td>
            </tr>
            <tr class="price_hide">
                <td colspan=""></td>
                <td class=""></td>
                <td class="" style="width: 18%; "></td>
            </tr>
            <tr class="price_hide">
                <td colspan="" style="width: 18%" align="right"></td>
                <td class="text-right" style="width: 15%; ">
                    <p style="color: #000; margin-top: -65px;  font-size: 18px !important ">{{ number_format($data['Order']->grand_total-$data['OrderSumPriceReturn'],2) }}</p>
                </td>
                <td class="" style="width: 18%; "></td>
            </tr>
          </table>

          <table class="price_hide2" height="205px">
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

          @else

          <table style="width: 100%" >
            <tr>
                <td rowspan="6" colspan="2" valign="top" style="line-height: 37px; width: 46.5%">
                    <p style="color: #000; font-size: 18px !important; margin-top: -35px; margin-left: 40px; ">หมายเหตุ : {{$data['Order']->noteorder}}</p>
                </td>
                <td colspan="" style="line-height: 37px; width: 10%" align="right" >
                    <p style="color: #000; font-size: 18px !important; margin-top: -50px; " class="price_hide">ราคาสุทธิ</p>
                </td>
                <td class="text-right" style="width: 18.5%; ">
                    <p style="color: #000; font-size: 18px !important; margin-top: -50px; " class="price_hide">
                        {{ number_format($data['Order']->price_all,2) }}</p>
                </td>
                <td class="" style="width: 25%; " ></td>
            </tr>
            <tr class="price_hide">
                <td colspan="" style="line-height: 37px; width: 18%" align="right">
                    <p style="color: #000; margin-top: -50px; font-size: 18px !important;">ภาษีมูลค่าเพิ่ม {{sprintf('%g',$data['Order']->vat_no)}}%
                    </p>
                </td>
                <td class="text-right" style="width: 15%;">
                    <p style="color: #000; margin-top: -50px; font-size: 18px !important;"> {{ number_format($data['Order']->vat,2) }} </p>
                </td>
                <td class="" style="width: 18%; "></td>
            </tr>
            <tr class="price_hide">
                <td colspan="" style="line-height: 37px; width: 18%" align="right">
                    <p style="color: #000; margin-top: -52px; font-size: 18px !important;">จำนวนเงินคืนสินค้า</p>
                </td>
                <td class="text-right" style="width: 15%;">
                    <p style="color: #000; margin-top: -52px; font-size: 18px !important;">{{ number_format($data['OrderSumPriceReturn'],2) }}</p>
                </td>
                <td class="" style="width: 18%; "></td>
            </tr>
            <tr class="price_hide">
                <td colspan=""></td>
                <td class=""></td>
                <td class="" style="width: 18%; "></td>
            </tr>
            <tr class="price_hide">
                <td colspan=""></td>
                <td class=""></td>
                <td class="" style="width: 18%; "></td>
            </tr>
            <tr class="price_hide">
                <td colspan="" style="width: 18%" align="right"></td>
                <td class="text-right" style="width: 15%; ">
                    <p style="color: #000; margin-top: -65px;  font-size: 18px !important ">{{ number_format($data['Order']->grand_total-$data['OrderSumPriceReturn'],2) }}</p>
                </td>
                <td class="" style="width: 18%; "></td>
            </tr>
          </table>

          <table class="price_hide2" height="205px">
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

        </tr>
        <tr class="price_hide2" >
            <td colspan="6"></td>
        </tr>
        <tr class="">
            <table style="width: 100%">
              <td class="" style="width: 10%; "></td>
              <td style="line-height: 37px; width: 20%;">
                  <p style="color: #000; margin-top: -15px; margin-left: -20%; font-size: 22px !important ">
                       {{ $data['Order']->status_departmen == "00" ? "( ".$data['Order']->name_merchant." )" : '' }}
                  </p>
              </td>
              <td class="" style="width: 10%; "></td>
              <td style="width: 20%; ">
                  <p style="color: #000; margin-top: 0px; margin-left: -25%; font-size: 22px !important ">
                      {{ $data['Order']->status_departmen == "01" ? $data['Order']->name_merchant : '' }}
                  </p>
              </td>
            </table>
        </tr>
    </table>
    <div style="page-break-after: always"></div>
    <?php } ?>
</form>

