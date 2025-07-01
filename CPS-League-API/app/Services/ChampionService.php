<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;
use App\Models\Champion;

class ChampionService
{
    public function storeAllChampions()
    {
        $response = Http::withoutVerifying()->get("https://ddragon.leagueoflegends.com/cdn/15.13.1/data/en_US/champion.json");
        $championData = $response->json()['data'] ?? [];

        foreach ($championData as $champion){
            Champion::updateOrCreate(
                ['key' => $champion['key']],
                [
                    'name' => $champion['name'],
                    'title' => $champion['title'],
                    'blurb' => $champion['blurb'],
                    'image_url' => "https://ddragon.leagueoflegends.com/cdn/15.13.1/img/champion/{$champion['id']}.png",
                ]
            );
        }
    }
}
