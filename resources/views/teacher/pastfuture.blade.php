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
                            <td class="text-nowrap align-middle"><button type="button" class="btn linkBlue video-btn" data-toggle="modal" data-src="https://player.vimeo.com/video/58385453?badge=0&autoplay=1&loop=1" data-target="#myModal">Recording</button></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Slides</a></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="/homework">Homework</a></td>
                        </tr>
                        <tr>
                            <td class="align-middle">2</td>
                            <td class="align-middle"><img class="rounded-circle imgmr-1" style="height:50px;" src="{{ asset('images/max.png') }}" alt="" title="" /> Max Smith</td>
                            <td class="align-middle">2/5/22</td>
                            <td class="align-middle">Vocabulary and Fluency</td>
                            <td class="text-nowrap align-middle"><button type="button" class="btn linkBlue video-btn" data-toggle="modal" data-src="https://player.vimeo.com/video/58385453?badge=0&autoplay=1&loop=1" data-target="#myModal">Recording</button></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Slides</a></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="/homework">Homework</a></td>
                        </tr>
                        <tr>
                            <td class="align-middle">3</td>
                            <td class="align-middle"><img class="rounded-circle imgmr-1" style="height:50px;" src="{{ asset('images/max.png') }}" alt="" title="" /> Max Smith</td>
                            <td class="align-middle">2/5/22</td>
                            <td class="align-middle">Vocabulary and Fluency</td>
                            <td class="text-nowrap align-middle"><button type="button" class="btn linkBlue video-btn" data-toggle="modal" data-src="https://player.vimeo.com/video/58385453?badge=0&autoplay=1&loop=1" data-target="#myModal">Recording</button></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Slides</a></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="/homework">Homework</a></td>
                        </tr>
                        <tr>
                            <td class="align-middle">4</td>
                            <td class="align-middle"><img class="rounded-circle imgmr-1" style="height:50px;" src="{{ asset('images/max.png') }}" alt="" title="" /> Max Smith</td>
                            <td class="align-middle">2/5/22</td>
                            <td class="align-middle">Vocabulary and Fluency</td>
                            <td class="text-nowrap align-middle"><button type="button" class="btn linkBlue video-btn" data-toggle="modal" data-src="https://player.vimeo.com/video/58385453?badge=0&autoplay=1&loop=1" data-target="#myModal">Recording</button></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Slides</a></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="/homework">Homework</a></td>
                        </tr>
                        <tr>
                            <td class="align-middle">5</td>
                            <td class="align-middle"><img class="rounded-circle imgmr-1" style="height:50px;" src="{{ asset('images/max.png') }}" alt="" title="" /> Max Smith</td>
                            <td class="align-middle">2/5/22</td>
                            <td class="align-middle">Vocabulary and Fluency</td>
                            <td class="text-nowrap align-middle"><button type="button" class="btn linkBlue video-btn" data-toggle="modal" data-src="https://player.vimeo.com/video/58385453?badge=0&autoplay=1&loop=1" data-target="#myModal">Recording</button></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Slides</a></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="/homework">Homework</a></td>
                        </tr>
                        <tr>
                            <td class="align-middle">6</td>
                            <td class="align-middle"><img class="rounded-circle imgmr-1" style="height:50px;" src="{{ asset('images/max.png') }}" alt="" title="" /> Max Smith</td>
                            <td class="align-middle">2/5/22</td>
                            <td class="align-middle">Vocabulary and Fluency</td>
                            <td class="text-nowrap align-middle"><button type="button" class="btn linkBlue video-btn" data-toggle="modal" data-src="https://player.vimeo.com/video/58385453?badge=0&autoplay=1&loop=1" data-target="#myModal">Recording</button></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Slides</a></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="/homework">Homework</a></td>
                        </tr>
                        <tr>
                            <td class="align-middle">7</td>
                            <td class="align-middle"><img class="rounded-circle imgmr-1" style="height:50px;" src="{{ asset('images/max.png') }}" alt="" title="" /> Max Smith</td>
                            <td class="align-middle">2/5/22</td>
                            <td class="align-middle">Vocabulary and Fluency</td>
                            <td class="text-nowrap align-middle"><button type="button" class="btn linkBlue video-btn" data-toggle="modal" data-src="https://player.vimeo.com/video/58385453?badge=0&autoplay=1&loop=1" data-target="#myModal">Recording</button></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Slides</a></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="/homework">Homework</a></td>
                        </tr>
                        <tr>
                            <td class="align-middle">8</td>
                            <td class="align-middle"><img class="rounded-circle imgmr-1" style="height:50px;" src="{{ asset('images/max.png') }}" alt="" title="" /> Max Smith</td>
                            <td class="align-middle">2/5/22</td>
                            <td class="align-middle">Vocabulary and Fluency</td>
                            <td class="text-nowrap align-middle"><button type="button" class="btn linkBlue video-btn" data-toggle="modal" data-src="https://player.vimeo.com/video/58385453?badge=0&autoplay=1&loop=1" data-target="#myModal">Recording</button></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Slides</a></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="/homework">Homework</a></td>
                        </tr>
                        <tr>
                            <td class="align-middle">9</td>
                            <td class="align-middle"><img class="rounded-circle imgmr-1" style="height:50px;" src="{{ asset('images/max.png') }}" alt="" title="" /> Max Smith</td>
                            <td class="align-middle">2/5/22</td>
                            <td class="align-middle">Vocabulary and Fluency</td>
                            <td class="text-nowrap align-middle"><button type="button" class="btn linkBlue video-btn" data-toggle="modal" data-src="https://player.vimeo.com/video/58385453?badge=0&autoplay=1&loop=1" data-target="#myModal">Recording</button></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Slides</a></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="/homework">Homework</a></td>
                        </tr>
                        <tr>
                            <td class="align-middle">10</td>
                            <td class="align-middle"><img class="rounded-circle imgmr-1" style="height:50px;" src="{{ asset('images/max.png') }}" alt="" title="" /> Max Smith</td>
                            <td class="align-middle">2/5/22</td>
                            <td class="align-middle">Vocabulary and Fluency</td>
                            <td class="text-nowrap align-middle"><button type="button" class="btn linkBlue video-btn" data-toggle="modal" data-src="https://player.vimeo.com/video/58385453?badge=0&autoplay=1&loop=1" data-target="#myModal">Recording</button></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="#">Slides</a></td>
                            <td class="text-nowrap align-middle"><a class="linkBlue" href="/homework">Homework</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="topSection tblLessonPnlRight px-4 py-4 bgWhite">
            <h2 class="mb-4">Upcoming Lessons (4)</h2>
            <div class="mt-4 upcomingLessons">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Wed, May 04, 9:00-11:30AM</h4>
                    </div>
                    <div class="col-md-6 txtRight">
                        <a class="editLess" href="#"><i class="fa fa-pencil"></i> Edit Lesson</a>
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
                                    <input type="checkbox" class="form-check-input" id="lesson">
                                    <label class="form-check-label" for="lesson">Article for lesson (Name of article)</label>
                                </div>
                            </div>
                        </div>
                        <div class="btnsBtmSec pull-right mt-2">
                            <div class="the-final-countdown"><p></p></div>
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
                        <a class="editLess" href="#"><i class="fa fa-pencil"></i> Edit Lesson</a>
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
                                    <input type="checkbox" class="form-check-input" id="lesson">
                                    <label class="form-check-label" for="lesson">Article for lesson (Name of article)</label>
                                </div>
                            </div>
                        </div>
                        <div class="btnsBtmSec pull-right mt-2">
                            <div class="the-final-countdown"><p></p></div>
                            <a class="enterLesson" href="">Enter Lesson</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal For Playing Video -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="max-height:700px;">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
                    <!-- 16:9 aspect ratio -->
                    <div class="embed-responsive">
                        {{-- <iframe src="" title="" frameborder="0" id="video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> --}}
                        <iframe class="embed-responsive-item" src="" id="video" allowscriptaccess="always" allow="autoplay"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal For Playing Video -->
@endsection

@section('scripts')
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
<script src="https://cdn.rawgit.com/JacobLett/bootstrap4-latest/master/bootstrap-4-latest.min.js"></script>
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
    var $videoSrc; 
    $('.video-btn').click(function() {
        //alert('test');
        $videoSrc = $(this).data( "src" );
    });
    //console.log($videoSrc);
    // when the modal is opened autoplay it  
    $('#myModal').on('shown.bs.modal', function (e) {  
        // set the video src to autoplay and not to show related video. Youtube related video is like a box of chocolates... you never know what you're gonna get
        $("#video").attr('src',$videoSrc + "?autoplay=1&amp;modestbranding=1&amp;showinfo=0" ); 
    });
    // stop playing the youtube video when I close the modal
    $('#myModal').on('hide.bs.modal', function (e) {
        // a poor man's stop video
        $("#video").attr('src', ''); 
    });
});
setInterval(function time(){
    var d = new Date();
    var hours = 24 - d.getHours();
    var min = 60 - d.getMinutes();
    if((min + '').length == 1){
        min = '0' + min;
    }
    var sec = 60 - d.getSeconds();
    if((sec + '').length == 1){
        sec = '0' + sec;
    }
  jQuery('.the-final-countdown p').html(min+':'+sec+' Left')
}, 1000);
</script>
@endsection