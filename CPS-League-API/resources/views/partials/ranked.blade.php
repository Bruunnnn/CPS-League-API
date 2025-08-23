<link rel="stylesheet" href="{{ asset('/css/ranked.css') }}">
@php
    $soloRank = $rankedData->firstWhere('queueType', 'RANKED_SOLO_5x5');
    $flexRank = $rankedData->firstWhere('queueType', 'RANKED_FLEX_SR');
@endphp

<div class="ranked-section">
    <div class="ranked-mode-block">
        <div class="ranked-col label">
            <span class="ranked-label">Ranked Solo:</span>
        </div>
        <div class="ranked-col value">
            @php
            $soloRankName = $rankedMap['solo'] ?? null;
            // Extract only the first word (the tier)
            $soloTier = $soloRankName ? explode(' ', $soloRankName)[0] : null;
            $soloImage = $soloTier ? $soloTier . '.png' : 'Unranked.png';
            @endphp

            @if($soloRankName)
            <img src="{{ asset('img/' . $soloImage) }}"
                 alt="{{ $soloTier }}"
                 class="ranked-icon"
                 style="width: 125px; height: 125px; vertical-align: middle; margin-right: 6px;">
            <span class="ranked-value">{{ $soloRankName }}</span>
            @else
            <img src="{{ asset('img/Unranked.png') }}"
                 alt="Unranked"
                 class="ranked-icon"
                 style="width: 125px; height: 125px; vertical-align: middle; margin-right: 6px;">
            <span class="ranked-value">Unranked</span>
            @endif
        </div>
        <div class="ranked-col points">
            <span class="ranked-points">{{ $soloRank ? 'LP: ' . $soloRank->leaguePoints : 'LP: ??' }}</span>
        </div>
    </div>

    <div class="ranked-mode-block">
        <div class="ranked-col label">
            <span class="ranked-label">Ranked Flex:</span>
        </div>
        <div class="ranked-col value">
            @php
            $flexRankName = $rankedMap['flex'] ?? null;
            $flexTier = $flexRankName ? explode(' ', $flexRankName)[0] : null;
            $flexImage = $flexTier ? $flexTier . '.png' : 'Unranked.png';
            @endphp

            @if($flexRankName)
            <img src="{{ asset('img/' . $flexImage) }}"
                 alt="{{ $flexTier }}"
                 class="ranked-icon"
                 style="width: 125px; height: 125px; vertical-align: middle; margin-right: 6px;">
            <span class="ranked-value">{{ $flexRankName }}</span>
            @else
            <img src="{{ asset('img/Unranked.png') }}"
                 alt="Unranked"
                 class="ranked-icon"
                 style="width: 125px; height: 125px; vertical-align: middle; margin-right: 6px;">
            <span class="ranked-value">Unranked</span>
            @endif
        </div>
        <div class="ranked-col points">
            <span class="ranked-points">{{ $flexRank ? 'LP: ' . $flexRank->leaguePoints : 'LP: ??' }}</span>
        </div>
    </div>
</div>

