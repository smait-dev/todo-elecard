<?php

namespace App\DTO;

use App\Models\User;

class UserDto
{
    public function __construct(
        public ?string $username,
        public ?string $password,
    )
    {
    }

    public static function fromModel(User $user): self
    {
        return new self(
            username: $user->username,
            password: $user->password,
        );
    }

    /**
     * @param array{username: string, password: string} $user
     * @return self
     */
    public static function fromArray(array $user): self
    {
        return new self(
            username: $user['username'],
            password: $user['password'],
        );
    }

    public function toArray(): array
    {
        return [
            'username' => $this->username,
            'password' => $this->password,
        ];
    }
}
