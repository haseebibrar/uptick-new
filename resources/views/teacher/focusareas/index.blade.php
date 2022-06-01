@extends('layouts.auth')

@section('content')
    <div class="col-md-10">
        <h1 class="txtLeft">Lesson's Material</h1>
        @if ($message = Session::get('success'))
            <div class="alert alert-success" id="msgSuccess">
                <p>{{ $message }}</p>
            </div>
        @endif
        <a class="btn btnGreen" href="/teacher/lessons-material/add">Add New</a>
        <div class="table-responsive mt-4">
            <table class="table table-striped table-bordered" id="myDataTable">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Focus Area</th>
                        <th scope="col">Lesson Subject</th>
                        <th scope="col">Embeded Url</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($focusareas as $focusarea)
                        @php
                            //dd($focusarea);
                        @endphp
                       <tr>
                            <td class="font-weight-bold"></td>
                            <td>{{$focusarea->focusarea}}</td>
                            <td>{{$focusarea->lesson}}</td>
                            <td>{{$focusarea->embeded_url}}</td>
                            <td class="text-nowrap">
                                <a href="/teacher/lessons-material/edit/{{$focusarea->id}}" class="btn btn-info mr-3"><i class="fa fa-edit"></i> Edit</a>
                                <a href="javascript:void(0)" data-id="{{$focusarea->id}}" class="btn btnDel btn-danger"><i class="fa fa-trash"></i> Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#myDataTable').DataTable({
                pageLength: 20,
                lengthMenu: [
                    [20, 50, 100, 500],
                    [20, 50, 100, 500]
                ],
                "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                    $('td:eq(0)', nRow).html(iDisplayIndexFull +1);
                }
            });
            $(document).off('click', '.btnDel').on('click', '.btnDel', function(){
                if(confirm("Are you sure you want to delete this?")){
                    myID = $(this).attr('data-id');
                    window.location = "/teacher/lessons-material/delete/"+myID;
                }
                else{
                    return false;
                }
            });
            $('#msgSuccess').delay(3000).fadeOut('slow');
        });
    </script>
@endsection