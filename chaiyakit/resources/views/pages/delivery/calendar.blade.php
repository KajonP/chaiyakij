@extends('layouts.app')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/fullcalendar-4.3.1/packages/core/main.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/fullcalendar-4.3.1/packages/daygrid/main.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/fullcalendar-4.3.1/packages/bootstrap/main.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/fullcalendar-4.3.1/packages/timegrid/main.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/fullcalendar-4.3.1/packages/list/main.css') }}">
@endsection

@section('content')
<div class="form-main" id="page-calendar">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 align-self-end">
        <h2>ปฎิทินการจัดส่งสินค้า</h2>
      </div>
      <div class="col-12">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="{{ route('delivery') }}">เมนูการจัดส่งสินค้า</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">ปฎิทินการจัดส่งสินค้า</li>
          </ol>
        </nav>
      </div>
      <div class="col-12 text-right">
        <i class="fa fa-circle" style="font-size:20px;color:#495057"></i> รอการจัดส่ง
        <i class="fa fa-circle" style="font-size:20px;color:#FF871E"></i> เตรียมการจัดส่ง
        <i class="fa fa-circle" style="font-size:20px;color:#FFC627"></i> กำลังเดินทางจัดส่ง
        <i class="fa fa-circle" style="font-size:20px;color:#EE6C98"></i> เลื่อน
        <i class="fa fa-circle" style="font-size:20px;color:#EE6C98"></i> เครม
        <i class="fa fa-circle" style="font-size:20px;color:#27C671"></i> จัดส่งสำเร็จ
      </div>
    </div>
    <hr>
    {{-- <div id='loading'>loading...</div> --}}
    <div id='calendar-container'>
      <div id='calendar'></div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script src="{{ asset('assets/fullcalendar-4.3.1/packages/core/main.js') }}"></script>
<script src="{{ asset('assets/fullcalendar-4.3.1/packages/interaction/main.js') }}"></script>
<script src="{{ asset('assets/fullcalendar-4.3.1/packages/daygrid/main.js') }}"></script>
<script src="{{ asset('assets/fullcalendar-4.3.1/packages/bootstrap/main.js') }}"></script>
<script src="{{ asset('assets/fullcalendar-4.3.1/packages/timegrid/main.js') }}"></script>
<script src="{{ asset('assets/fullcalendar-4.3.1/packages/list/main.js') }}"></script>
<script src="{{ asset('assets/fullcalendar-4.3.1/packages/core/locales-all.js') }}"></script>

<script>
  document.addEventListener("DOMContentLoaded", function() {
  var calendarEl = document.getElementById("calendar");
  var calendar = new FullCalendar.Calendar(calendarEl, {
    locale: "th",
    header: {
      left: "prev, next, today, dayGridMonth, listWeek", // timeGridDay , timeGridWeek
      right: "title_popup",
      center: ""
    },
    plugins: ["interaction", "dayGrid", "timeGrid", "list", "bootstrap"],
    themeSystem: "bootstrap",
    // defaultDate: '2020-01-01',
    buttonIcons: false, // show the prev/next text
    weekNumbers: false,
    navLinks: true, // can click day/week names to navigate views
    editable: false,
    eventLimit: true, // allow "more" link when too many events
    eventRender: function (info) {
      //console.log(info.event.extendedProps);

    $(info.el).popover({
       content: info.event.extendedProps.title_popup+'<br>'+info.event.extendedProps.namestore+'<br>'+"เวลา : "+info.event.extendedProps.roundtime+'<br>'+info.event.extendedProps.trucktype+info.event.extendedProps.licenseplate,
       trigger: 'hover',
       html: true,
       });
    },
    events: {
      url: "{{ url('api/delivery/calendar') }}",
      failure: function() {
        // document.getElementById('script-warning').style.display = 'block'
      }
    },
    loading: function(bool) {
      // document.getElementById('loading').style.visibility = bool ? 'visible' : 'hidden';
    },
    // eventClick: function(info) {
    //   info.jsEvent.preventDefault(); // don't let the browser navigate

    //   if (info.event.url) {
    //     alert(info.event.url);
    //     // window.open(info.event.url);
    //   }
    // },
    // eventMouseEnter: function(info) {
    //     console.log(info.event.title);

    //  $(this.el).tooltip({description: "Lecture", department: "BioChemistry"});
    //   // {description: "Lecture", department: "BioChemistry"}
    // }
  });

  calendar.render();

});


</script>
@endsection
