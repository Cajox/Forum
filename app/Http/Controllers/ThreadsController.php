<?php

namespace App\Http\Controllers;

use App\Inspections\Spam;
use App\Models\Channel;
use App\Filters\ThreadFilters;
use App\Models\Reply;
use App\Models\Thread;
use Barryvdh\Debugbar\Facade as DebugBar;
use Carbon\Carbon;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThreadsController extends Controller
{

    public function __construct(){

        $this->middleware('auth')->except(['index', 'show']);

    }

    public function index(Channel $channel, ThreadFilters $filters)
    {
        $threads = $this->getThreads($filters, $channel);

        if (\request()->wantsJson()){

            return $threads;

        }

        return view('threads.index', compact('threads'));

    }

    public function show($channelId, Thread $thread)
    {

        if (Auth::check()){

            Auth::user()->read($thread);

        }


    /*    $key = sprintf("users.%s.visits.%s", Auth::id(), $thread->id);

        cache()->forever($key, Carbon::now());*/

        return view('threads.show', [

            'thread' => $thread,
            'replies' => $thread->replies()


        ]);

    }

    public function create(){

        return view('threads.create');

    }

    public function store(Request $request, Spam $spam){

        $this->validate($request, [

            'title' => 'required|spamFree',
            'channel_id' => 'required|exists:channels,id|spamFree',
            'body' => 'required'

        ]);

/*        $spam->detect(\request('body'));*/

        $thread = Thread::create([

            'title'=> $request['title'],
            'channel_id'=> $request['channel_id'],
            'body'=> $request['body'],
            'user_id'=> Auth::id(),

        ]);

        return redirect($thread->path())->with('flash', 'Your Thread Has been published');


    }

    /**
     * @param ThreadFilters $filters
     * @param Channel $channel
     * @return mixed
     */
    protected function getThreads(ThreadFilters $filters, Channel $channel)
    {
        $threads = Thread::latest()->filter($filters);

        if ($channel->exists) {

            $threads->where('channel_id', $channel->id);

        }

        $threads = $threads->get();
        return $threads;
    }

    public function destroy($channel, Thread $thread){

/*        $thread->replies()->delete();*/

        $this->authorize('update', $thread);

        if ($thread->user_id != Auth::id()){

            abort(403);
        }

        $thread->delete();

        if (\request()->wantsJson()){

            return response([], 204);

        }

        return redirect('/threads');

    }

    /**
     * @param Channel $channel
     * @return \Illuminate\Database\Eloquent\Collection
     */


}
