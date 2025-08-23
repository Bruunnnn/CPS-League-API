<link rel="stylesheet" href={{asset('/css/header.css')}}>

<header class="header">
    <div class="site-left">
        <a href="/" class="site-name">League of Dudes</a>
        <span class="current-patch">Patch {{ $latestPatch }}</span>
    </div>
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

</header>
<script src="{{ asset('js/search.js') }}"></script>

