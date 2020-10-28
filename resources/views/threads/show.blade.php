@extends('layouts.app')

@section('content')
    <thread-view :initial-replies-count="{{$thread->replies_count}}" inline-template>
    <div class="container">
        <div class="row">
            <div class="col-md-8">

                <div class="card">
                    <div class="card-header"><a href="/profiles/{{$thread->creator->name}}">{{$thread->creator->name}}</a> posted:{{ $thread->title }}</div>
                    <div class="card-body">
                        {{$thread->body}}
                    </div>

                    @can('update', $thread)
                    <form method="post" action="{{$thread->path()}}">
                        @csrf
                        {{method_field('DELETE')}}

                        <button type="submit" class="flex btn btn-danger">Delete</button>
                    </form>
                     @endcan
                </div>
                <br><br>

                <replies :data="{{$thread->replies}}" @removed="repliesCount--" ></replies>

                <hr>
{{--
                {{$replies->links("pagination::bootstrap-4")}}
--}}


                <br>
                <br>

                @if(\Illuminate\Support\Facades\Auth::check())

                    <form method="post" action="{{route('reply.store', [$thread->channel->id, $thread->id ])}}">
                        @csrf
                        <textarea name="body" id="body" class="form-control" placeholder="Reply something" cols="30" rows="5"></textarea>
                        <input type="submit">
                    </form>

                @endif

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

            <div class="col-md-4">

                <div class="card">
                    <div class="card-body">

                        <p>This thread was published : {{$thread->created_at->diffForHumans()}}</p>
                        <a href="#">Creator : {{$thread->creator->name}}</a> and has
                        <span v-text="repliesCount"> </span>  {{str_plural('comment', $thread->replies_count)}}

                    </div>

                    <div>

                        @if(!$thread->isSubscribedTo)
                            @if($thread->user_id != Auth::id())
                                <form method="post" action="{{url($thread->path() . '/subscriptions')}}">
                                    @csrf
                                    <input type="submit" value="Subscribe" class="btn btn-dark">
                                </form>
                            @endif
                        @else
                        <form method="post" action="{{url($thread->path() . '/subscriptions')}}">
                            @csrf
                            {{method_field('DELETE')}}
                            <input type="submit" value="Unsubscribe" class="btn btn-dark">

                        </form>
                        @endif
                    </div>
                </div>

            </div>
        </div>
        <br>




    </div>
    </thread-view>

@endsection
