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
            <span class="ranked-value">{{ $rankedMap['solo'] ?? 'Unranked' }}</span>
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
            <span class="ranked-value">{{ $rankedMap['flex'] ?? 'Unranked' }}</span>
        </div>
        <div class="ranked-col points">
            <span class="ranked-points">{{ $flexRank ? 'LP: ' . $flexRank->leaguePoints : 'LP: ??' }}</span>
        </div>
    </div>
</div>

