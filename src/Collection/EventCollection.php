<?php

declare(strict_types=1);

namespace App\Collection;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

final class EventCollection extends Collection
{
    public function excludeEventHosts(): self
    {
        return (new self($this->items))->filter(function (array $rsvp): bool {
            return !Arr::get($rsvp, 'member.event_context.host');
        });
    }
}
