<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> SuperMegaOvernice-Softtek-frontpage </title>
    <link rel="stylesheet" href="{{asset('/css/frontpage.css')}}">
    <link rel="stylesheet" href="{{asset('/css/nikolai.css')}}">
</head>
<body>
    <div class="right-column">
        <div id="nikolais" class="nikolai">
            <ul class="recently-played-list">
                @foreach($recentlyPlayedWith as $name => $stats)
                <li class="recent-player">
                    <img class="player-icon" src="{{asset('/img/playerIcon.webp')}}">
                    {{ $name }}<br>
                    Games: {{ $stats['count'] }}<br>
                    W-L: {{ $stats['wins'] }}-{{ $stats['losses'] }}<br>
                    Winrate: {{ round(($stats['wins'] / $stats['count']) * 100) }}%
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</body>
</html>
