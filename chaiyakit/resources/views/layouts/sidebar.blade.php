<style>
    #sidebar.active label {
        text-align: center;
        display: none;
    }
</style>
<nav id="sidebar">
    <div class="sidebar-header">
        <h3>ไชยกิจคอนกรีต</h3>
        <strong>CYK</strong>
    </div>

    <ul class="list-unstyled components">

        <li class="{{ Request::is('home') ? 'active' : '' }}">
            <a href="{{ route('home') }}" rel="tooltip" data-placement="right" title="หน้าแรก" style="font-size:16px">
                {{-- <i class="fas fa-home"></i> --}}
                <img class="mr-2" src="{{ asset('assets/image/ico_menu05_02.png') }}" alt="" width="20px">
                <label>หน้าแรก</label>
            </a>
        </li>

        <li class="{{ Request::is('orders*') ? 'active' : '' }}">
            <a href="{{ route('orders') }}" rel="tooltip" data-placement="right" title="จัดการใบสั่งซื้อสินค้า"
                style="font-size:16px">
                <img class="mr-2" src="{{ asset('assets/image/ico_menu01_02.png') }}" alt="" width="20px">
                <label>จัดการใบสั่งซื้อสินค้า</label>
            </a>

        </li>
        @hasanyrole('super-admin|manager|admin')
        <li class="{{ Request::is('delivery*') ? 'active' : '' }}">
            <a href="{{ route('delivery') }}" rel="tooltip" data-placement="right" title="การจัดส่งสินค้า"
                style="font-size:16px">
                {{-- <i class="fas fa-home"></i> --}}
                <img class="mr-2" src="{{ asset('assets/image/ico_menu02_02.png') }}" alt="" width="20px">
                <label>การจัดส่งสินค้า</label>
            </a>
        </li>
        @endhasanyrole
        @hasanyrole('super-admin|manager|admin')
        <li class="{{ Request::is('claim*') ? 'active' : '' }}">
            <a href="{{ route('claim') }}" rel="tooltip" data-placement="right" title="การจัดส่งสินค้า"
                style="font-size:16px">
                {{-- <i class="fas fa-home"></i> --}}
                <img class="mr-2" src="{{ asset('assets/image/ico_menu03_02.png') }}" alt="" width="20px">
                <label>คืน / เคลม สินค้า</label>
            </a>
        </li>

        @endhasanyrole

        @hasanyrole('super-admin|manager')
        <li class="{{ Request::is('report*') ? 'active' : '' }}">
            <a href="#data_report" data-toggle="collapse" class="dropdown-toggle" rel="tooltip"
                data-placement="right" title="รายงาน" style="font-size:16px">
                <img class="mr-2" src="{{ asset('assets/image/ico_menu01_02.png') }}" alt="" width="20px">
                <label>รายงาน</label>
            </a>
            <ul class="collapse list-unstyled {{ Request::is('report*') ? 'show' : '' }}" id="data_report">
                <li class="{{ Request::is('report/report_return*') ? 'active' : '' }}">
                    <a href="{{ route('report_return') }}" rel="tooltip" data-placement="right"
                        title="การคืนสินค้า"><i class="fas fa-circle mr-1" style="font-size:7px;"></i> <label
                            style="font-size: 14px">การคืนสินค้า</label></a>
                </li>
                <li class="{{ Request::is('report/report_claim*') ? 'active' : '' }}">
                    <a href="{{ route('report_claim') }}" rel="tooltip" data-placement="right"
                        title="การเคลมสินค้า"><i class="fas fa-circle mr-1" style="font-size:7px"></i><label
                            style="font-size: 14px"> การเคลมสินค้า</label></a>
                </li>
                <li class="{{ Request::is('report/report_total*') ? 'active' : '' }}">
                    <a href="{{ route('report_total') }}" rel="tooltip" data-placement="right"
                        title="ยอดขายรวม"><i class="fas fa-circle mr-1"
                            style="font-size:7px"></i><label style="font-size: 14px">
                            ยอดขายรวม</label></a>
                </li>
                <li class="{{ Request::is('report/report_transaction*') ? 'active' : '' }}">
                    <a href="{{ route('report_transaction') }}" rel="tooltip" data-placement="right"
                        title="ยอดขายตาม transaction"><i class="fas fa-circle mr-1" style="font-size:7px"></i><label
                            style="font-size: 14px"> ยอดขายตาม transaction</label></a>
                </li>
            </ul>
        </li>
        @endhasanyrole

        @hasanyrole('super-admin|manager')

        <li class="{{ Request::is('master-data*') ? 'active' : '' }}">
            <a href="#data_management" data-toggle="collapse" class="dropdown-toggle" rel="tooltip"
                data-placement="right" title="จัดการข้อมูล" style="font-size:16px">
                <img class="mr-2" src="{{ asset('assets/image/ico_menu04_02.png') }}" alt="" width="20px">
                <label>จัดการข้อมูล</label>
            </a>
            <ul class="collapse list-unstyled {{ Request::is('master-data*') ? 'show' : '' }}" id="data_management">
                <li class="{{ Request::is('master-data/product_information*') ? 'active' : '' }}">
                    <a href="{{ route('product_information') }}" rel="tooltip" data-placement="right"
                        title="จัดการข้อมูลสินค้า"><i class="fas fa-circle mr-1" style="font-size:7px;"></i> <label
                            style="font-size: 14px">จัดการข้อมูลสินค้า</label></a>
                </li>
                <li class="{{ Request::is('master-data/car_type_information*') ? 'active' : '' }}">
                    <a href="{{ route('car_type_information') }}" rel="tooltip" data-placement="right"
                        title="จัดการข้อมูลประเภทรถ"><i class="fas fa-circle mr-1" style="font-size:7px"></i><label
                            style="font-size: 14px"> จัดการข้อมูลประเภทรถ</label></a>
                </li>
                <li class="{{ Request::is('master-data/delivery_car*') ? 'active' : '' }}">
                    <a href="{{ route('delivery_car') }}" rel="tooltip" data-placement="right"
                        title="จัดการข้อมูลรถจัดส่งสินค้า"><i class="fas fa-circle mr-1"
                            style="font-size:7px"></i><label style="font-size: 14px">
                            จัดการข้อมูลรถจัดส่งสินค้า</label></a>
                </li>
                <li class="{{ Request::is('master-data/delivery_time*') ? 'active' : '' }}">
                    <a href="{{ route('delivery_time') }}" rel="tooltip" data-placement="right"
                        title="จัดการข้อมูลเวลาจัดส่ง"><i class="fas fa-circle mr-1" style="font-size:7px"></i><label
                            style="font-size: 14px"> จัดการข้อมูลเวลาจัดส่ง</label></a>
                </li>
                <li class="{{ Request::is('master-data/vat*') ? 'active' : '' }}">
                    <a href="{{ route('vat') }}" rel="tooltip" data-placement="right" title="จัดการข้อมูลภาษี"><i
                            class="fas fa-circle mr-1" style="font-size:7px"></i><label style="font-size: 14px">
                            จัดการข้อมูลภาษี</label></a>
                </li>
                <li class="{{ Request::is('master-data/itemsend*') ? 'active' : '' }}">
                    <a href="{{ route('master.itemsend') }}" rel="tooltip" data-placement="right"
                        title="จัดการข้อมูลลูกค้า"><i class="fas fa-circle mr-1" style="font-size:7px"></i><label
                            style="font-size: 14px"> จัดการข้อมูลขนส่งรายวัน</label></a>
                </li>
                <li class="{{ Request::is('master-data/itemmerchant*') ? 'active' : '' }}">
                    <a href="{{ route('master.itemmerchant') }}" rel="tooltip" data-placement="right"
                        title="จัดการข้อมูลขนส่งรายวันแยกร้านค้า"><i class="fas fa-circle mr-1" style="font-size:7px"></i>
                        <label style="font-size: 14px"> จัดการข้อมูลขนส่งสินค้าร้านค้า</label></a>
                </li>
                <li class="{{ Request::is('master-data/customer_information*') ? 'active' : '' }}">
                    <a href="{{ route('customer_information') }}" rel="tooltip" data-placement="right"
                        title="จัดการข้อมูลลูกค้า"><i class="fas fa-circle mr-1" style="font-size:7px"></i><label
                            style="font-size: 14px"> จัดการข้อมูลลูกค้า</label></a>
                </li>
            </ul>
        </li>
        @endhasanyrole
        @hasanyrole('super-admin|manager')
        <li class="{{ Request::is('master-users*') ? 'active' : '' }}">
            <a href="{{ route('users') }}" rel="tooltip" data-placement="right" title="จัดการผู้ดูแลระบบ"
                style="font-size:16px">
                <img class="mr-2" src="{{ asset('assets/image/ico_menu06_02.png') }}" alt="" width="20px">
                <label>จัดการผู้ดูแลระบบ</label>
            </a>
        </li>
        @endhasanyrole
    </ul>
</nav>
