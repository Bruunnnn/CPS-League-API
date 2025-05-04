<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> SuperMegaOvernice-Softtek-frontpage </title>
    <link rel=preconnect href="https://ddragon.leagueoflegends.com/cdn/14.8.1/data/en_US/champion.json">
    <link rel="stylesheet" href="{{asset('/css/frontpage.css')}}">
    <link rel="stylesheet" href="{{asset('/css/jakob.css')}}">
</head>
<body>
<header>
    @include('partials.header')
</header>

<div class="info-wrapper">
    <div>
        @include('partials.profile')
    </div>
    <div class="content-wrapper">
        <div class="left-column">
            @include('partials.ranked')
            @include('partials.winloss-rate')
        </div>

        <div class="right-column">
            <div class="personal">
                <div class="mastery-header">
                    <div class="header-name">Name</div>
                    <div class="header-icon">Icon</div>
                    <div class="header-level">Level</div>
                    <div class="header-points">Points</div>
                    <div class="header-since">Since Level Up</div>
                    <div class="header-to">To Level Up</div>
                    <div class="header-last">Last Played</div>
                </div>
                @foreach ($masteryCards as $card)
                <div class="mastery-card">
                    <div class="champion-name">{{ $card['championName'] }}</div>

                    <div class="mastery-main">
                        <img class="champion-icon" src="{{ $card['championImage'] }}" alt="Champion Icon">

                        <div class="mastery-info">
                            <div class="champion-level">{{ $card['championLevel'] }}</div>
                            <div class="champion-points">{{ number_format($card['championPoints']) }}</div>
                            <div class="points-progress">{{ number_format($card['championPointsSinceLastLevel']) }}</div>
                            <div class="points-progress">{{ number_format($card['championPointsUntilNextLevel']) }}</div>
                            <div class="last-played">{{ \Carbon\Carbon::createFromTimestampMs($card['lastPlayTime'])->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

</body>
</html>
