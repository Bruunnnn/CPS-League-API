<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> SuperMegaOvernice-Softtek-frontpage </title>
    <link rel="stylesheet" href="{{asset('/css/frontpage.css')}}">
    <link rel="stylesheet" href="{{asset('/css/nikolai.css')}}">
</head>
<body>
    <div class="right-column">
        <div class = "recently-played-header">
            <div class="header-icon"></div>
            <div class="header-name">Name:</div>
            <div class="header-games">Games played with:</div>
            <div class="header-win/loss">Win/Loss:</div>
            <div class="header-winrate">Win rate:</div>
        </div>
        <div id="nikolais" class="nikolai">
            @foreach($recentlyPlayedWith as $key => $stats)
            <div class = "recently-played-list">
                <img class="player-icon" src="https://ddragon.leagueoflegends.com/cdn/15.13.1/img/profileicon/{{ $stats['profileIcon'] }}.png" alt="Icon">
                <div class="player-name">{{ $stats['name'] }}
                    <div class="player-tagline">#{{ $stats['tagline'] }}</div>
                </div>
                <div class="player-games">{{ $stats['count'] }}</div>
                <div class="player-win/loss">{{ $stats['wins'] }}-{{ $stats['losses'] }}</div>
                <div class="player-winrate">{{ round(($stats['wins'] / $stats['count']) * 100) }}%</div>
            </div>
            @endforeach
        </div>
    </div>
</body>
</html>
