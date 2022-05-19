@extends('layouts.auth')

@section('content')
    <div class="col-md-6">
        <div class="topSection tblLessonPnl px-4 py-4 bgWhite">
            <h2 class="mb-4">Completed Lessons (13)</h2>
            <div class="table-responsive mt-4">
                <table class="table" id="myDataTable">
                    <thead>
                        <tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="align-middle">1</td>
                            <td class="align-middle"><img class="rounded-circle imgmr-1" style="height:50px;" src="{{ asset('images/max.png') }}" alt="" title="" /> Max Smith</td>
                            <td class="align-middle">2/5/22</td>
                            <td class="align-middle">Vocabulary and Fluency</td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Recording</a></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Slides</a></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Homework</a></td>
                        </tr>
                        <tr>
                            <td class="align-middle">2</td>
                            <td class="align-middle"><img class="rounded-circle imgmr-1" style="height:50px;" src="{{ asset('images/max.png') }}" alt="" title="" /> Max Smith</td>
                            <td class="align-middle">2/5/22</td>
                            <td class="align-middle">Vocabulary and Fluency</td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Recording</a></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Slides</a></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Homework</a></td>
                        </tr>
                        <tr>
                            <td class="align-middle">3</td>
                            <td class="align-middle"><img class="rounded-circle imgmr-1" style="height:50px;" src="{{ asset('images/max.png') }}" alt="" title="" /> Max Smith</td>
                            <td class="align-middle">2/5/22</td>
                            <td class="align-middle">Vocabulary and Fluency</td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Recording</a></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Slides</a></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Homework</a></td>
                        </tr>
                        <tr>
                            <td class="align-middle">4</td>
                            <td class="align-middle"><img class="rounded-circle imgmr-1" style="height:50px;" src="{{ asset('images/max.png') }}" alt="" title="" /> Max Smith</td>
                            <td class="align-middle">2/5/22</td>
                            <td class="align-middle">Vocabulary and Fluency</td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Recording</a></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Slides</a></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Homework</a></td>
                        </tr>
                        <tr>
                            <td class="align-middle">5</td>
                            <td class="align-middle"><img class="rounded-circle imgmr-1" style="height:50px;" src="{{ asset('images/max.png') }}" alt="" title="" /> Max Smith</td>
                            <td class="align-middle">2/5/22</td>
                            <td class="align-middle">Vocabulary and Fluency</td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Recording</a></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Slides</a></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Homework</a></td>
                        </tr>
                        <tr>
                            <td class="align-middle">6</td>
                            <td class="align-middle"><img class="rounded-circle imgmr-1" style="height:50px;" src="{{ asset('images/max.png') }}" alt="" title="" /> Max Smith</td>
                            <td class="align-middle">2/5/22</td>
                            <td class="align-middle">Vocabulary and Fluency</td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Recording</a></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Slides</a></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Homework</a></td>
                        </tr>
                        <tr>
                            <td class="align-middle">7</td>
                            <td class="align-middle"><img class="rounded-circle imgmr-1" style="height:50px;" src="{{ asset('images/max.png') }}" alt="" title="" /> Max Smith</td>
                            <td class="align-middle">2/5/22</td>
                            <td class="align-middle">Vocabulary and Fluency</td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Recording</a></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Slides</a></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Homework</a></td>
                        </tr>
                        <tr>
                            <td class="align-middle">8</td>
                            <td class="align-middle"><img class="rounded-circle imgmr-1" style="height:50px;" src="{{ asset('images/max.png') }}" alt="" title="" /> Max Smith</td>
                            <td class="align-middle">2/5/22</td>
                            <td class="align-middle">Vocabulary and Fluency</td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Recording</a></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Slides</a></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Homework</a></td>
                        </tr>
                        <tr>
                            <td class="align-middle">9</td>
                            <td class="align-middle"><img class="rounded-circle imgmr-1" style="height:50px;" src="{{ asset('images/max.png') }}" alt="" title="" /> Max Smith</td>
                            <td class="align-middle">2/5/22</td>
                            <td class="align-middle">Vocabulary and Fluency</td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Recording</a></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Slides</a></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Homework</a></td>
                        </tr>
                        <tr>
                            <td class="align-middle">10</td>
                            <td class="align-middle"><img class="rounded-circle imgmr-1" style="height:50px;" src="{{ asset('images/max.png') }}" alt="" title="" /> Max Smith</td>
                            <td class="align-middle">2/5/22</td>
                            <td class="align-middle">Vocabulary and Fluency</td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Recording</a></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Slides</a></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Homework</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="topSection px-4 py-4 bgWhite">
            <h2 class="mb-4">Upcoming Lessons (4)</h2>
            <div class="mt-4 upcomingLessons">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Wed, May 04, 9:00-11:30AM</h4>
                    </div>
                    <div class="col-md-6 txtRight">
                        <a class="editLess" href="#"><i class="fa fa-edit"></i>Edit Lesson</a>
                    </div>
                </div>
                <div class="mt-1 px-2 py-2 btmSecLesson">
                    <div class="teacherInfo">
                        <img class="rounded-circle imgmr-1" style="height:50px;"  src="{{ asset('images/collins.png') }}" alt="" title="" />
                        <p class="brdrRight">Sean Collins</p>
                        <p class="textSecPnl">Certified English Teacher</p>
                    </div>
                    <div class="mt-4 px-3 py-3">
                        <div style="display:flex">
                            <img class="imgmr-1" style="height:21px;"  src="{{ asset('images/playbutton.png') }}" alt="" title="" />
                            <p><strong>Grammer</strong></p>
                        </div>
                        <div class="txtSmall">
                            <p>To-Do List</p>
                            <div class="brdrGray mb-1">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="Article">
                                    <label class="form-check-label" for="Article">Article for lesson (Name of article)</label>
                                </div>
                            </div>
                            <div class="brdrGray">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="Article">
                                    <label class="form-check-label" for="Article">Article for lesson (Name of article)</label>
                                </div>
                            </div>
                        </div>
                        <div class="btnsBtmSec pull-right" style="display:flex">
                            <a class="timeLeft" href="">1:05 Left</a>
                            <a class="enterLesson" href="">Enter Lesson</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 upcomingLessons">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Wed, May 04, 9:00-11:30AM</h4>
                    </div>
                    <div class="col-md-6 txtRight">
                        <a class="editLess" href="#"><i class="fa fa-edit"></i>Edit Lesson</a>
                    </div>
                </div>
                <div class="mt-1 px-2 py-2 btmSecLesson">
                    <div class="teacherInfo">
                        <img class="rounded-circle imgmr-1" style="height:50px;"  src="{{ asset('images/collins.png') }}" alt="" title="" />
                        <p class="brdrRight">Sean Collins</p>
                        <p class="textSecPnl">Certified English Teacher</p>
                    </div>
                    <div class="mt-4 px-3 py-3">
                        <div style="display:flex">
                            <img class="imgmr-1" style="height:21px;"  src="{{ asset('images/playbutton.png') }}" alt="" title="" />
                            <p><strong>Grammer</strong></p>
                        </div>
                        <div class="txtSmall">
                            <p>To-Do List</p>
                            <div class="brdrGray mb-1">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="Article">
                                    <label class="form-check-label" for="Article">Article for lesson (Name of article)</label>
                                </div>
                            </div>
                            <div class="brdrGray">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="Article">
                                    <label class="form-check-label" for="Article">Article for lesson (Name of article)</label>
                                </div>
                            </div>
                        </div>
                        <div class="btnsBtmSec pull-right" style="display:flex">
                            <a class="timeLeft" href="">1:05 Left</a>
                            <a class="enterLesson" href="">Enter Lesson</a>
                        </div>
                    </div>
                </div>
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
        searching: false
        
    });
});
</script>
@endsection