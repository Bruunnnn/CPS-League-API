<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeneralService
{
    public string $riotApi;
    public string $region = 'euw1';

    public function returnResponse($url)
    {
        return Http::withHeaders([
            'X-Riot-Token' => $this->riotApi,
        ])->withoutVerifying()->get($url);
    }

    public function __construct()
    {
        $this->riotApi = config('services.riot.key');
    }
}
