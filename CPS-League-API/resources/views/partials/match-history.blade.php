<link rel="stylesheet" href={{asset('/css/match-history.css')}}>

    <div class="content-wrapper">
        <div class="left-column">
            @include('partials.ranked')
            @include('partials.winloss-rate')
        </div>

        <div class="right-column">
            <div class="match-history">
            </div>
        </div>
    </div>
