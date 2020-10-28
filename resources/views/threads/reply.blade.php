{{--
<br>
<reply :attributes="{{$reply}}" inline-template v-cloak>

    <div id="reply-{{$reply->id}}" class="card">

        <a href="/profiles/{{$reply->owner->name}}" class="flex">
        <div class="card-header">{{ $reply->owner->name }} said {{ $thread->created_at->diffForHumans() }}
        </a>

        @if(\Illuminate\Support\Facades\Auth::check())
        <favorite :reply="{{$reply}}"></favorite>
--}}
{{--        <form action="/replies/{{$reply->id}}/favorites" method="post">
            @csrf
            <button type="submit" class=" btn btn-primary"{{$reply->isFavorited() ? 'disabled' : ''}}>
                {{$reply->favorites_count}} {{str_plural('Favorite', $reply->favorites_count)}}
            </button>
        </form>--}}{{--

        @endif
        </div>

        <div class="card-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model = "body" >{{$reply->body}}</textarea>
                </div>

                <button class="btn btn-xs btn-dark" @click="update">Update</button>
                <button class="btn btn-xs btn-warning" @click="editing = false">Cancel</button>
            </div>
            <div v-else v-text="body">
--}}
{{--
                {{$reply->body}}
--}}{{--

            </div>
        </div>

        @can('update', $reply)
        <div class="panel-footer level">
            <button class="btn btn-info btn-xs mr-1"@click="editing = true">Edit</button>
            <button class="btn btn-danger btn-xs mr-1"@click="destroy">Delete</button>
--}}
{{--            <form method="post" action="/replies/{{$reply->id}}">
                @csrf
                {{method_field('DELETE')}}
                <button class="btn btn-danger btn-xs " type="submit">Delete</button>
            </form>--}}{{--

        </div>
        @endcan

    </div>

</reply>
--}}
