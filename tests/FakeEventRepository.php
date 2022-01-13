<?php

declare(strict_types=1);

namespace App\Tests;

use App\EventRepository;
use App\RsvpResponse;
use Tightenco\Collect\Support\Collection;

final class FakeEventRepository implements EventRepository
{
    private static array $rsvps = [
        ['name' => 'matthew s.', 'response' => RsvpResponse::RESPONSE_YES],
        ['name' => 'Michael P.', 'response' => RsvpResponse::RESPONSE_YES],
        ['name' => 'Kathryn "Kat" R.', 'response' => RsvpResponse::RESPONSE_YES],
        ['name' => 'Did not attend', 'response' => RsvpResponse::RESPONSE_NO],
    ];

    public function getConfirmedAttendees(): Collection {
        return Collection::make(self::$rsvps)
            ->filter(fn (array $attendee): bool => $attendee['response']
                == RsvpResponse::RESPONSE_YES)
            ;
    }
}
