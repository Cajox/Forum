@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Create a new Thread') }}</div>
                    <div class="panel-body">
                        <form method="post" action="{{route('thread.store')}}">

                            @csrf

                            <div class="form-group">
                                <label for="title">Chose a channel:</label>
                                <select type="text" name="channel_id" placeholder="title"  class="form-control">
                                    <option value="">Chose one </option>
                                    @foreach($channels as $channel)
                                        <option value="{{$channel->id}}">{{$channel->name}}</option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="form-group">

                                <label for="title">Title:</label>
                                <input type="text" name="title" placeholder="title" value="{{old('title')}}" class="form-control">

                            </div>

                            <div class="form-group">

                                <label for="body">Body:</label>
                                <textarea name="body" id="" class="form-control"></textarea>
                            </div>

                            <input type="submit" name="Publish">

                        </form>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
