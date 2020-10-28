<?php
namespace App;

use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

trait Favoritable{

    protected static function bootFavoritable(){

        static::deleting(function ($model){

            $model->favorites->each->delete();

        });

    }

    public function favorites(){

        return $this->morphMany(Favorite::class, 'favorited');

    }

    public function favorite(){

        if (!$this->favorites()->where(['user_id' => Auth::id()])->exists()){

            return $this->favorites()->create(['user_id' => Auth::id()]);

        }

    }

    public function isFavorited(){

        return !! $this->favorites->where('user_id', Auth::id())->count();

    }

    public function getIsFavoritedAttribute(){

        return $this->isFavorited();

    }

    public function getFavoritesCountAttribute(){

        return $this->favorites->count();

    }

    public function unfavorite(){

        $attributes = ['user_id' => Auth::id()];

/*        $this->favorites()->where($attributes)->get()->each(function ($favorite){

            $favorite->delete();

        });*/

        $this->favorites()->where($attributes)->get()->each->delete();

    }

}
