@extends('layouts.auth')

@section('content')
    <div class="col-md-4">
        <h1 class="txtLeft">Add Company</h1>
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
        <form class="mt-4 mb-4" method="POST" action="/admin/companies/add" enctype="multipart/form-data">
            @csrf
            <div class="row mb-4">
                <div class="col-md-6"><label for="name">Name</label></div>
                <div class="col-md-6">
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6"><label for="name">Allocate Hours</label></div>
                <div class="col-md-6"><input type="number" name="bank_hours" class="form-control" value="{{ old('bank_hours') }}"></div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6"><label for="name">Phone</label></div>
                <div class="col-md-6">
                    <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
            </div>
            <button type="submit" class="btn btn-primary btnGreen">Save</button>
            <a class="btn btn-light" href="/admin/companies/">Cancel</a>
        </form>
    </div>
    <div class="col-md-6">
    </div>
@endsection