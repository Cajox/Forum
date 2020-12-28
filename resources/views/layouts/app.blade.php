<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        body {padding-bottom: 100px;}
        .level{display: flex; align-items: center;}
        .flex{flex: 1;}
        [v-cloak] {display: none;}
    </style>
</head>
<body style="padding-bottom: 100px">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>

                <div class="dropdown">
                    <a href="#" class="dropdown-toggle" role="button" type="button" id="" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded = "false"> Browse <span class="caret"></span> </a>

                    <ul class="dropdown-menu">
                        <li><a  href="{{ route('all.thread') }}">All Threads </a></li>
                        @if(\Illuminate\Support\Facades\Auth::check())
                        <li><a class="" href="{{route('all.thread')}}?by={{Auth::user()->name}}">My Threads </a></li>
                        @endif

                        <li><a  href="/threads?popular=1">Popular Threads </a></li>

                    </ul>
                </div>

                <a class="navbar-brand" href="{{ route('thread.create') }}">
                    New Threads
                </a>

                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Channels
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    @foreach($channels as $channel)
                        <a class="dropdown-item" href="{{$channel->slug}}">{{$channel->name}}</a>
                        @endforeach

                    </div>
                </div>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            @if(Auth::user()->notifications->isNotEmpty())
                            <li class="dropdown">

                                <a href="" class="dropdown-toggle" data-toggle="dropdown">
                                    Notifications <span class="glyphicon glyphicon-bell"></span>
                                </a>

                                <ul class="dropdown-menu">

                                    <li>
                                        @foreach(Auth::user()->notifications as $notification)
                                            <a href="{{$notification->data['link']}}">
                                                {{$notification->data['message']}}
                                            </a>
                                            <br>
                                        @endforeach
                                    </li>

                                </ul>

                            </li>
                            @endif
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <div class="nav-link dropdown">
                                    <a href="{{route('profile', Auth::user()->name)}}">My profile</a>
                                    </div>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
            <flash message="{{session('flash')}}"></flash>
        </main>
    </div>
</body>
</html>
<script>

</script>
