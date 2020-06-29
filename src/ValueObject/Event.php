<?php

namespace App\ValueObject;

use App\Collection\RsvpCollection;
use Tightenco\Collect\Support\Collection;

class Event
{
    private string $name;

    private string $link;

    public static function createFromArray(array $data): self
    {
        return new static($data);
    }

    public function getLink(): string
    {
        return rtrim($this->link, '/');
    }

    public function getName(): string
    {
        return $this->name;
    }

    protected function __construct(array $data)
    {
        [
            'name' => $name,
            'link' => $link,
        ] = $data;

        $this->name = $name;
        $this->link = $link;
    }
}
