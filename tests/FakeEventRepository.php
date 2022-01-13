<?php

declare(strict_types=1);

namespace App\Tests;

use App\EventRepository;
use App\RsvpResponse;
use Tightenco\Collect\Support\Collection;

final class FakeEventRepository implements EventRepository
{
    private static array $rsvps = [
        ['name' => 'Oliver Davies.', 'response' => RsvpResponse::RESPONSE_YES, 'member' => ['event_context' => ['host' => true]]],
        ['name' => 'matthew s.', 'response' => RsvpResponse::RESPONSE_YES, 'member' => ['event_context' => ['host' => false]]],
        ['name' => 'Michael P.', 'response' => RsvpResponse::RESPONSE_YES, 'member' => ['event_context' => ['host' => false]]],
        ['name' => 'Kathryn "Kat" R.', 'response' => RsvpResponse::RESPONSE_YES, 'member' => ['event_context' => ['host' => false]]],
        ['name' => 'Did not attend', 'response' => RsvpResponse::RESPONSE_NO, 'member' => ['event_context' => ['host' => false]]],
    ];

    public function getConfirmedAttendees(): Collection {
        return Collection::make(self::$rsvps)
            ->filter(fn (array $attendee): bool => $attendee['response']
                == RsvpResponse::RESPONSE_YES)
            ;
    }
}
