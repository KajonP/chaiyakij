<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<style>
    span.notification-badge {
        position: relative;
        top: -10px;
        right: 10px;
        border: $white 1px solid;
    }

    .scrollable-menu {
        height: auto;
        max-height: 350px;
        width: 300px;
        overflow-x: hidden;
    }

    h5 {
        font-size: 18px;
        font-weight: bold;
        margin-left: 5%;
    }

    .fontsize13 {
        font-size: 13px;
    }

    .fontsize12 {
        font-size: 12px;
    }

    .dotorange {
        height: 40px;
        width: 40px;
        background-color: #F26401;
        border-radius: 50%;
        display: table;
        margin: 15px auto;
        margin-left: 60%;
    }

    .dotogreen {
        height: 40px;
        width: 40px;
        background-color:green;
        border-radius: 50%;
        display: table;
        margin: 15px auto;
        margin-left: 60%;
    }

    .dotred {
        height: 40px;
        width: 40px;
        background-color: red;
        border-radius: 50%;
        display: table;
        margin: 15px auto;
        margin-left: 60%;
    }

    .dotblue {
        height: 40px;
        width: 40px;
        background-color: #1D48FA;
        border-radius: 50%;
        display: table;
        margin: 15px auto;
        margin-left: 60%;
    }
    .dropdown-item.fontsize13 {
        padding: 0.25rem 0.5rem;
    }
    .icon_image{
        height: 40px;
        width: 40px;
        display: table;
        margin-left: 60%;
    }
    .dropdown-menu.noti_li.scrollable-menu.show{
        min-width: 20rem;
    }
</style>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ไชยกิจคอนกรีต</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/fontawesome/css/all.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/datatable/datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/gijgo/css/gijgo.min.css') }} " />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/daterangepicker/daterangepicker.css') }}">
    @yield('css')
</head>

<body>
    <div class="wrapper" id="app">
        <!-- Sidebar  -->
        @include('layouts.sidebar')

        <!-- Page Content  -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">


                    <a id="sidebarCollapse" style="font-size:20px; font-weight: bold;">
                        ไชยกิจคอนกรีต
                    </a>

                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">

                            <div class="dropdown">
                                <a class="nav-link" id="" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    <i class="fa fa-lg fa-bell"></i>
                                    <span class="notification-badge badge badge-danger noti">0
                                    </span>
                                </a>
                                <div class="dropdown-menu noti_li scrollable-menu">
                                </div>
                            </div>

                            <li class="nav-item btn btn-outline-dark disabled mr-2">
                                <a class="">
                                    {{ Auth::user()->name }}
                                    <i class="fas fa-angle-down"></i>
                                </a>
                            </li>
                            <li class="nav-item btn btn-dark">
                                <a class="" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();localStorage.clear();"><i
                                        class="fas fa-sign-out-alt"></i>
                                    ออกจากระบบ
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="row justify-content-end text-right">
                <div class="col-12">
                    <p id="date_time" class="pr-3">กำลังโหลด..</p>
                </div>
            </div>
            <div class="content">

                @yield('content')
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/jquery/jquery.form.js') }}"></script>
    <script src="{{ asset('assets/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/fontawesome/js/all.js') }}"></script>
    <script src="{{ asset('assets/sweetalert/sweetalert2@9.js') }}"></script>
    <script src="{{ asset('assets/js/cleave.min.js') }}"></script>
    <script src="{{ asset('assets/js/cleave-phone.i18n.js') }}"></script>
    <script src="{{ asset('assets/js/moment.js') }}"></script>
    <script src="{{ asset('assets/js/moment-with-locales.min.js') }}"></script>
    <script src="{{ asset('assets/js/numeral.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/fontawesome/js/all.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/isNumber.js') }}"></script>
    <script src="{{ asset('assets/gijgo/js/gijgo.min.js') }}"></script>
    <script src="{{ asset('assets/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/bootstrap-3-typeahead/bootstrap3-typeahead.min.js') }}"></script>


    <script type="text/javascript">
        $(document).ready(function () {
            $("[rel='tooltip']").tooltip()

            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active')
            });
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // Update the count down every 5 second
        moment.locale('th')
        var x = setInterval(function() {$('#date_time').text(moment().format('LLLL'))}, 1000)
    </script>
    <script>
        $(document).ready(function(){

        function load_unseen_notification()
        {
        $.ajax({
            url: `{{ url('api/noti/Notification') }}`,
        method:"GET",
        dataType:"json",
        success:function(data)
        {
            //console.log('xx1',data.Data4)

            var subDay_schedul = data.Data1;
            var addDay_schedul = data.Data2;
            var addDay_license = data.Data3;
            var subDay_orderdend = data.Data4;
            var count = 0;
            var url_icon = `{{url('assets/image/bell-alert.png')}}`
            var str ="<h5>แจ้งเตือน</h5>";

            $('.noti_li').html(str);
            for(i=0; i<subDay_orderdend.length; i++){
                if(subDay_orderdend[i].status_read == true)
                {
                let url_delivery_confirm1 = `{{ url('api/noti/NotificationCacheOrder/${subDay_orderdend[i].order_id}') }}`
                str += `<div class="row"><div class="col-sm-3 "><span><img class="icon_image" src="${url_icon}"></span></div><div class="col-9"><a class="dropdown-item fontsize13 text-secondary" href="${url_delivery_confirm1}">ใบสั่งซื้อเลขที่ ${subDay_orderdend[i].order_number} ใกล้ถึงวันจัดส่งแล้ว <br>วันที่จัดส่งสิ้นค้า <br> วันที่ : ${subDay_orderdend[i].datemarksend}</a></div></div><hr>`;
                }else{
                let url_delivery_confirm1 = `{{ url('api/noti/NotificationCacheOrder/${subDay_orderdend[i].order_id}') }}`
                str += `<div class="row"><div class="col-sm-3 "><span><img class="icon_image" src="${url_icon}"></span></div><div class="col-9"><a class="dropdown-item fontsize13" href="${url_delivery_confirm1}">ใบสั่งซื้อเลขที่ ${subDay_orderdend[i].order_number} ใกล้ถึงวันจัดส่งแล้ว <br>วันที่จัดส่งสิ้นค้า  : ${subDay_orderdend[i].datemarksend}</a></div></div><hr>`;
                count=count+1;
                }
            }

            for(i=0; i<subDay_schedul.length; i++){
                if(subDay_schedul[i].status_read == true)
                {
                let url_delivery_confirm1 = `{{ url('api/noti/NotificationCache/${subDay_schedul[i].order_id}/${subDay_schedul[i].order_delivery_id}/${subDay_schedul[i].truck_schedule_id}') }}`
                str += `<div class="row"><div class="col-sm-3 "><span><img class="icon_image" src="${url_icon}"></span></div><div class="col-9"><a class="dropdown-item fontsize13 text-secondary" href="${url_delivery_confirm1}">คุณมีสินค้าที่ยังไม่ได้ทำการจัดส่ง <br>กรุณาทำการจัดส่งสินค้า <br> วันที่ : ${subDay_schedul[i].date_schedule}</a></div></div><hr>`;
                }else{
                let url_delivery_confirm1 = `{{ url('api/noti/NotificationCache/${subDay_schedul[i].order_id}/${subDay_schedul[i].order_delivery_id}/${subDay_schedul[i].truck_schedule_id}') }}`
                str += `<div class="row"><div class="col-sm-3 "><span><img class="icon_image" src="${url_icon}"></span></div><div class="col-9"><a class="dropdown-item fontsize13" href="${url_delivery_confirm1}">คุณมีสินค้าที่ยังไม่ได้ทำการจัดส่ง <br>กรุณาทำการจัดส่งสินค้า <br> วันที่ : ${subDay_schedul[i].date_schedule}</a></div></div><hr>`;
                count=count+1;
                }
            }
            $('.noti_li').html(str);

            for(i=0; i<addDay_schedul.length; i++){
                if(addDay_schedul[i].status_read == true)
                {
                let url_delivery_confirm2 = `{{ url('api/noti/NotificationCache/${addDay_schedul[i].order_id}/${addDay_schedul[i].order_delivery_id}/${addDay_schedul[i].truck_schedule_id}') }}`
                str += `<div class="row"><div class="col-sm-3 "><span><img class="icon_image" src="${url_icon}"></span></div><div class="col-9"><a class="dropdown-item fontsize13 text-secondary" href="${url_delivery_confirm2}">คุณมีสินค้าที่เลยวันจัดส่ง <br>กรุณาทำการจัดส่งสินค้า <br> วันที่ : ${addDay_schedul[i].date_schedule}</a></div></div><hr>`;
                }else{
                let url_delivery_confirm2 = `{{ url('api/noti/NotificationCache/${addDay_schedul[i].order_id}/${addDay_schedul[i].order_delivery_id}/${addDay_schedul[i].truck_schedule_id}') }}`
                str += `<div class="row"><div class="col-sm-3 "><span><img class="icon_image" src="${url_icon}"></span></div><div class="col-9"><a class="dropdown-item fontsize13" href="${url_delivery_confirm2}">คุณมีสินค้าที่เลยวันจัดส่ง <br>กรุณาทำการจัดส่งสินค้า <br> วันที่ : ${addDay_schedul[i].date_schedule}</a></div></div><hr>`;
                count=count+1;
                }
            }
            $('.noti_li').html(str);

            for(i=0; i<addDay_license.length; i++){
                if(addDay_license[i].status_read == true)
                {
                let url_delivery_confirm2 = `{{ url('api/noti/NotificationCacheMastertruck/${addDay_license[i].master_truck_id}') }}`
                str += ` <div class="row"><div class="col-sm-3 "><span><img class="icon_image" src="${url_icon}"></span></div><div class="col-9"><a class="dropdown-item fontsize13 text-secondary" href="${url_delivery_confirm2}">คุณมีรถยนต์ป้ายทะเบียน ${addDay_license[i].license_plate} <br>ที่จะหมดอายุภาษีทะเบียนรถ<br>ในอีก 30 วัน กรุณาตรวจสอบข้อมูล </a></div></div><hr>`;
                }else{
                let url_delivery_confirm2 = `{{ url('api/noti/NotificationCacheMastertruck/${addDay_license[i].master_truck_id}') }}`
                str += ` <div class="row"><div class="col-sm-3 "><span><img class="icon_image" src="${url_icon}"></span></div><div class="col-9"><a class="dropdown-item fontsize13" href="${url_delivery_confirm2}">คุณมีรถยนต์ป้ายทะเบียน ${addDay_license[i].license_plate} <br>ที่จะหมดอายุภาษีทะเบียนรถ<br>ในอีก 30 วัน กรุณาตรวจสอบข้อมูล </a></div></div><hr>`;
                count=count+1;
                }

            }




            $('.noti_li').html(str);
            $('.noti').text(count);


            $('.dropdown-menu').html(data.notification);
            if(data.unseen_notification > 0)
            {
            $('.count').html(data.unseen_notification);
            }
        }
        });
        }

        load_unseen_notification();

        $(document).on('click', '.dropdown-toggle', function(){
        $('.count').html('');
        load_unseen_notification('yes');
        });

        setInterval(function(){
        load_unseen_notification();
        }, 10000);

        });

    </script>
    @yield('js')
</body>

</html>
