<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> SuperMegaOvernice-Softtek-frontpage </title>
    <link rel="stylesheet" href="{{asset('/css/frontpage.css')}}">
    <link rel="stylesheet" href="{{asset('/css/peter.css')}}">
</head>
<body>
<div class="free-champions">

    <div class="header-title">
        <h2 class="title">Free Rotation Champions</h2>
    </div>

    @foreach ($freeChampions as $champion)

    <div class="champion-box">
        <img class="image-Champion" src={{ $champion->image_url}} alt={{$champion->name}} width="64">
        <p class="paragraph-Name">{{ $champion->name }}</p>
        <p class="paragraph-Title">{{ $champion->title }}</p>
        <p class="paragraph-Blurb">{{$champion->blurb}}</p>
    </div>
    @endforeach
    </div>
</body>
</html>
