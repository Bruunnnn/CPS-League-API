<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> SuperMegaOvernice-Softtek-Project </title>
    <script src="../js/search" ></script>
    <script src="{{ asset('js/toggleFeatureButtons.js') }}"></script>
    <link rel="stylesheet" href="{{asset('/css/frontpage.css')}}">
</head>

<body>
<header>
    @include('partials.header')
</header>

<div class="info-wrapper">
    <div class="Profile-wrapper">
        @include('partials.profile')
        <div class="feature-buttons">
            <a class="feature-button" data-target="match-history-feature">Match-History</a>
            <a class="feature-button" data-target="nikolais-feature">Nikolai Feature</a>
            <a class="feature-button" data-target="jakobs-feature">Jakob Feature</a>
            <a class="feature-button" data-target="peters-feature">Peter Feature</a>
            <a class="feature-button" data-target="graph"> Graph </a>
        </div>
    </div>
    <div class="content-wrapper">
        <div class="left-column">
            @include('partials.ranked')
            @include('partials.winloss-rate')
        </div>

        <div class="right-column">
            <div id="match-history-feature" class="feature-section">
                @include('partials.match-history')
            </div>
            <div id="peters-feature" class="feature-section">
                @include('partials.peters')
            </div>
            <div id="nikolais-feature" class="feature-section">
                @include('partials.nikolais')
            </div>
            <div id="graph" class="feature-section">
                @include('partials.graph')
            </div>
            <div id="jakobs-feature" class="feature-section">
                @include('partials.jakobs')
            </div>
        </div>
    </div>
</div>
</body>
</html>





