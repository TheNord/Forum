@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create a new thread</div>
                    <div class="card-body">
                        <article>
                            <div class="body">
                                <form action="{{ route('threads.store') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="channel_id">Select a channel</label>
                                        <select name="channel_id" id="channel_id" class="form-control" required>
                                            @foreach($channels as $channel)
                                                <option value="{{ $channel->id }}"{{ old('channel_id') == $channel->id ? ' selected' : ''}}>
                                                    {{ $channel->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('channel_id'))
                                            <span class="invalid-feedback"><strong>{{ $errors->first('channel_id') }}</strong></span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text"
                                               class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                               id="title" name="title" placeholder="" required>
                                        @if ($errors->has('title'))
                                            <span class="invalid-feedback"><strong>{{ $errors->first('title') }}</strong></span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="body">Content</label>
                                        <textarea name="body" id="body"
                                                  class="form-control{{ $errors->has('body') ? ' is-invalid' : '' }}" required></textarea>
                                        @if ($errors->has('body'))
                                            <span class="invalid-feedback"><strong>{{ $errors->first('body') }}</strong></span>
                                        @endif
                                    </div>
                                    <button type="submit" class="btn btn-success">Create</button>
                                </form>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
