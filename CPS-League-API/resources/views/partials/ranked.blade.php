<link rel="stylesheet" href="{{ asset('/css/ranked.css') }}">

<div class="ranked-section">
    <div class="ranked-mode">
        <span class="ranked-label">Ranked Solo:</span>
        <span class="ranked-value">{{ $rankedMap['solo'] ?? 'Unranked' }}</span>
    </div>
    <div class="ranked-mode">
        <span class="ranked-label">Ranked Flex:</span>
        <span class="ranked-value">{{ $rankedMap['flex'] ?? 'Unranked' }}</span>
    </div>
</div>
