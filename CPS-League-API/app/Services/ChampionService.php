<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Champion;

class ChampionService
{
    protected GeneralService $generalService;

    public function __construct(GeneralService $generalService)
    {
        $this->generalService = $generalService;
    }

    public function storeAllChampions()
    {
        // Fetch the latest patch dynamically
        $latestPatch = $this->generalService->getLatestPatch();

        // Get champion data from Data Dragon
        $response = Http::withoutVerifying()
            ->get("https://ddragon.leagueoflegends.com/cdn/{$latestPatch}/data/en_US/champion.json");

        $championData = $response->json()['data'] ?? [];

        // Store or update champion info
        foreach ($championData as $champion) {
            Champion::updateOrCreate(
                ['key' => $champion['key']],
                [
                    'name' => $champion['name'],
                    'title' => $champion['title'],
                    'blurb' => $champion['blurb'],
                    'image_url' => "https://ddragon.leagueoflegends.com/cdn/{$latestPatch}/img/champion/{$champion['id']}.png",
                ]
            );
        }
    }
}
