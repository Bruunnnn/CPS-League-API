<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> SuperMegaOvernice-Softtek-frontpage </title>
    <link rel="stylesheet" href="{{asset('/css/frontpage.css')}}">
    <link rel="stylesheet" href="{{asset('/css/peter.css')}}">
</head>
<body>
<h2>Free Rotation Champions</h2>

<div class="free-champions">

    @foreach ($freeChampions as $champion)

    <div class="champion-box">
        <img src={{ $champion->image_url}} alt={{$champion->name}} width="64">
        <p class="paragraph-Name">{{ $champion->name }}</p>
        <p class="paragraph-Blurb">{{$champion->blurb}}</p>
    </div>
    @endforeach


</div>

<div class="right-column">
    <div id="peters" class="peter">
    </div>
</div>




</body>
</html>
