<link rel="stylesheet" href={{asset('/css/header.css')}}>

<header class="header">
    <a href="/" class="site-name">League of Dudes</a>
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

