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
    @include('partials.match-history')
</div>
</html>
