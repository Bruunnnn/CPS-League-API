<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> SuperMegaOvernice-Softtek-frontpage </title>
    <link rel=preconnect href="https://ddragon.leagueoflegends.com/cdn/14.8.1/data/en_US/champion.json">
    <link rel="stylesheet" href="{{asset('/css/frontpage.css')}}">
    <link rel="stylesheet" href="{{asset('/css/jakob.css')}}">
    <script defer src="{{ asset('js/jakob.js') }}"></script>
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
                <div class="mastery-card">
                    <div class="champion-name">Yasuo</div>

                    <div class="mastery-main">
                        <img class="champion-icon" src="https://via.placeholder.com/64" alt="Champion Icon">

                        <div class="mastery-info">
                            <div class="champion-level">Level 13</div>
                            <div class="champion-points">157,411 Points</div>
                            <div class="points-progress">48,811 since last level</div>
                            <div class="points-progress">37,811 until next level</div>
                            <div class="last-played">Last played: 24 days ago</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

</body>
</html>
