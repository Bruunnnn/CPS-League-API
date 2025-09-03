<link rel="stylesheet" href={{asset('/css/header.css')}}>

<header class="header">
    <div class="site-left">
        <a href="/" class="site-name">League of Dudes</a>
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
        <a href="{{ route('login-page') }}" class="login-redirect">Login</a>
    </div>
</header>
<script src="{{ asset('js/search.js') }}"></script>

