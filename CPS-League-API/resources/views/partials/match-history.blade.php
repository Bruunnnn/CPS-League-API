<link rel="stylesheet" href={{asset('/css/match-history.css')}}>

<div id="match-history-feature" class="feature-section"> <link rel="stylesheet" href="{{ asset('/css/match-history.css') }}">
    @foreach($matchHistory as $match)
    <div class="match-card {{ $match->win ? 'match-win' : 'match-loss' }}">
        <div class="match-main">
            <div class="match-info-left">
                <div class="game-type"> {{ $queueMap[$match->queueId] ?? 'Unknown' }}</div>
                <div class="game-age">{{ \Carbon\Carbon::createFromTimestampMs($match->endGameTimestamp)->diffForHumans() }}</div>

                <div class="result-timer">
                    <div class="game-result">{{ $match->win ? 'WIN' : 'LOSS' }}</div>
                    <div class="game-duration">{{ gmdate('i:s', $match->gameDuration) }}</div>
                </div>
            </div>

            <img class="champion-icon"
                 src="{{ $championMap[$match->championId]['image'] }}"
                 alt="{{ $championMap[$match->championId]['name'] }}">

            <div class="kda-section">
                <div class="score">{{ $match->kills }} / {{ $match->deaths }} / {{ $match->assists }}</div>
                <div class="kda">{{ number_format(($match->kills + $match->assists) / max(1, $match->deaths), 2) }} KDA</div>
                <div class="cs">{{ $match->totalMinionsKilled + $match->totalEnemyJungleMinionsKilled }} CS</div>
            </div>

            <div class="items-grid">
                <div class="items-row">
                    @foreach ([$match->item0, $match->item1, $match->item2, $match->item6] as $item)
                    @if ($item)
                    <img class="item-icon" src="https://ddragon.leagueoflegends.com/cdn/15.9.1/img/item/{{ $item }}.png" alt="Item {{ $item }}">
                    @endif
                    @endforeach
                </div>
                <div class="items-row">
                    @foreach ([$match->item3, $match->item4, $match->item5] as $item)
                    @if ($item)
                    <img class="item-icon" src="https://ddragon.leagueoflegends.com/cdn/15.9.1/img/item/{{ $item }}.png" alt="Item {{ $item }}">
                    @endif
                    @endforeach
                </div>
            </div>


            <div class="players-columns">
                {{-- Placeholder for actual team members; you’ll need to fetch and render them --}}
                <div class="team-column">
                    @for ($i = 1; $i <= 5; $i++)
                    <div class="player"><img src='https://ddragon.leagueoflegends.com/cdn/15.9.1/img/profileicon/{{ $summoner->profile_icon_id }}.png' alt="Champ"><span>Player {{ $i }}</span></div>
                    @endfor
                </div>
                <div class="team-column">
                    @for ($i = 6; $i <= 10; $i++)
                    <div class="player"><img src='https://ddragon.leagueoflegends.com/cdn/15.9.1/img/profileicon/{{ $summoner->profile_icon_id }}.png' alt="Champ"><span>Player {{ $i }}</span></div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
