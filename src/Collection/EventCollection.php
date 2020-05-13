<?php

declare(strict_types=1);

namespace App\Collection;

use Tightenco\Collect\Support\Collection;

final class EventCollection extends Collection
{
    public function excludeEventHosts(): self
    {
        return (new self($this->items))->filter(function (array $rsvp): bool {
            return $rsvp['member']['event_context']['host'];
        });
    }
}
