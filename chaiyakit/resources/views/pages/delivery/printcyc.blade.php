<form method="post" action="#" id="printJS-form" class="d-none">
    <br><br><br>
    <table style="width: 100%; font-size: 12px !important">
        <tr>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td colspan="6">
                <table style="width: 100%">
                    <tr>
                        <td style="text-align: left !important; width: 62%"> </td>
                        <td style="width: 38%;">
                            <p style="color: #000; font-size: 14px !important; margin-left: 25px; margin-top: -5px;">
                            เลขที่การจัดส่ง
                                {{ $data['OrderDelivery']->order_delivery_number }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="6">
                <table style="width: 100%">
                    <tr>
                        <td style="text-align: left !important; width: 50%" align="left">
                            <p style="color: #000; margin-top: -10px; font-size: 14px !important; margin-left: 25px;">
                                ใบสั่งซื้อเลขที่ {{ $data['Order']->order_number }}
                            </p>
                        </td>
                        <td style="text-align: right !important; width: 50%" align="right"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="6">
                <table style="width: 100%">
                    <tr>
                        <td style="text-align: left !important; width: 50%" align="left">
                            <p style="color: #000; margin-left: 120px; margin-top: 5px; font-size: 15px !important;">{{-- ชื่อลูกค้า --}}
                                {{ isset($data['Order']->department_name) ? $data['Order']->department_name : $data['Order']->name_merchant }}
                            </p>
                        </td>
                        <td>{{-- วันที่ --}}
                            <p style="color: #000; margin-left: 40px; margin-top: 5px; font-size: 15px !important;">
                                {{ $data['Order']->getOrderDate() }}</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="6">
                <table style="width: 100%">
                    <tr>
                        <td style="text-align: left !important; width: 50%" align="left">
                            <p style="color: #000; margin-left: 120px; margin-top: 0px; font-size: 15px !important;">{{-- ที่อยู่ --}}
                                {{ $data['Order']->status_departmen == "00" ? $data['Order']->address : $data['Order']->address_department }} &nbsp;
                            </p>
                        </td>
                        <td>{{-- โทร. --}}
                            <p style="color: #000; margin-left: 40px; margin-top: 0px; font-size: 15px !important;">
                                {{   $data['Order']->status_departmen == "00" ? $data['Order']->phone_number : $data['Order']->phone_department }} &nbsp;</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="6"></td>
        </tr>
        <tr>
            <td colspan="6"></td>
        </tr>

        <tr>
            <td colspan="6">
                <table style="width: 100%; margin-top: -5px;">
                    @foreach ($data['OrderDeliveryItem'] as $key => $item)
                    <tr style="">
                        <td style="width: 16.5%; text-align: center">
                            <p style="color: #000; margin-top: -12px; font-size: 12px !important;">{{number_format($item->qty)}}</p>
                        </td>
                        <td style="width: 20%; text-align: center">
                          @php
                            $total_text = $item->product_name." ".$item->product_size_name_text;
                            $name_pro = iconv_substr($item->product_name, 0, 3);
                          @endphp
                            @if($name_pro == 'เสา')
                                    @if(strlen($total_text) <= 44)
                                        <p style="color: #000; margin-top: -12px; font-size: 12px !important;">{{ $item->product_name." ".$item->product_size_name_text  }}</p>
                                    @else
                                        <p style="color: #000; margin-top: -12px; font-size: 5px !important;">{{ $item->product_name." ".$item->product_size_name_text  }}</p>
                                    @endif
                            @else
                                @if(strlen($total_text) <= 44)
                                    <p style="color: #000; margin-top: -12px; font-size: 12px !important;">{{ $item->product_name." ".$item->product_size_name_text  }}</p>
                                @else
                                    <p style="color: #000; margin-top: -12px; font-size: 5px !important;">{{ $item->product_name." ".$item->product_size_name_text  }}</p>
                                @endif
                            @endif
                        </td>
                        <td style="width: 14%; text-align: center">
                            <p style="color: #000; margin-top: -12px; font-size: 12px !important;">{{ $item->total_size_text }}</p>
                        </td>
                        <td style="width: 14.2%; text-align: center">
                            <p style="color: #000; margin-top: -12px; font-size: 12px !important;" class="show_amount">{{ number_format($item->price+$item->addition,2) }}</p>
                        </td>
                        <td style="width: 17.6%; text-align: right;">
                            <p style="color: #000; margin-top: -12px; font-size: 12px !important; margin-right: 1px;" class="show_amount">{{ number_format($item->total_price,2) }}</p>
                        </td>
                        <td style="text-align: left;">
                            <p style="color: #000; margin-top: -12px; font-size: 12px !important; margin-left: 20px;">{{ $item->remark }}</p>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </td>
        </tr>

        <?php
          $order = count($data['OrderDeliveryItem']);
          $order_totle = 10 - $order;
        ?>

        @for ($i = 1; $i <= $order_totle; $i++) <tr>
            <td colspan="6"></td>
            </tr>
            @endfor

            <tr class="">
                <td colspan="4" style="text-align: left !important;">
                    <p style="color: #000; margin-top: -1px; font-size: 12px !important; margin-left: 25px;">
                        {{ $data['OrderDelivery']->product_return_claim_id ? '**หมายเหตุ : เคลมสินค้า' : 'หมายเหตุ :'. $data['Order']->noteorder }}
                    </p>
                </td>
                <td class=""></td>
                <td class=""></td>
            </tr>
            <tr>
                <td colspan="6"></td>
            </tr>
            <tr>
                <td colspan="6"></td>
            </tr>
            <tr>
                <td colspan="6"></td>
            </tr>
            <tr>
                <td colspan="6"></td>
            </tr>
            <tr>
              <td colspan="6">
                <table style="width: 100%">
                  <td colspan="4"></td>
                  <td style="width: 25%; text-align: right !important">
                      <p class="show_amount" style="margin-top: 0px; font-size: 12px !important;">
                          {{ number_format($data['Order']->grand_total,2) }}</p>
                  </td>
                  <td style="width: 18.1%;"></td>
                </table>
              </td>
            </tr>
            <tr class="">
              <table style="width: 100%" >
                <td class="" style="width: 10%; "></td>
                <td style="line-height: 37px; width: 20%;">
                    <p style="color: #000; margin-top: 15px; margin-left: -90%; font-size: 15px !important ">
                         {{ $data['Order']->status_departmen == "00" ?"( ".$data['Order']->name_merchant." )" : '' }}
                    </p>
                </td>
                <td class="" style="width: 10%; "></td>
                <td style="width: 20%; ">
                    <p style="color: #000; margin-top: 0px; margin-left: -120%; font-size: 15px !important ">
                        {{  $data['Order']->status_departmen == "01" ? $data['Order']->name_merchant : '' }}
                    </p>
                </td>
              </table>
            </tr>
    </table>
</form>
