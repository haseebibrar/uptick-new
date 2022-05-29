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
  @php
    //dd($myTime);
  @endphp
  <div class="col-md-6">
      <div class="topSection px-4 py-4 bgWhite">
          <h2 class="mb-4">Schedule a lesson</h2>
          <div id='loading'>loading...</div>
          <div id='calendar'></div>
      </div>
  </div>
  <div class="col-md-4">
    <div class="topSection px-4 py-4 bgWhite">
      <h3 class="mb-4 text-center">Set your weekly hours</h3>
      <div class="innerSec">
        <form class="mt-4 mb-4" method="POST" action="/admin/lessonsubject/update" enctype="multipart/form-data">
          @csrf
          <div class="dynamicAddRemove dynamicAddRemovesun">
            <div class="form-check mb-2 row d-flex">
              <div class="col-md-2">
                <input type="checkbox" name="suncheck[0][name]" class="form-check-input" id="focus-1">
                <label class="form-check-label" for="focus-1">Sun</label>
              </div>
              <div class="col-md-4">
                <select name="suncheck[0][time][1]" class="form-control">
                  <option value="unavailable">Unavailable</option>
                  {!! $myTime !!}
                </select>
              </div>
              <div class="col-md-1"> - </div>
              <div class="col-md-4"><select name="suncheck[0][time][2]" class="form-control">{!! $myTime !!}</select></div>
              <div class="col-md-1"><button type="button" name="add" data-day="sun" class="btn btn-success addTimeBtn">+</button></div>
            </div>
          </div>
          <div class="form-check mb-2 row d-flex">
            <div class="col-md-2">
              <input type="checkbox" name="moncheck" class="form-check-input" id="focus-2">
              <label class="form-check-label" for="focus-2">Mon</label>
            </div>
            <div class="col-md-4">
              <select name="monstart" class="form-control">
                <option value="unavailable">Unavailable</option>
                {!! $myTime !!}
              </select>
            </div>
            <div class="col-md-1"> - </div>
            <div class="col-md-4">
              <select name="monend" class="form-control">
                <option value="unavailable">Unavailable</option>
                {!! $myTime !!}
              </select>
            </div>
          </div>
          <div class="form-check mb-2 row d-flex">
            <div class="col-md-2">
              <input type="checkbox" name="tuecheck" class="form-check-input" id="focus-3">
              <label class="form-check-label" for="focus-3">Tue</label>
            </div>
            <div class="col-md-4">
              <select name="tuestart" class="form-control">
                <option value="unavailable">Unavailable</option>
                {!! $myTime !!}
              </select>
            </div>
            <div class="col-md-1"> - </div>
            <div class="col-md-4">
              <select name="tueend" class="form-control">
                <option value="unavailable">Unavailable</option>
                {!! $myTime !!}
              </select>
            </div>
          </div>
          <div class="form-check mb-2 row d-flex">
            <div class="col-md-2">
              <input type="checkbox" name="wedcheck" class="form-check-input" id="focus-4">
              <label class="form-check-label" for="focus-4">Wed</label>
            </div>
            <div class="col-md-4">
              <select name="wedstart" class="form-control">
                <option value="unavailable">Unavailable</option>
                {!! $myTime !!}
              </select>
            </div>
            <div class="col-md-1"> - </div>
            <div class="col-md-4">
              <select name="wedend" class="form-control">
                <option value="unavailable">Unavailable</option>
                {!! $myTime !!}
              </select>
            </div>
          </div>
          <div class="form-check mb-2 row d-flex">
            <div class="col-md-2">
              <input type="checkbox" name="thucheck" class="form-check-input" id="focus-5">
              <label class="form-check-label" for="focus-5">Thu</label>
            </div>
            <div class="col-md-4">
              <select name="thustart" class="form-control">
                <option value="unavailable">Unavailable</option>
                {!! $myTime !!}
              </select>
            </div>
            <div class="col-md-1"> - </div>
            <div class="col-md-4">
              <select name="thuend" class="form-control">
                <option value="unavailable">Unavailable</option>
                {!! $myTime !!}
              </select>
            </div>
          </div>
          <div class="form-check mb-2 row d-flex">
            <div class="col-md-2">
              <input type="checkbox" name="fricheck" class="form-check-input" id="focus-6">
              <label class="form-check-label" for="focus-6">Fri</label>
            </div>
            <div class="col-md-4">
              <select name="fristart" class="form-control">
                <option value="unavailable">Unavailable</option>
                {!! $myTime !!}
              </select>
            </div>
            <div class="col-md-1"> - </div>
            <div class="col-md-4">
              <select name="friend" class="form-control">
                <option value="unavailable">Unavailable</option>
                {!! $myTime !!}
              </select>
            </div>
          </div>
          <div class="form-check mb-2 row d-flex">
            <div class="col-md-2">
              <input type="checkbox" name="satcheck" class="form-check-input" id="focus-7">
              <label class="form-check-label" for="focus-7">Sat</label>
            </div>
            <div class="col-md-4">
              <select name="satstart" class="form-control">
                <option value="unavailable">Unavailable</option>
                {!! $myTime !!}
              </select>
            </div>
            <div class="col-md-1"> - </div>
            <div class="col-md-4">
              <select name="satend" class="form-control">
                <option value="unavailable">Unavailable</option>
                {!! $myTime !!}
              </select>
            </div>
          </div>
          <button type="submit" class="btn btn-primary btnGreen">Save</button>
        </form>
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
  <div class="modal" id="myModalSmall" tabindex="-1" role="dialog">
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
          document.getElementById('script-warning').style.display = 'none'
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