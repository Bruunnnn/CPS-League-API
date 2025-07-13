<link rel="stylesheet" href="{{ asset('/css/profile.css') }}">

<div class="player-card">
    <div class="player-element">
        <div class="profile-wrapper">
            <div class="level-label">{{'Lv.' . $summoner->summonerLevel }}</div>
            <img class="profile-icon" src='https://ddragon.leagueoflegends.com/cdn/15.13.1/img/profileicon/{{ $summoner->profileIconId}}.png' >
        </div>
        <div class="player-details">
            <div class="name-tag-wrapper">
                <div class="player-name">{{ $summoner->gameName }}</div>
                <div class="player-tag-line">{{ '#' . $summoner->tagLine }}</div>
            </div>
            <button class="update-button">Update</button>
        </div>
    </div>
</div>





