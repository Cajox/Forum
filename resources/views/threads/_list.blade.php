@forelse($threads as $thread)
    <article>

        <div class="level">
            <div class="flex">
                <h4>
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
                <h5>
                    Posted by: <a href="{{route('profile', $thread->creator)}}">{{$thread->creator->name}}</a>
                </h5>
            </div>
            <a href="{{$thread->path()}}">{{$thread->replies_count}} {{str_plural('reply', $thread->replies_count)}}</a>

        </div>
        <div class="body">{{$thread->body}}</div>
    </article>
    <hr>
@empty
    <p>There are no relevant relevent result at this time</p>
@endforelse
