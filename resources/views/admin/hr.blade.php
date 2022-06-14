@extends('layouts.auth')

@section('content')
    @php
        //dd(Auth::user());
    @endphp
    <div class="col-md-8 noPadRight">
        <div class="topSection studentStatsTop px-4 py-4 bgWhite">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="txtCenter mb-4">Weekly Lesson Stats</h2>
                    <div class="row justify-content-md-center">
                        <div class="col col-md-4 mr-2 innerNumsDiv bgGreen">
                            <h3>Conducted</h3>
                            <div class="numsData">{{ $totalConductW }}</div>
                        </div>
                        <div class="col col-md-4 innerNumsDiv bgPink">
                            <h3>Scheduled</h3>
                            <div class="numsData">{{ $totalSchedtW }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h2 class="txtCenter mb-4">Last 30 Days</h2>
                    <div class="row justify-content-md-center">
                        <div class="col col-md-4 mr-2 innerNumsDiv bgGreenDark">
                            <h3>Conducted</h3>
                            <div class="numsData">{{ $totalConductM }}</div>
                        </div>
                        <div class="col col-md-4 innerNumsDiv bgYellow">
                            <h3>Scheduled</h3>
                            <div class="numsData">{{ $totalSchedtM }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="btmSection studentStats px-4 py-4 bgWhite">
            <h2 class="mb-4">Student Statistics</h2>
            <div class="table-responsive mt-4">
                <table class="table" id="myDataTableStudent">
                    <thead>
                        <tr>
                            <th data-orderable="false" scope="col" class="txtCenter">Activity</th>
                            <th scope="col">Students</th>
                            <th scope="col">Department</th>
                            <th scope="col">Progress</th>
                            <th scope="col" class="txtCenter">Cancellation</th>
                            <th data-orderable="false" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            @php
                                $myImage = '';
                                if(!empty($student->image))
                                    $myImage = asset('images/users/'.$student->image);
                                
                                $studentEv  = $student->events;
                                $pastEvents = 0;
                                $totalEvent = $student->allocated_hour;
                                $myPercente = 0;
                                $canceled   = 0;
                                if($studentEv->isNotEmpty()){
                                    foreach($studentEv as $event){
                                        $myTime = strtotime($event->start);
                                        if($myTime < time())
                                            $pastEvents = $pastEvents+1;
                                        if($event->status === "canceled")
                                            $canceled = $canceled+1;
                                    }
                                    $myPercente = round(($pastEvents / $totalEvent)*100, 2);
                                }
                                //dd($student->events);
                            @endphp
                            <tr>
                                <td class="align-middle"><div class="circleAct{{ $totalEvent < 1 ? ' circleActRed' : '' }}"></div></td>
                                <td class="align-middle text-nowrap"><div style="display:flex;">{!! ($myImage === "" ? '' : '<img class="imgmr-1" style="width:50x; height:30px;" src="'.$myImage.'" alt="'.$student->name.'" title="'.$student->name.'" />') !!}<div style="margin-top: 0.4rem; text-align: center; line-height: 1.4;">{{$student->name}}<br /><p class="noMargin txtSmall">{{$student->title}}</p></div></div></td>
                                <td class="align-middle">{{ $student->deptname }}</td>
                                <td class="align-middle">
                                    <div class="txtCenter">{{ $pastEvents }}/{{ $totalEvent }}</div>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $myPercente }}%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                                <td class="align-middle txtCenter">{{ $canceled }}</td>
                                <td class="text-nowrap">
                                    <a href="/admin/students/edit/{{$student->id}}" class="btn mr-3 btnGray"><i class="fa fa-pencil"></i></a>
                                    <a href="javascript:void(0)" data-id="{{$student->id}}" class="btn btnDel btnGray"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="chartSectionHr px-4 py-4 bgWhite myHeight">
            <h2 class="txtCenter mb-4">Lesson Type</h2>
            <div id="chart"></div>
        </div>
    </div>

    <!-- Modal Event Open -->
    <div class="modal" id="myModal" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modalEvents modal-dialog-centered" role="document">
            <div class="modal-content px-4 py-4">
                <div style="text-align: right;"><a class="closeModal" href="javascript:void(0)"><i class="fa fa-close"></i></a></div>
                <div class="ajaxData"></div>
            </div>
        </div>
    </div>
    <!-- Modal Event Open -->
@endsection

@section('scripts')
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    $(document).ready(function() {
        $('#myModal').modal({
            backdrop: 'static', 
            keyboard: false
        });
        $('#myDataTableStudent').DataTable({
            pageLength: 20,
            lengthMenu: [
                [20, 50, 100, 500],
                [20, 50, 100, 500]
            ],
            info: true,
            lengthChange: false,
            language: {
                search: "",
                searchPlaceholder: "Search"
            },
            targets: 'no-sort',
            bSort: false,
            order: []
        });
        $("#myDataTableStudent_wrapper .row:first div:first").append('Total: {{ $totalStudents }} members');
        $("#myDataTableStudent_filter").append("<a class='btn btnSearchBox btnGreen' href='/admin/students/add'>+ Add Member</a><div class='newBtnsDiv'><p>Hourly Bank: {{ $remHours }}/{{ $bankHours }}</p><a data-id='{{ $myCompID }}' class='btn btnGrayBg btnSearchBox btnDivide' href='javascript:void(0)'>Auto Divide</a></div>")
        // 
        $(document).off('click', '.btnDel').on('click', '.btnDel', function(){
            if(confirm("Are you sure you want to delete this?")){
                myID        = $(this).attr('data-id');
                window.location = "/admin/students/delete/"+myID;
            }
            else{
                return false;
            }
        });

        $(document).off('click', '.closeModal').on('click', '.closeModal', function(){
            $('#myModal').modal('hide');
        });

        $(document).off('click', '.btnDivide').on('click', '.btnDivide', function(){
            var compID = $(this).attr('data-id');
            $('#myModal .ajaxData').html('<div class="text-center"><strong>Processing...</strong><br /><div class="spinner-border ml-auto" style="width: 3rem; height: 3rem;" role="status" aria-hidden="true"></div></div>');
            $('#myModal').modal('show');
            //alert(compID);
            $.ajax({
                url: "{{url('/')}}/admin/divide_hours",
                type:'POST',
                data: {_token:"{{ csrf_token() }}", compID:compID},
                success: function(data) {
                    $('#myModal .ajaxData').html(data);
                    location.reload();
                }
            });
        });
        $('#msgSuccess').delay(3000).fadeOut('slow');
        var options = {
            chart: {
                type: 'donut',
                height: '700'
            },
            pie: {
                customScale: 0.6,
                expandOnClick: false,
                donut: {
                    size: '100%'
                }
            },
            dataLabels: {
                textAnchor: 'middle',
                distributed: false,
                offsetX: 0,
                offsetY: 0,
                dropShadow: {
                    enabled: false,
                }
            },
            legend: {
                horizontalAlign: 'center',
                position: 'bottom',
                floating: false,
            },
            series: [20, 10, 10, 5, 15, 10, 20, 10],
            labels: ['Vocabulary and Fluency', 'Grammar', 'Interview Prep', 'Presentation Prep', 'Debate', 'Current Affairs', 'Professional Writing', 'Freestyle'],
            colors: ['#FFEC02', '#58CFEF', '#5F369E', '#FD6B82', '#28DF99', '#FFA754', '#5886D5', '#EE585D']
        }
        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    });
</script>
@endsection