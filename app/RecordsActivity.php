<?php


namespace App;


use App\Models\Activity;
use App\Models\Thread;
use Illuminate\Support\Facades\Auth;

 trait RecordsActivity
{

     protected static function bootRecordsActivity(){

         if (Auth::guest()) return;

         foreach (static::getActivitiesToRecord() as $event){

             static::$event(function ($model) use ($event){

                 $model->recordActivity($event);

             });
         }

         static::deleting(function ($model){

             $model->activity()->delete();

         });
/*
         static::created(function ($thread){

             $thread->recordActivity('created');

         });*/


     }

     protected static function getActivitiesToRecord(){

         return ['created'];

     }

    public function getActivityType($event)
    {

        $type = strtolower((new \ReflectionClass($this))->getShortName()); //thread

        return $event . '_' . $type;

    }

    protected function recordActivity($event)
    {

        $this->activity($event)->create([

            'user_id' => Auth::id(),
            'type' => $this->getActivityType($event)
        ]);

    }

    public function activity(){

         return $this->morphMany(Activity::class, 'subject');

    }
}
