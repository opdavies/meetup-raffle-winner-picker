<?php

declare(strict_types=1);

namespace App\Tests;

use App\EventRepository;
use Tightenco\Collect\Support\Collection;

final class FakeEventRepository implements EventRepository
{
    private static array $rsvps = [
        ['name' => 'matthew s.', 'response' => 'yes'],
        ['name' => 'Michael P.', 'response' => 'yes'],
        ['name' => 'Kathryn "Kat" R.', 'response' => 'yes'],
        ['name' => 'Did not attend', 'response' => 'no'],
    ];

    public function getConfirmedAttendees(): Collection {
        return Collection::make(self::$rsvps)
            ->filter(fn (array $attendee): bool => $attendee['response'] == 'yes')
            ;
    }
}
