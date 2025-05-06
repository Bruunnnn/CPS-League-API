<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\Mastery;

class jakobsController extends Controller
{
    public function index()
    {
        $puuid = '6mhRxZFxNMCIA3aACd1lopxXFcN_OWebSyvHKsnHNsIlu_rAT9hn17XjCTzQoo01J9-MUGQ9iuDWDw'; // example
        $masteries = Mastery::where('puuid', $puuid)
            ->orderByDesc('championPoints')
            ->get();


        // Fetch champion list from ddragon
        $response = Http::withoutVerifying()->get('https://ddragon.leagueoflegends.com/cdn/14.8.1/data/en_US/champion.json');
        $championData = $response->json()['data'];

        $championMap = [];
        foreach ($championData as $champion) {
            $championMap[(int)$champion['key']] = [
                'name' => $champion['id'],
                'image' => "https://ddragon.leagueoflegends.com/cdn/14.8.1/img/champion/{$champion['id']}.png"
            ];
        }

        // Map mastery data with champion info
        $masteryCards = $masteries->map(function ($mastery) use ($championMap) {
            $champion = $championMap[$mastery->championId];
            return [
                'championName' => $champion['name'],
                'championImage' => $champion['image'],
                'championLevel' => $mastery->championLevel,
                'championPoints' => $mastery->championPoints,
                'championPointsSinceLastLevel' => $mastery->championPointsSinceLastLevel,
                'championPointsUntilNextLevel' => $mastery->championPointsUntilNextLevel,
                'lastPlayTime' => $mastery->lastPlayTime,
            ];
        });

        return view('frontpage', [
            'masteryCards' => $masteryCards
        ]);
    }
}
