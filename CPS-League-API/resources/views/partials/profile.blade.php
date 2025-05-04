<link rel="stylesheet" href="{{ asset('/css/profile.css') }}">

<div class="player-card">
    <div class="player-element">
        <div class="profile-wrapper">
            <div class="level-label">{{'Lv.' . $summoner->summoner_level }}</div>
            <img class="profile-icon" src='https://ddragon.leagueoflegends.com/cdn/14.9.1/img/profileicon/{{ $summoner->profile_icon_id }}.png' >
        </div>
        <div class="player-details">
            <div class="name-tag-wrapper">
                <div class="player-name">{{ $summoner->game_name }}</div>
                <div class="player-tag-line">{{ '#' . $summoner->tag_line }}</div>
            </div>
            <button class="update-button">Update</button>
        </div>


    </div>

    @include('partials.feature')
</div>





