<?php

namespace App\ValueObject;

use App\Collection\RsvpCollection;
use Tightenco\Collect\Support\Collection;

class Event
{
    private string $name;

    private string $link;

    public static function createFromArray(array $data)
    {
        return new static($data);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLink(): string
    {
        return rtrim($this->link, '/');
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

    public function getRsvps(): Collection
    {
        return new Collection();
    }
}
