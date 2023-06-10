<?php

namespace App\Services\Payment\Entities;

use App\Models\User;
use Illuminate\Contracts\Support\Arrayable;

class BuyerEntity implements Arrayable
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->user->getAttribute('name'),
            'surname' => $this->user->getAttribute('surname'),
            'email' => $this->user->getAttribute('email'),
            'document' => $this->user->getAttribute('document'),
            'documentType' => $this->user->getAttribute('document_type'),
            'mobile' => '+57'.$this->user->getAttribute('phone'),
            'address' => [
                'street' => $this->user->getAttribute('address'),
                'city' => $this->user->city->name,
                'state' => $this->user->city->department->name,
                'phone' => $this->user->phone,
            ],
        ];
    }
}
