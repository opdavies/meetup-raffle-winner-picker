<?php

declare(strict_types=1);

namespace App\Collection;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

final class RsvpCollection extends Collection
{
    private const RESPONSE_ATTENDING = 'yes';

    public function excludeEventHosts(): self
    {
        return (new self($this->items))->filter(
            function (array $rsvp): bool {
                return !$rsvp['member']['event_context']['host'];
            }
        );
    }

    public function onlyAttending(): self
    {
        return (new self($this->items))->filter(
            function (array $rsvp): bool {
                return $rsvp['response'] == self::RESPONSE_ATTENDING;
            }
        );
    }

    public function getNames(): self
    {
        return (new self($this->items))->pluck('member.name')->sort();
    }
}
