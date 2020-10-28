<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserNotificationsController extends Controller
{

    public function __construct(){

        $this->middleware('auth');

    }

    public function index(){

        return Auth::user()->unreadNotifications;

    }

    public function destroy(User $user, $notificationId){

        Auth::user()->notifications()->findOrFail($notificationId)->markAsRead();

    }

}
