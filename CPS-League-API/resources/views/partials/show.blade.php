
<h1>{{ $summoner['name'] }}'s Stats</h1>
<p>Level: {{ $summoner['summonerLevel'] }}</p>

<h2>Recent Matches</h2>
<ul>
    @foreach ($matches as $match)
        <li>
            Match ID: {{ $match['metadata']['matchId'] }} <br>
            KDA:
            @php
                $me = collect($match['info']['participants'])->firstWhere('puuid', $summoner['puuid']);
            @endphp
            {{ $me['kills'] }}/{{ $me['deaths'] }}/{{ $me['assists'] }}
        </li>
    @endforeach
</ul>
