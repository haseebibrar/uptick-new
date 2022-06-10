@extends('layouts.auth')

@section('content')
    <div class="col-md-11">
        <div class="topSection tblLessonPnl px-4 py-4 bgWhite">
            <h2 class="mb-4">Homework</h2>
            <h4 class="mb-4">PLease answer the questions below:</h4>
            <div class="row">
                @php
                    $counter = 1;
                @endphp
                @foreach ($myHomeWork as $data)
                    <div class="col-md-6 ">
                        <div class="myQuesDiv mb-4">
                            <h5>{{ $counter }}. {{ $data->question }}</h5>
                            @php
                                $countIn = 1;
                                $answers = json_decode($data->answer);
                            @endphp
                            @foreach($answers as $answer)
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="answer{{ $data->id }}" id="data-{{ $data->id.$countIn }}">
                                    <label class="form-check-label" for="data-{{ $data->id.$countIn }}">{{ $answer }}</label>
                                </div>
                                @php
                                    $countIn++;
                                @endphp
                            @endforeach
                        </div>
                    </div>
                    @php
                        $counter++;
                    @endphp
                @endforeach
                {{-- <div class="col-md-6 ">
                    <div class="myQuesDiv mb-4">
                        <h5>1. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua?</h5>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="flexRadioDefault" id="data-1">
                            <label class="form-check-label" for="data-1">Possible answer</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="flexRadioDefault" id="data-2">
                            <label class="form-check-label" for="data-2">Possible answer</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="flexRadioDefault" id="data-3">
                            <label class="form-check-label" for="data-3">Possible answer</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="flexRadioDefault" id="data-4">
                            <label class="form-check-label" for="data-4">Possible answer</label>
                        </div>
                    </div>

                    <div class="myQuesDiv mb-4">
                        <h5>2. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua?</h5>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="flexRadioDefault" id="data-1">
                            <label class="form-check-label" for="data-1">Possible answer</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="flexRadioDefault" id="data-2">
                            <label class="form-check-label" for="data-2">Possible answer</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="flexRadioDefault" id="data-3">
                            <label class="form-check-label" for="data-3">Possible answer</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="flexRadioDefault" id="data-4">
                            <label class="form-check-label" for="data-4">Possible answer</label>
                        </div>
                    </div>

                    <div class="myQuesDiv mb-4">
                        <h5>3. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua?</h5>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="flexRadioDefault" id="data-1">
                            <label class="form-check-label" for="data-1">Possible answer</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="flexRadioDefault" id="data-2">
                            <label class="form-check-label" for="data-2">Possible answer</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="flexRadioDefault" id="data-3">
                            <label class="form-check-label" for="data-3">Possible answer</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="flexRadioDefault" id="data-4">
                            <label class="form-check-label" for="data-4">Possible answer</label>
                        </div>
                    </div>

                    <div class="myQuesDiv mb-4">
                        <h5>4. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua?</h5>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="flexRadioDefault" id="data-1">
                            <label class="form-check-label" for="data-1">Possible answer</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="flexRadioDefault" id="data-2">
                            <label class="form-check-label" for="data-2">Possible answer</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="flexRadioDefault" id="data-3">
                            <label class="form-check-label" for="data-3">Possible answer</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="flexRadioDefault" id="data-4">
                            <label class="form-check-label" for="data-4">Possible answer</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="myQuesDiv mb-4">
                        <h5>5. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua?</h5>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="flexRadioDefault" id="data-1">
                            <label class="form-check-label" for="data-1">Possible answer</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="flexRadioDefault" id="data-2">
                            <label class="form-check-label" for="data-2">Possible answer</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="flexRadioDefault" id="data-3">
                            <label class="form-check-label" for="data-3">Possible answer</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="flexRadioDefault" id="data-4">
                            <label class="form-check-label" for="data-4">Possible answer</label>
                        </div>
                    </div>

                    <div class="myQuesDiv mb-4">
                        <h5>6. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua?</h5>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="flexRadioDefault" id="data-1">
                            <label class="form-check-label" for="data-1">Possible answer</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="flexRadioDefault" id="data-2">
                            <label class="form-check-label" for="data-2">Possible answer</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="flexRadioDefault" id="data-3">
                            <label class="form-check-label" for="data-3">Possible answer</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="flexRadioDefault" id="data-4">
                            <label class="form-check-label" for="data-4">Possible answer</label>
                        </div>
                    </div>

                    <div class="myQuesDiv mb-4">
                        <h5>7. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua?</h5>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="flexRadioDefault" id="data-1">
                            <label class="form-check-label" for="data-1">Possible answer</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="flexRadioDefault" id="data-2">
                            <label class="form-check-label" for="data-2">Possible answer</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="flexRadioDefault" id="data-3">
                            <label class="form-check-label" for="data-3">Possible answer</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="flexRadioDefault" id="data-4">
                            <label class="form-check-label" for="data-4">Possible answer</label>
                        </div>
                    </div>

                    <div class="myQuesDiv mb-4">
                        <h5>8. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua?</h5>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="flexRadioDefault" id="data-1">
                            <label class="form-check-label" for="data-1">Possible answer</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="flexRadioDefault" id="data-2">
                            <label class="form-check-label" for="data-2">Possible answer</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="flexRadioDefault" id="data-3">
                            <label class="form-check-label" for="data-3">Possible answer</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" name="flexRadioDefault" id="data-4">
                            <label class="form-check-label" for="data-4">Possible answer</label>
                        </div>
                    </div>
                </div> --}}
            </div>
            <div class="btnsBtmSec pull-right" style="display:flex">
                <a class="btnStndrd btnGreen mr-2" href="/past-future-lesson">Save</a>
                <a class="btnStndrd btnGray" href="/past-future-lesson">Cancel</a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    
});
</script>
@endsection