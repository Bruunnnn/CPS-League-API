<link rel="stylesheet" href="{{ asset('/css/header.css') }}">

<header class="header">
    <div class="site-left">
        <a href="/" class="site-name">League of Dudes</a>
        @auth
        <a href="/leaderboard" class="leaderboard-route">LeaderBoard</a>
        @endauth
    </div>

    <div class="right-side">
        <form id="summonerSearchForm">
            <div class="search-bar">
                <input
                    id="summonerInput"
                    class="search-bar"
                    type="text"
                    placeholder="Search Summoner">
                <button id="searchButton" type="submit"><span>&#128269;</span></button>
            </div>
        </form>

        @guest
        {{-- Not logged in: show Login --}}
        <a href="{{ route('login-page') }}" class="login-redirect">Login</a>
        @endguest

        @auth
        {{-- Logged in: show Logout button --}}
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-button">Logout</button>
        </form>
        @endauth
    </div>
</header>

<script src="{{ asset('js/search.js') }}"></script>
