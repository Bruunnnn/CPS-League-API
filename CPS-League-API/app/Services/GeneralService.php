<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeneralService
{
    public string $riotApi;
    public string $region = 'euw1';

    public function __construct()
    {
        $this->riotApi = config('services.riot.key');
    }

    public function returnResponse(string $url)
    {
        return Http::withHeaders([
            'X-Riot-Token' => $this->riotApi,
        ])->withoutVerifying()->get($url);
    }

    public function getLatestPatch(): ?string
    {
        $response = Http::withoutVerifying()->get('https://ddragon.leagueoflegends.com/api/versions.json');

        if ($response->successful() && isset($response[0])) {
            return $response[0];
        }

        return null;
    }
}
