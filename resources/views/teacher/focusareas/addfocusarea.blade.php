@extends('layouts.auth')

@section('content')
    <div class="col-md-4">
        <div class="topSection px-4 py-4 bgWhite">
            <h1 class="txtLeft">Upload lesson's materials</h1>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form class="mt-4 mb-4" method="POST" action="/teacher/lessons-material/add" enctype="multipart/form-data">
                @csrf
                <div class="row mb-4">
                    <div class="col-md-6"><label for="focusarea_id">Focus Area</label></div>
                    <div class="col-md-6">
                        <select name="focusarea_id" id="focusarea_id" class="form-control">
                            <option value="0">Please Select</option>
                            @foreach($focusareas as $focusarea)
                                <option value="{{ $focusarea->id }}">{{ $focusarea->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-6"><label for="lesson_id">Lesson's Subject</label></div>
                    <div class="col-md-6">
                        <select name="lesson_id" id="lesson_id" class="form-control"></select>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-6"><label for="embeded_url">Embeded URL</label></div>
                    <div class="col-md-6">
                        <input type="text" name="embeded_url" class="form-control" value="{{ old('embeded_url') }}" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btnGreen">Save</button>
                <a class="btn btn-light" href="/teacher/lessons-material/">Cancel</a>
            </form>
        </div>
    </div>
    <div class="col-md-6">
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $(document).off('change', '#focusarea_id').on('change', '#focusarea_id', function(){
                //alert($(this).val());
                var myFocusID = $(this).val();
                $.ajax({
                    url: "{{url('/')}}/teacher/lessons-material/get-lessons",
                    type:'POST',
                    data: {_token:"{{ csrf_token() }}", myFocusID:myFocusID},
                    success: function(data) {
                        console.log(data);
                        $('#lesson_id').html(data);
                    }
                });
            });
            $('#msgSuccess').delay(3000).fadeOut('slow');
        });
    </script>
@endsection