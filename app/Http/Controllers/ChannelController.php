<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Thread;
use Illuminate\Http\Request;

class ChannelController extends Controller
{

/*    public function index($channelSlug){

        $channelId = Channel::where('slug', $channelSlug)->first()->id;
        $threads = Thread::where('channel_id', $channelId)->latest()->get();

        return view('threads.index', compact('threads'));

    }*/
}
