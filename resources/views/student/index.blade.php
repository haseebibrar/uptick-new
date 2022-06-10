@extends('layouts.auth')
@push('css')
    <link href="{{ asset('lib/main.css') }}" rel="stylesheet" type='text/css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        #calendar {
            max-width: 1100px;
            margin: 0 auto;
        }
    </style>
@endpush
@section('content')
  <div class="col-md-7 noPadRight">
      <div class="topSection px-4 py-4 bgWhite">
          <h2 class="mb-4">Schedule a lesson</h2>
          <div id='loading'>loading...</div>
          <div id='calendar'></div>
      </div>
  </div>
  <div class="col-md-4">
    <div class="topSection topSectionPnl px-4 py-4 bgWhite">
      <div class="disabledDivFocus"></div>
      <h3 class="mb-4">Set a lesson focus area</h3>
      <input type="hidden" name="myday" id="myday" value="">
      <input type="hidden" name="mydate" id="mydate" value="">
      <input type="hidden" name="mystart" id="mystart" value="">
      <input type="hidden" name="myend" id="myend" value="">
      <input type="hidden" name="mydatefull" id="mydatefull" value="">
      @foreach($focusareas as $focusarea)
        <div class="form-check mb-1">
          <input type="radio" name="focusarea" value="{{$focusarea->id}}" data-name="{{$focusarea->name}}" class="form-check-input focusCheck" id="focus-{{$focusarea->id}}">
          <label class="form-check-label" for="focus-{{$focusarea->id}}">{{$focusarea->name}}</label>
        </div>
      @endforeach
    </div>
    <div class="btmSection tblTeacherPnl mt-4 px-4 py-4 bgWhite">
      <div class="disabledDiv"></div>
      <h3 class="mb-4">Teachers available at selected time</h3>
      <div class="table-responsive mt-4">
        <table class="table" id="myDataTable">
          <thead>
            <tr><th></th><th></th><th></th></tr>
          </thead>
          <tbody class="teacherData">
            @foreach($teachers as $teacher)
              @php
                $myImage = '';
                if(!empty($teacher->image))
                    $myImage = asset('images/users/'.$teacher->image);
              @endphp
              <tr>
                <td class="align-middle">{!! ($myImage === "" ? '' : '<img class="rounded-circle imgmr-1" style="max-width:50px; max-height:50px;" src="'.$myImage.'" alt="'.$teacher->name.'" title="'.$teacher->name.'" />') !!}</td>
                <td class="align-middle">{{$teacher->name}}</td>
                <td class="text-nowrap align-middle"><a href="#" data-name="{{$teacher->name}}" class="btn btnSchedule">Schedule</a></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal Event Open -->
  <div class="modal" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modalEvents modal-dialog-centered" role="document">
      <div class="modal-content px-4 py-4">
        <form method="POST" action="/save-event" enctype="multipart/form-data">
          @csrf
          <div class="row">
            <div style="text-align: right;"><a class="closeModal" href="javascript:void(0)"><i class="fa fa-close"></i></a></div>
            <div style="font-size: 17px; font-weight: 600; margin-bottom: 1rem;" class="focusTitle"></div>
            <div class="align-middle" style="font-size: 13px;">
              <div class="form-check mb-2 row d-flex">
                <div class="col-md-5">
                  <div class="input-group date">
                    <img height="11" style="margin-top: 9px;margin-right: 1rem;" src="{{ asset('images/clock.svg') }}" alt="Clock" title="Clock" /> 
                    <input type="text" name="event_date" class="form-control datepicker" value="">
                    <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                  </div>
                </div>
                <div class="timeDiv" style="display: contents;"></div>
              </div>
            </div>
            <div style="display:flex; font-size: 15px; font-weight: 600; margin: 1rem 0; "><img class="teacherImg" style="margin-right: 1rem;margin-top: 8px;margin-left: 20px;" height="11" src="{{ asset('images/user.svg') }}" alt="Teacher" title="Teacher" /><div class="teacherTitle"></div></div>
            <input type="hidden" name="teacher_id" id="teacher_id" value="">
            <input type="hidden" name="student_id" id="student_id" value="{{ Auth::user()->id }}">
            <input type="hidden" name="focusarea_id" id="focusarea_id" value="">
            <input type="hidden" name="lesson_id" id="lesson_id" value="0">
            <button type="submit" class="btn btn-primary btnGreen">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Modal Event Open -->

  <!-- Modal Event Open -->
  <div class="modal" id="myModalSec" tabindex="-1" role="dialog">
    <div class="modal-dialog modalEvents modal-dialog-centered" role="document">
      <div class="modal-content px-4 py-4">
        <div class="row">
          <div style="text-align: right;">
            <a class="editEvent" data-id="" href="javascript:void(0)"><i class="fa fa-pencil"></i></a>
            <a class="dltEvent" data-id="" href="javascript:void(0)"><i class="fa fa-trash"></i></a>
            <a class="closeModal" href="javascript:void(0)"><i class="fa fa-close"></i></a>
          </div>
          <div style="font-size: 17px; font-weight: 600; margin-bottom: 1rem;" class="focusTitleEdit"></div>
          
          <div class="align-middle" style="font-size: 13px;">
            <div class="form-check mb-2 row d-flex">
              <div class="col-md-5">
                <div class="input-group date">
                  <img height="11" style="margin-top: 9px;margin-right: 1rem;" src="{{ asset('images/clock.svg') }}" alt="Clock" title="Clock" /> 
                  <input type="text" name="event_date" class="form-control datepicker" value="">
                  <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span></div>
                </div>
              </div>
              <div class="timeDiv" style="display: contents;"></div>
            </div>
          </div>
          <div style="font-size: 13px;"><img height="11" src="{{ asset('images/user.svg') }}" alt="User" title="User" /> Max Smith</div>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal Event Open -->

  <!-- Modal Event Open -->
  <div class="modal" id="myModalDel" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modalEvents modal-dialog-centered" role="document">
      <div class="modal-content px-4 py-4">
        <div style="font-size: 15px; text-align:center; display:none;" class="mb-4 delWarning">Your lesson is less than 24 hours away, which means that it can't be canceled and will count as a lesson 🙁</div>
        <div style="font-size: 15px; text-align:center;" class="mb-4">Are you sure you want to delete this lesson?</div>
        <div style="display:flex; text-align:center; margin: 0 auto;">
          <a href="javascript:void(0)" class="btnStndrd btnGreen closeModal" style="margin-right: 10px;">No</a>
          <a href="javascript:void(0)" data-id="" data-lesshour="0" class="btnDelEvent btnStndrd btnGray">Yes</a>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal Event Open -->

  <!-- Modal Event Open -->
  <div class="modal" id="myModalSmall" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modalEvents modal-dialog-centered" role="document">
      <div class="modal-content px-4 py-4"></div>
    </div>
  </div>
  <!-- Modal Event Open -->
@endsection

@section('scripts')
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('lib/main.js') }}"></script>
<script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
{{-- <script src="{{ asset('js/moment.js') }}"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.3/moment.min.js"></script>
<script>
var calendar;
  $(document).ready(function () {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var calendarEl = document.getElementById('calendar');
    var SITEURL = "{{url('/')}}";
    var calendar = new FullCalendar.Calendar(calendarEl, {
      themeSystem       : 'standard',
      height            : '85vh',
      allDaySlot        : false,
      expandRows        : true,
      slotMinTime       : '07:00',
      slotMaxTime       : '22:00',
      slotDuration      : '01:00',
      headerToolbar     : {
        left    : 'prev,next today',
        center  : 'title',
        right   : 'timeGridWeek,timeGridDay'
      },
      initialView       : 'timeGridWeek',
      navLinks          : true, // can click day/week names to navigate views
      editable          : false,
      selectable        : true,
      nowIndicator      : true,
      dayMaxEvents      : true, // allow "more" link when too many events
      events: {
        url: SITEURL + "/getevents",
        failure: function() {
          //document.getElementById('script-warning').style.display = 'none'
        }
      },
      eventDidMount: function (info) {
        //console.log(info);
        info.el.innerHTML = '<div class="eventInfo">'+info.timeText+'<br />'+info.event._def.title+' / '+info.event._def.extendedProps.description+'</div>';
      },
      loading: function(bool) {
        document.getElementById('loading').style.display =bool ? 'block' : 'none';
      },
      select: function(arg) {
        var myStart = moment(arg.start, "m-dd-y");
        //alert(myStart);
        if(myStart.isBefore(moment())) {
            alert("You can't book in past!");
            return false;
        }
        //console.log(arg.jsEvent);
        //arg.dayEl.style.backgroundColor = 'red';
        //alert(arg.start);
        $.ajax({
            url: SITEURL + "/get-data",
            data: {start:arg.start, end:arg.end},
            type: "POST",
            success: function (data) {
              if(data === "1"){
                // alert();
                $('#myModalSmall .modal-content').html('<div style="font-size: 15px; text-align:center;" class="mb-4">You don\'t have any Hours please contact with your company.</div><div style="display:flex; text-align:center; margin: 0 auto;"><a href="javascript:void(0)" class="btnStndrd btnGreen closeModal" style="margin-right: 10px;">Close</a></div>');
                $('#myModalSmall').modal('show');
              }else{
                $('.disabledDivFocus').hide();
                myData = data.split("--");
                $('#myday').val(myData[0]);
                $('#mydate').val(myData[1]);
                $('#mystart').val(myData[2]);
                $('#myend').val(myData[3]);
                 $('#mydatefull').val(myData[4]);
                $("input[name='focusarea']:radio").prop( "checked", false );
                $('.disabledDiv').show();
              }
              return false;
            }
        });
        calendar.unselect()
      },
      eventClick: function(arg) {
        //$('#myModal').modal('show');
        var myEventID = arg.event.id;
        $('.editEvent, .dltEvent').attr('data-id', myEventID);
        $('.modal-backdrop').hide();
        $('#myModalSmall .modal-content').html('<div class="text-center"><strong>Loading...</strong><br /><div class="spinner-border ml-auto" style="width: 3rem; height: 3rem;" role="status" aria-hidden="true"></div></div>');
        $('#myModalSmall').modal('show');
        $.ajax({
            url: "{{url('/')}}/editbook",
            type:'POST',
            data: {_token:"{{ csrf_token() }}", myEventID:myEventID},
            success: function(data) {
              $('#myModalSmall').modal('hide');
              $('#myModalSec').modal('show');
              //$('#myModalSec .modal-content').html(data);
              //alert(myDateFull);
              //$('.datepicker').val(myDateFull);
              //$('.datepicker').datepicker({
              //  autoclose : true,
              //  format    : 'M dd yyyy', //format    : moment().format('MM D YYYY'),
              //  startDate : 'd'
              //});
              //$('.timeDiv').html(data);
              //$('#myModal').modal('show');
              //$('#myModalSmall').modal('hide');
              //$('#myModalSmall .modal-content').html(data);
            }
        });
      },
    });
    calendar.render();

    $('#myModalSmall').modal({
      backdrop: 'static', 
      keyboard: false
    });
    $('#myDataTable').DataTable({
        pageLength: 20,
        lengthMenu: [
            [20, 50, 100, 500],
            [20, 50, 100, 500]
        ],
        info: false,
        lengthChange: false,
        paging: false,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search"
        },
    });

    $(document).off('click', '.closeModal').on('click', '.closeModal', function(){
      $('#myModal, #myModalSmall, #myModalDel, #myModalSec').modal('hide');
    });

    $(document).off('click', '.dltEvent').on('click', '.dltEvent', function(){
      $('#myModalSec').modal('hide');
      $('.delWarning').hide();
      $('#myModalDel').modal('show');
      var myEventID = $(this).attr('data-id');
      $('.btnDelEvent').attr('data-id', myEventID);
    });

    $(document).off('click', '.btnDelEvent').on('click', '.btnDelEvent', function(){
      $('#myModalSmall .modal-content').html('<div class="text-center"><strong>Processing...</strong><br /><div class="spinner-border ml-auto" style="width: 3rem; height: 3rem;" role="status" aria-hidden="true"></div></div>');
      $('#myModalSmall').modal('show');
      //var _token = {{ csrf_token() }};
      var myEventID   = $(this).attr('data-id');
      var myLessHour  = $(this).attr('data-lesshour');
      $.ajax({
          url: "{{url('/')}}/event-delete",
          type:'POST',
          data: {_token:"{{ csrf_token() }}", myEventID:myEventID, myLessHour:myLessHour},
          success: function(data) {
            $('#myModalSmall').modal('hide');
            if(data === "No"){
              $('.delWarning').show();
              $('.btnDelEvent').attr('data-lesshour', 1);
              //
            }else{
              $('#myModalDel').modal('hide');
              calendar.refetchEvents();
              $('.btnDelEvent').attr('data-lesshour', 0);
            }
            //$('#myModalSmall .modal-content').html(data);
          }
      });
    });
    
    $(document).off('change', '.focusCheck').on('change', '.focusCheck', function(){
      //alert();
      var myFocusID = $(this).val();
      var myDay     = $('#myday').val();
      var myTime    = $('#mystart').val();
      //
      $('.focusTitle').html($(this).attr('data-name'));
      $('.teacherData').html('<td colspan="3" class="text-center">Loading Teachers...</td>');
      //return false;
      $.ajax({
          url: "{{url('/')}}/geteachers",
          type:'POST',
          data: {_token:"{{ csrf_token() }}", myFocusID:myFocusID, myDay:myDay, myTime:myTime},
          success: function(data) {
            $('.disabledDiv').hide();
            $('.teacherData').html(data);
          }
      });
    });
    
    $(document).off('change', '.datepicker').on('change', '.datepicker', function(){
      $('.timeDiv').html('Loading...');
      $(':input[type="submit"]').prop('disabled', true);
      var weekday     = ["sun","mon","tue","wed","thu","fri","sat"];
      var myTeacherID = $('#teacher_id').val();
      var myFocusID   = $('#focusarea_id').val();
      var myDayGet    = new Date($(this).val());
      var myDay       = weekday[myDayGet.getDay()];
      var myDate      = $(this).val();
      var myStart     = $('#mystart').val();
      var myEnd       = $('#myend').val();
      $.ajax({
          url: "{{url('/')}}/openbookpoup",
          type:'POST',
          data: {_token:"{{ csrf_token() }}", myTeacherID:myTeacherID, myFocusID:myFocusID, myDay:myDay, myDate:myDate, myStart:myStart, myEnd:myEnd},
          success: function(data) {
            $('.datepicker').datepicker({
              autoclose : true,
              format    : 'M dd yyyy', //format    : moment().format('MM D YYYY'),
              startDate : 'd'
            });
            if(data === "1"){
              $('.timeDiv').html('<div class="col-md-8" style="font-size: 15px;">Unavailable</div>');
            }else{
              $('.timeDiv').html(data);
              $(':input[type="submit"]').prop('disabled', false);
            }
          }
      });
      //alert($(this).val());
    });

    $(document).off('click', '.btnSchedule').on('click', '.btnSchedule', function(){
      $('.btnSchedule').removeClass('active');
      $(this).addClass('active');
      var myTeacherID = $(this).attr('data-id');
      var myFocusID   = $(this).attr('data-focus');
      var myDay       = $('#myday').val();
      var myDate      = $('#mydate').val();
      var myStart     = $('#mystart').val();
      var myEnd       = $('#myend').val();
      var myDateFull  = $('#mydatefull').val();
      $('#teacher_id').val(myTeacherID);
      $('#focusarea_id').val(myFocusID);
      $('.teacherTitle').html($(this).attr('data-name'));
      $('#myModalSmall .modal-content').html('<div class="text-center"><strong>Processing...</strong><br /><div class="spinner-border ml-auto" style="width: 3rem; height: 3rem;" role="status" aria-hidden="true"></div></div>');
      $('#myModalSmall').modal('show');
      
      //return false;
      //alert(myTeacherID);
      $.ajax({
          url: "{{url('/')}}/openbookpoup",
          type:'POST',
          data: {_token:"{{ csrf_token() }}", myTeacherID:myTeacherID, myFocusID:myFocusID, myDay:myDay, myDate:myDate, myStart:myStart, myEnd:myEnd},
          success: function(data) {
            $('#myModalSmall').modal('hide');
            //alert(myDateFull);
            $('.datepicker').val(myDateFull);
            $('.datepicker').datepicker({
              autoclose : true,
              format    : 'M dd yyyy', //format    : moment().format('MM D YYYY'),
              startDate : 'd'
            });
            $('.timeDiv').html(data);
            $('#myModal').modal('show');
            //$('#myModalSmall').modal('hide');
            //$('#myModalSmall .modal-content').html(data);
          }
      });
    });
  });
</script>
@endsection