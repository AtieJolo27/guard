<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GPIOService
{
    public static function move(int $pin, int $angle): void
    {
        $angle = max(0, min(180, $angle));

        Http::timeout(5)->get(env('GPIO_URL') . '/move', [
            'pin' => $pin,
            'angle' => $angle,
        ]);
    }
}