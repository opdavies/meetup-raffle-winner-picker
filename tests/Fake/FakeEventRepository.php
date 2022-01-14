<?php

declare(strict_types=1);

namespace App\Tests\Fake;

use App\EventRepository;
use Tightenco\Collect\Support\Collection;

final class FakeEventRepository implements EventRepository
{
    private static array $rsvps = [];

    public function __construct()
    {
        self::$rsvps = [
            (object) ['name' => 'Oliver Davies.', 'is_attending' => true, 'is_host' => true],
            (object) ['name' => 'matthew s.', 'is_attending' => true, 'is_host' => false],
            (object) ['name' => 'Michael P.', 'is_attending' => true, 'is_host' => false],
            (object) ['name' => 'Kathryn "Kat" R.', 'is_attending' => true, 'is_host' => false],
            (object) ['name' => 'Did not attend', 'is_attending' => false, 'is_host' => false],
        ];
    }

    public function getConfirmedAttendees(): Collection
    {
        return Collection::make(self::$rsvps)
            ->filter(fn (\stdClass $attendee): bool => $attendee->is_attending)
            ->filter(fn (\stdClass $attendee): bool => !$attendee->is_host)
            ;
    }
}
