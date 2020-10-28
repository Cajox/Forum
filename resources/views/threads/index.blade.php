@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Form Threads') }}</div>

                    <div class="card-body">

                        @forelse($threads as $thread)
                            <article>

                                <div class="level">

                                <h4 class="flex">
                                    <a href="{{route('show.thread', [$thread->channel->slug ,$thread->id])}}">

                                        @if(Auth::check() && $thread->hasUpdatesFor(Auth::user()))

                                            <strong>

                                                {{$thread->title}}

                                            </strong>

                                        @else

                                            {{$thread->title}}


                                        @endif

                                    </a>
                                </h4>

                                <a href="{{$thread->path()}}">{{$thread->replies_count}} {{str_plural('reply', $thread->replies_count)}}</a>

                                </div>
                                <div class="body">{{$thread->body}}</div>
                            </article>
                            <hr>
                        @empty
                            <p>There are no relevant relevent result at this time</p>
                        @endforelse

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection