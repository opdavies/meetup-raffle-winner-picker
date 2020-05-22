<?php

declare(strict_types=1);

namespace App\ValueObject;

final class Winner
{

    private string $name;

    private string $photo;

    public static function createFromArray(array $data): self
    {
        return new static($data);
    }

    public function getName(): string
    {
        return $this->name;
    }

    protected function __construct(array $data)
    {
        [
            'name' => $name,
            'photo' => $photo,
        ] = $data;

        $this->name = $name;
        $this->photo = $photo['photo_link'];
    }

    public function getPhoto(): ?string
    {
        return $this->photo ?? null;
    }
}
