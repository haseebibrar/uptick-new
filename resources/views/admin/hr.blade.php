@extends('layouts.auth')

@section('content')
    @php
        //dd(Auth::user());
    @endphp
    <div class="col-md-7">
        <div class="topSection px-4 py-4 bgWhite">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="txtCenter mb-4">Weekly Lesson Stats</h2>
                    <div class="row justify-content-md-center">
                        <div class="col col-md-4 mr-2 innerNumsDiv bgGreen">
                            <h3>Conducted</h3>
                            <div class="numsData">22</div>
                        </div>
                        <div class="col col-md-4 innerNumsDiv bgPink">
                            <h3>Scheduled</h3>
                            <div class="numsData">40</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h2 class="txtCenter mb-4">Last 30 Days</h2>
                    <div class="row justify-content-md-center">
                        <div class="col col-md-4 mr-2 innerNumsDiv bgGreenDark">
                            <h3>Conducted</h3>
                            <div class="numsData">100</div>
                        </div>
                        <div class="col col-md-4 innerNumsDiv bgYellow">
                            <h3>Scheduled</h3>
                            <div class="numsData">160</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="btmSection studentStats mt-4 px-4 py-4 bgWhite">
            <h2 class="mb-4">Student Statistics</h2>
            <div class="table-responsive mt-4">
                <table class="table" id="myDataTable">
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
                            @endphp
                            <tr>
                                <td><div class="circleAct"></div></td>
                                <td>{!! ($myImage === "" ? '' : '<img class="rounded-circle imgmr-1" style="height:50px;" src="'.$myImage.'" alt="'.$student->name.'" title="'.$student->name.'" />') !!}<div class="tdRight">{{$student->name}}<br /><p class="noMargin txtSmall">{{$student->title}}</p></div></td>
                                <td class="align-middle">{{ $student->deptname }}</td>
                                <td>
                                    <div class="txtCenter">3/6</div>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                                <td class="txtCenter">{{ rand(1, 10) }}</td>
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
        <div class="chartSectionHr px-4 py-4 bgWhite">
            <h2 class="txtCenter mb-4">Lesson Type</h2>
            <div id="chart"></div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    $(document).ready(function() {
        $('#myDataTable').DataTable({
            pageLength: 20,
            lengthMenu: [
                [20, 50, 100, 500],
                [20, 50, 100, 500]
            ],
            info: true,
            lengthChange: false
        });
        $("#myDataTable_filter").append("<a class='btn btnGrayBg btnSearchBox mr-4 ml-4' href='/admin'>Auto Divide</a><a class='btn btnSearchBox btnGreen' href='/admin/students/add'>+ Add Member</a>")
        $(document).off('click', '.btnDel').on('click', '.btnDel', function(){
            if(confirm("Are you sure you want to delete this?")){
                myID        = $(this).attr('data-id');
                window.location = "/admin/students/delete/"+myID;
            }
            else{
                return false;
            }
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
            series: [44, 55, 13, 33, 55, 65],
            labels: ['Vocabulary and Fluency', 'Grammar', 'Interview Prep', 'Presentation Prep', 'Debate', 'Current Affairs'],
            colors: ['#FFEC02', '#58CFEF', '#5F369E', '#FD6B82', '#28DF99', '#5886D5']
        }
        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    });
</script>
@endsection