<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\Thread;
use App\Inspections\Spam;
use App\Rules\SpamFree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class RepliesController extends Controller
{

    public function __construct(){

        $this->middleware('auth');

    }

    /**
     * @param $channelId
     * @param Thread $thread
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($channelId, Thread $thread){

        try {

            $this->validateReply();

            $reply = $thread->addReply([

                'body' => request('body'),
                'user_id' => Auth::id(),

            ]);

        } catch (Exception $e){

            return response('This is spam',422);

        }

        if (\request()->expectsJson()){

            return $reply->load('owner');

        }

        return back()->with('flash', 'Your Reply Has been published');

    }

    public function destroy(Reply $reply){

        $this->authorize('update', $reply);

        $reply->delete();

        if (\request()->expectsJson()){

            return response(['status' => 'Reply deleted']);

        }

        return back();

    }

    public function update(Reply $reply){

        try {

            $this->authorize('update', $reply);

            $this->validateReply();

            $reply->update(['body' => \request('body')]);

        } catch (\Exception $e){

            return response('This is spam',422);

        }

    }

    public function validateReply(){

        $this->validate(\request(), ['body' => ['required', new SpamFree]]);
/*        resolve(Spam::class)->detect(\request('body'));*/

    }

}
