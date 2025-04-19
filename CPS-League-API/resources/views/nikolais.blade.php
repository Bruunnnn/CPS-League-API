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
<div>
    @include('partials.profile')
    @include('partials.ranked')
</div>
</body>
</html>
