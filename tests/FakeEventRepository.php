<?php

declare(strict_types=1);

namespace App\Tests;

use App\EventRepository;
use App\RsvpResponse;
use Tightenco\Collect\Support\Collection;

final class FakeEventRepository implements EventRepository
{
    private static array $rsvps = [
        ['name' => 'Oliver Davies.', 'response' => RsvpResponse::RESPONSE_YES, 'is_host' => true],
        ['name' => 'matthew s.', 'response' => RsvpResponse::RESPONSE_YES, 'is_host' => false],
        ['name' => 'Michael P.', 'response' => RsvpResponse::RESPONSE_YES, 'is_host' => false],
        ['name' => 'Kathryn "Kat" R.', 'response' => RsvpResponse::RESPONSE_YES, 'is_host' => false],
        ['name' => 'Did not attend', 'response' => RsvpResponse::RESPONSE_NO, 'is_host' => false],
    ];

    public function getConfirmedAttendees(): Collection
    {
        return Collection::make(self::$rsvps)
            ->filter(fn (array $attendee): bool => $attendee['response']
                == RsvpResponse::RESPONSE_YES)
            ->filter(fn (array $attendee): bool => !$attendee['is_host'])
            ;
    }
}
