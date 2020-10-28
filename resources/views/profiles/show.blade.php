@extends('layouts.app')

@section('content')


    <div class="page-header">
        <h1>
            {{$profileUser->name}}
            <smal>Since: {{$profileUser->created_at->diffForHumans()}}</smal>
        </h1>
    </div>

    @forelse($activities as $date =>$activity)
        <h3 class="text-center page-header">{{$date}}</h3>
        @foreach($activity as $record)
            @if(view()->exists("profiles.activities.{$record->type}"))
                @include("profiles.activities.{$record->type}", ['activity' => $record])
            @endif
        @endforeach
    @empty

        <p>There is no activity</p>

    @endforelse

@endsection
