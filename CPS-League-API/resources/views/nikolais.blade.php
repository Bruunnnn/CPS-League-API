<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> SuperMegaOvernice-Softtek-frontpage </title>
    <link rel="stylesheet" href="{{asset('/css/frontpage.css')}}">
    <link rel="stylesheet" href="{{asset('/css/nikolai.css')}}">
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
            </div>
        </div>
    </div>
</div>

</body>
</html>
