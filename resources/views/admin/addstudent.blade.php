@extends('layouts.auth')

@section('content')
    @php
        //dd($myCompID);
    @endphp
    <div class="col-md-4">
        <h1 class="txtLeft">Add Student</h1>
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
        <form class="mt-4 mb-4" method="POST" action="/admin/students/add" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="company_id" value="{{ $myCompID }}">
            <div class="row mb-4">
                <div class="col-md-6"><label for="name">Name</label></div>
                <div class="col-md-6">
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6"><label for="name">Email</label></div>
                <div class="col-md-6">
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6"><label for="name">Phone</label></div>
                <div class="col-md-6">
                    <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6"><label for="name">ID</label></div>
                <div class="col-md-6">
                    <input type="tel" name="roll_num" class="form-control" value="{{ old('roll_num') }}">
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6"><label for="name">Title</label></div>
                <div class="col-md-6">
                    <input type="tel" name="title" class="form-control" value="{{ old('title') }}">
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6"><label for="expertise">Department</label></div>
                <div class="col-md-6">
                    <select name="dept_id" class="form-control">
                        <option value="0">Please Select</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6"><label for="image">Image</label></div>
                <div class="col-md-6">
                    <input type="file" name="image" class="form-control" value="{{ old('image') }}">
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6"><label for="password">Password</label></div>
                <div class="col-md-6">
                    <input type="password" name="password" class="form-control" value="{{ old('password') }}" required>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6"><label for="password_confirmation ">Confirm Password</label></div>
                <div class="col-md-6">
                    <input type="password" name="password_confirmation" class="form-control" value="{{ old('password_confirmation') }}" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btnGreen">Save</button>
            @php
                if($myCompID > 0)
                    echo '<a class="btn btn-light" href="/admin">Cancel</a>';
                else
                    echo '<a class="btn btn-light" href="/admin/students/">Cancel</a>';
            @endphp
            
        </form>
    </div>
    <div class="col-md-6">
    </div>
@endsection