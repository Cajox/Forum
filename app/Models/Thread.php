<?php

namespace App\Models;

use App\Events\ThreadHasNewReply;
use App\Notifications\ThreadWasUpdated;
use App\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Thread extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribedTo'];

    public $table = "threads";

    use HasFactory;


    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

/*        static::addGlobalScope('replyCount', function ($builder){

            $builder->withCount('replies');

        });*/

        static::deleting(function ($thread){

            $thread->replies->each->delete();

/*            $thread->replies->each(function ($reply){

                $reply->delete();

            });*/

        });


    }

    public function path(){

        return '/threads/' . $this->channel->slug . '/' . $this->id;
    }

    public function replies(){

        return $this->hasMany(Reply::class);

    }

/*    public function getReplyCountAttribute(){

        return $this->replies()->count();

    }*/

    public function creator(){

        return $this->belongsTo(User::class, 'user_id');

    }

    public function addReply($reply){

        $reply = $this->replies()->create($reply);

        $this->notifySubscribers($reply);

        return $reply;

// working process 1
/*        $this->subscriptions

            ->where('user_id', '==', $reply->user_id)

            ->each
            ->notify($reply);
            */

// working process 2 , ex 2
/*
 *         ->each->notify($reply);

*/

// working process 3
 /*         foreach ($this->subscriptions as $subscription){

            if ($subscription->user_id != $reply->user_id){

                $subscription->user->notify(new ThreadWasUpdated($this, $reply));

            }

        }*/


    }

    public function channel(){

        return $this->belongsTo(Channel::class);

    }

    public function scopeFilter($query , $filters){

        return $filters->apply($query);

    }

    public function subscribe($userId = null){

        if ($this->user_id == Auth::id()){

            return redirect()->back();

        }

        $this->subscriptions()->create([

            'user_id' => $userId ?: Auth::id()

        ]);

        return $this;

    }

    public function unsubscribe($userId = null){

        $this->subscriptions()
            ->where('user_id', $userId ?: Auth::id())
            ->delete();

    }

    public function subscriptions(){

        return $this->hasMany(ThreadSubscription::class);

    }

    public function getIsSubscribedToAttribute(){

        return $this->subscriptions()
            ->where('user_id', Auth::id())
            ->exists();

    }

    public function notifySubscribers($reply){

        $this->subscriptions
            ->where('user_id', '!=', $reply->user_id)
            ->each
            ->notify($reply);

    }

    public function hasUpdatesFor($user){

//      $key = sprintf("users.%s.visits.%s", Auth::id(), $this->id);

        $key = $user->visitedThreadCacheKey($this);

        return $this->updated_at > cache($key);

    }

}
