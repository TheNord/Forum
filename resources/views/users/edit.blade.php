@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-header">
            <h1>Edit profile</h1>
        </div>

        @include('partials.error')

        <div class="page-body">
            <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
                </div>

                <img src="{{ asset("storage/$user->avatar_path") }}" alt="avatar" class="img-fluid" style="height: 100px;">
                <div class="form-group">
                    <label for="avatar">Avatar</label>
                    <input type="file" class="form-control" id="avatar" name="avatar" placeholder="">
                </div>

                <button type="submit" class="btn btn-success">Upload</button>
            </form>
        </div>
    </div>
@endsection
