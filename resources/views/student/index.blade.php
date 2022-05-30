@extends('layouts.auth')
@push('css')
    <link href="{{ asset('lib/main.css') }}" rel="stylesheet" type='text/css'>
    <style>
        #calendar {
            max-width: 1100px;
            margin: 0 auto;
        }
    </style>
@endpush
@section('content')
    <div class="col-md-6">
        <div class="topSection px-4 py-4 bgWhite">
            <h2 class="mb-4">Schedule a lesson</h2>
            <div id='loading'>loading...</div>
            <div id='calendar'></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="topSection px-4 py-4 bgWhite">
            <h3 class="mb-4">Set a lesson focus area</h3>
            @foreach($focusareas as $focusarea)
                <div class="form-check mb-1">
                    <input type="radio" name="focusarea" value="{{$focusarea->id}}" class="form-check-input focusCheck" id="focus-{{$focusarea->id}}">
                    <label class="form-check-label" for="focus-{{$focusarea->id}}">{{$focusarea->name}}</label>
                </div>
            @endforeach
        </div>
        <div class="btmSection tblTeacherPnl mt-4 px-4 py-4 bgWhite">
            <div class="disabledDiv"></div>
            <h3 class="mb-4">Available Teachers</h3>
            <div class="table-responsive mt-4">
                <table class="table" id="myDataTable">
                    <thead>
                        <tr><th></th><th></th><th></th><th></th></tr>
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
                                <td class="align-middle">{{$teacher->expertise}} Marketing</td>
                                <td class="text-nowrap align-middle"><a href="#" class="btn btnSchedule">Schedule</a></td>
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
          <div class="row">
            <div style="text-align: right;">
              <a class="editEvent" data-id="" href="javascript:void(0)"><i class="fa fa-pencil"></i></a>
              <a class="dltEvent" data-id="" href="javascript:void(0)"><i class="fa fa-trash"></i></a>
              <a class="closeModal" href="javascript:void(0)"><i class="fa fa-close"></i></a>
            </div>
            <div style="font-size: 15px;">Vocabulary and Fluency</div>
            <div class="align-middle" style="font-size: 13px;"><img height="11" src="{{ asset('images/clock.svg') }}" alt="Clock" title="Clock" /> Monday, May 02, 9:00 - 10:00am</div>
            <div style="font-size: 13px;"><img height="11" src="{{ asset('images/user.svg') }}" alt="User" title="User" /> Max Smith</div>
            <div style="font-size: 13px;"><img height="11" src="{{ asset('images/bell.svg') }}" alt="Bell" title="Bell" /> 10 minutes before</div>
          </div>

        </div>
      </div>
    </div>
    <!-- Modal Event Open -->

    <!-- Modal Event Open -->
    <div class="modal" id="myModalSmall" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modalEvents modal-dialog-centered" role="document">
        <div class="modal-content px-4 py-4">
            <div style="font-size: 15px; text-align:center;" class="mb-4">Are you sure you want to delete this lesson?</div>
            <div style="display:flex; text-align:center; margin: 0 auto;">
              <a href="javascript:void(0)" class="btnStndrd btnGreen closeModal" style="margin-right: 10px;">No</a>
              <a href="javascript:void(0)" data-id="" class="btnDelEvent btnStndrd btnGray">Yes</a>
            </div>
          </div>

        </div>
      </div>
    </div>
    <!-- Modal Event Open -->
@endsection

@section('scripts')
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('lib/main.js') }}"></script>
<script>
$(document).ready(function () {
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
      $('#myModal, #myModalSmall').modal('hide');
    });
    $(document).off('click', '.dltEvent').on('click', '.dltEvent', function(){
      $('#myModal').modal('hide');
      $('#myModalSmall').modal('show');
      var myEventID = $(this).attr('data-id');
      $('.btnDelEvent').attr('data-id', myEventID);
    });
    $(document).off('click', '.btnDelEvent').on('click', '.btnDelEvent', function(){
      $('#myModalSmall .modal-content').html('<div class="text-center"><strong>Processing...</strong><br /><div class="spinner-border ml-auto" style="width: 3rem; height: 3rem;" role="status" aria-hidden="true"></div></div>');
      //var _token = {{ csrf_token() }};
      var myEventID = $(this).attr('data-id');
      $.ajax({
          url: "{{url('/')}}/eventdelete",
          type:'POST',
          data: {_token:"{{ csrf_token() }}", myEventID:myEventID},
          success: function(data) {
            $('#myModalSmall .modal-content').html(data);
          }
      });
    });
    
    $(document).off('change', '.focusCheck').on('change', '.focusCheck', function(){
      //alert();
      var myFocusID = $(this).val();
      $('.teacherData').html('<td colspan="3" class="text-center">Loading Teachers...</td>');
      //return false;
      $.ajax({
          url: "{{url('/')}}/geteachers",
          type:'POST',
          data: {_token:"{{ csrf_token() }}", myFocusID:myFocusID},
          success: function(data) {
            $('.disabledDiv').remove();
            $('.teacherData').html(data);
          }
      });
    });
    
    $(document).off('click', '.btnSchedule').on('click', '.btnSchedule', function(){
      $(this).removeClass('active');
      $(this).addClass('active');
      var myTeacherID = $(this).attr('data-id');
      var myFocusID   = $(this).attr('data-focus');
      $('#myModalSmall .modal-content').html('<div class="text-center"><strong>Processing...</strong><br /><div class="spinner-border ml-auto" style="width: 3rem; height: 3rem;" role="status" aria-hidden="true"></div></div>');
      $('#myModalSmall').modal('show');
      //return false;
      //alert(myTeacherID);
      $.ajax({
          url: "{{url('/')}}/openbookpoup",
          type:'POST',
          data: {_token:"{{ csrf_token() }}", myTeacherID:myTeacherID, myFocusID:myFocusID},
          success: function(data) {
            //$('#myModalSmall .modal-content').html(data);
          }
      });
    });
});


document.addEventListener('DOMContentLoaded', function() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var calendarEl = document.getElementById('calendar');
    var SITEURL = "{{url('/')}}";
    var calendar = new FullCalendar.Calendar(calendarEl, {
      themeSystem       : 'standard',
      height            : 'auto',
      allDaySlot        : false,
      expandRows        : true,
      slotMinTime       : '08:00',
      slotMaxTime       : '20:00',
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
        url: SITEURL + "/fullcalendareventmaster",
        failure: function() {
          //document.getElementById('script-warning').style.display = 'none'
        }
      },
      loading: function(bool) {
        document.getElementById('loading').style.display =bool ? 'block' : 'none';
      },
      select: function(arg) {
        $.ajax({
            url: SITEURL + "/fullcalendareventmaster/create",
            data: 'start=' + arg.start + '&end=' + arg.end,
            type: "POST",
            success: function (data) {
                displayMessage("Added Successfully");
            }
        });
        calendar.unselect()
      },
      eventClick: function(arg) {
        $('#myModal').modal('show');
        var myEventID = arg.event.id;
        $('.editEvent, .dltEvent').attr('data-id', myEventID);
        $('.modal-backdrop').hide();
        //console.log(arg);
        console.log(arg.event.id);
        //if (confirm('Are you sure you want to delete this event?')) {
        //  arg.event.remove()
        //}
      },
    });
    calendar.render();
  });
</script>
@endsection