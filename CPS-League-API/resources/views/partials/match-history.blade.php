<div id="match-history-feature" class="feature-section">
    <link rel="stylesheet" href="{{ asset('/css/match-history.css') }}">

    @foreach($matches as $game)
    @php
    $match = $game['match'];
    $players = $game['players'];
    @endphp

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
                <div class="team-column-left">
                    @foreach($players->slice(0, 5) as $player)
                    <div class="player left">
                        <img src="{{ $championMap[$player->championId]['image'] ?? 'fallback.png' }}" alt="Champ">
                        <span>{{ $player->riotIdGameName }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="team-column-right">
                    @foreach($players->slice(5, 5) as $player)
                    <div class="player right">
                        <span>{{ $player->riotIdGameName }}</span>
                        <img src="{{ $championMap[$player->championId]['image'] ?? 'fallback.png' }}" alt="Champ">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
