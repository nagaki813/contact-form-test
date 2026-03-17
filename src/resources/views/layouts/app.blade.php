<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FashinablyLate</title>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
    @unless(View::hasSection('hideHeader'))
        <header class="header">
            <div class="header__inner">
                <h1 class="logo">
                    <a href="/">FashinablyLate</a>
                </h1>

                <nav>
                    @auth
                        <form action="/logout" method="POST">
                            @csrf
                            <button class="header-nav__button" type="submit">logout</button>
                        </form>
                    @else
                        @if(request()->is('login'))
                            <a class="header-nav__link" href="/register">register</a>
                        @elseif(request()->is('register'))
                            <a class="header-nav__link" href="/login">login</a>
                        @endif
                    @endauth
                </nav>
            </div>
        </header>
    @endunless

    <main class="main">
        @yield('content')
    </main>

</body>

</html>