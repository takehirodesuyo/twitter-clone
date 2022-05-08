<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer>
    </script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body style="background-color:#EDF7FF;">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <img src="/images/twitter.svg" width="50" height="50">
                <a class="navbar-brand" href="{{ url('/tweets') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav me-4">
                    <!-- Authentication Links -->
                    @guest
                    @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">新規登録</a>
                    </li>
                    @endif
                    @else

                    <div class="nav-item me-3 d-flex align-items-center">
                        @if (session('flash_message'))
                        <div class="flash_message text-primary h3">
                            {{ session('flash_message') }}
                        </div>
                        @endif
                    </div>
                    <div class="nav-item me-3 d-flex align-items-center">
                        <a class="text-dark text-decoration-none" href="{{ route('tweets.index') }}">ホーム</a>
                    </div>
                    <div class="nav-item me-3 d-flex align-items-center">
                        <a class="text-dark text-decoration-none" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            ログアウト
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                    <div class=" nav-item me-3 d-flex align-items-center">
                        <a href="{{ url('users') }}" class="text-dark text-decoration-none">ユーザ一覧</a>

                    </div>
                    <div class="me-5 justify-content-center">
                        <form action=" {{ route('tweets.search') }}" method="post">
                            {{ csrf_field() }}
                            <input id="inputPassword5" class="form-control" aria-describedby="passwordHelpBlock" name="search" placeholder="キーワード検索" style="position:relative; top:20px;">
                            <span class=" input-group-btn" style="position:relative; top:-18px;right:-186px">
                                <button type="submit" class="btn btn-outline-primary">
                                    検索する
                                </button>
                            </span>
                        </form>
                    </div>
                    @endguest
                </ul>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>

</html>