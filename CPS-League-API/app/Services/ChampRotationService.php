<?php

namespace App\Services;

use App\Models\Champion;
use App\Models\ChampionRotation;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Http;
use function Termwind\renderUsing;

class ChampRotationService extends GeneralService
{

    public function connectChampRotationNewPlayers()
    {
        $url = "https://{$this->region}.api.riotgames.com/lol/platform/v3/champion-rotations";

        $response = Http::withoutVerifying()->get($url, [
            'api_key' => config('services.riot.key')
        ]);

        return $response->json() ? $response->json() : null;

    }
    public function storeChampsForNewPlayers(): ?ChampionRotation
    {
        $rotationData = $this->connectChampRotationNewPlayers();
        if (!$rotationData || !isset($rotationData['freeChampionIdsForNewPlayers'])) {
            return null;
        }
        return ChampionRotation::create([
                'freeChampionIds' => $rotationData['freeChampionIds'],
                'freeChampionIdsForNewPlayers' => $rotationData['freeChampionIdsForNewPlayers'],
                'maxNewPlayerLevel' => $rotationData['maxNewPlayerLevel'] ?? null,
            ]);
    }

    public function getCurrentFreeChampions()
    {
        $rotation = ChampionRotation::latest()->first();

        if (!$rotation) {
            return collect();
        }
        $freeChampionIds = $rotation->freeChampionIds ?? [];

        return Champion::whereIn('key',collect($freeChampionIds)->map(fn($id) => (string) $id))->get();

    }


}
