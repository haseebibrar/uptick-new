@extends('layouts.auth')

@section('content')
    <div class="col-md-6">
        <div class="topSection px-4 py-4 bgWhite">
            <h2 class="mb-4">Schedule a lesson</h2>
            <div class="response"></div>
            <div id='calendar'></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="topSection px-4 py-4 bgWhite">
            <h3 class="mb-4">Set a lesson focus area</h3>
            @foreach($focusareas as $focusarea)
                <div class="form-check mb-1">
                    <input type="checkbox" class="form-check-input" id="focus-{{$focusarea->id}}">
                    <label class="form-check-label" for="focus-{{$focusarea->id}}">{{$focusarea->name}}</label>
                </div>
            @endforeach
        </div>
        <div class="btmSection tblTeacherPnl mt-4 px-4 py-4 bgWhite">
            <h3 class="mb-4">Teachers available at selected time</h3>
            <div class="table-responsive mt-4">
                <table class="table" id="myDataTable">
                    <thead>
                        <tr><th></th><th></th><th></th><th></th></tr>
                    </thead>
                    <tbody>
                        @foreach($teachers as $teacher)
                            @php
                                $myImage = '';
                                if(!empty($teacher->image))
                                    $myImage = asset('images/users/'.$teacher->image);
                            @endphp
                            <tr>
                                <td class="align-middle">{!! ($myImage === "" ? '' : '<img class="rounded-circle imgmr-1" style="height:50px;" src="'.$myImage.'" alt="'.$teacher->name.'" title="'.$teacher->name.'" />') !!}</td>
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
@endsection

@section('scripts')
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js" integrity="sha256-4iQZ6BVL4qNKlQ27TExEhBN1HFPvAvAMbFavKKosSWQ=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
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
        }
    });
    var SITEURL = "{{url('/')}}";
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var calendar = $('#calendar').fullCalendar({
        header: {
            center: 'agendaDay,agendaWeek' // buttons for switching between views
        },
        defaultView     : 'agendaDay',
        allDaySlot      : false,
        /*views: {
            agendaDay: {
                type        : 'agenda',
                allDaySlot  : false,
                minTime     : '08:00:00',
                maxTime     : '20:00:00',
            }
        },*/
        editable        : true,
        events          : SITEURL + "/fullcalendareventmaster",
        displayEventTime: true,
        eventRender     : function (event, element, view) {
            //alert('test');
            //console.log(event);
            if (event.allDay === 'true') {
                event.allDay = true;
            } else {
                event.allDay = false;
            }
        },
        selectable      : true,
        selectHelper    : true,
        select          : function (start, end, allDay) {
            var title = prompt('Event Title:');
            if (title) {
                //alert(start);
                var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
                var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");

                $.ajax({
                    url: SITEURL + "/fullcalendareventmaster/create",
                    data: 'title=' + title + '&start=' + start + '&end=' + end,
                    type: "POST",
                    success: function (data) {
                        displayMessage("Added Successfully");
                    }
                });
                calendar.fullCalendar('renderEvent',
                    {
                        title: title,
                        start: start,
                        end: end,
                        //allDay: allDay
                    },
                    //true
                );
            }
            calendar.fullCalendar('unselect');
        },
            
        eventDrop: function (event, delta) {
            var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
            var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
            $.ajax({
                url: SITEURL + '/fullcalendareventmaster/update',
                data: 'title=' + event.title + '&start=' + start + '&end=' + end + '&id=' + event.id,
                type: "POST",
                success: function (response) {
                    displayMessage("Updated Successfully");
                }
            });
        },
        eventClick: function (event) {
            var deleteMsg = confirm("Do you really want to delete?");
            if (deleteMsg) {
                $.ajax({
                    type: "POST",
                    url: SITEURL + '/fullcalendareventmaster/delete',
                    data: "&id=" + event.id,
                    success: function (response) {
                        if(parseInt(response) > 0) {
                            $('#calendar').fullCalendar('removeEvents', event.id);
                            displayMessage("Deleted Successfully");
                        }
                    }
                });
            }
        }
    });
});
 
  function displayMessage(message) {
    $(".response").html(""+message+"");
    setInterval(function() { $(".success").fadeOut(); }, 1000);
  }
</script>
@endsection