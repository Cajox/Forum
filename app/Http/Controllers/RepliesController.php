<?php

namespace App\Http\Controllers;

use App\Http\Forms\CreatePostForm;
use App\Models\Reply;
use App\Models\Thread;
use App\Inspections\Spam;
use App\Models\User;
use App\Notifications\YouWereMentioned;
use App\Rules\SpamFree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Gate;

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
    public function store($channelId, Thread $thread, CreatePostForm $form){

        if (Gate::denies('create', new Reply)){

            return response('Take a break',422);

        }

        $this->authorize('create', new Reply());

//            $this->validateReply();

        $reply = $form->persist($thread);

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
