<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> SuperMegaOvernice-Softtek-Project </title>
    <script src="../js/search" ></script>
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
            <a class="feature-button">Match-History</a>
            <a class="feature-button">Nikolai Feature</a>
            <a class="feature-button">Jakob Feature</a>
            <a class="feature-button">Peter Feature</a>
        </div>
    </div>
    <div class="content-wrapper">
        <div class="left-column">
            @include('partials.ranked')
            @include('partials.winloss-rate')
        </div>

        <div class="right-column">
            @include('partials.match-history')
            @include('partials.peters')
            @include('partials.nikolais')
            @include('partials.jakobs')
        </div>
    </div>
</div>

</body>
</html>





