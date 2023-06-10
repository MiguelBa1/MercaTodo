<?php

namespace App\Services\Payment\Entities;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;

class AuthEntity implements Arrayable
{
    public function toArray(): array
    {
        $nonce = Str::random();
        $seed = date('c');

        return [
            'login' => config('placetopay.login'),
            'tranKey' => base64_encode(
                hash(
                    'sha256',
                    $nonce . $seed . config('placetopay.tranKey'),
                    true
                )
            ),
            'nonce' => base64_encode($nonce),
            'seed' => $seed,
        ];
    }
}
