<?php

declare(strict_types=1);

namespace App\Tests;

use App\EventRepository;
use Tightenco\Collect\Support\Collection;

final class FakeEventRepository implements EventRepository
{
    private static array $rsvps = [
        ['name' => 'Oliver Davies.', 'is_attending' => true, 'is_host' => true],
        ['name' => 'matthew s.', 'is_attending' => true, 'is_host' => false],
        ['name' => 'Michael P.', 'is_attending' => true, 'is_host' => false],
        ['name' => 'Kathryn "Kat" R.', 'is_attending' => true, 'is_host' => false],
        ['name' => 'Did not attend', 'is_attending' => false, 'is_host' => false],
    ];

    public function getConfirmedAttendees(): Collection
    {
        return Collection::make(self::$rsvps)
            ->filter(fn (array $attendee): bool => $attendee['is_attending'])
            ->filter(fn (array $attendee): bool => !$attendee['is_host'])
            ;
    }
}
