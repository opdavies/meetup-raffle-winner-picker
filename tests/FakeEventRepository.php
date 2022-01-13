<?php

declare(strict_types=1);

namespace App\Tests;

use App\EventRepository;
use Tightenco\Collect\Support\Collection;

final class FakeEventRepository implements EventRepository
{
    public function getConfirmedAttendees(): Collection {
        $rsvps = Collection::make([
            ['name' => 'matthew s.', 'response' => 'yes'],
            ['name' => 'Michael P.', 'response' => 'yes'],
            ['name' => 'Kathryn "Kat" R.', 'response' => 'yes'],
            ['name' => 'Did not attend', 'response' => 'no'],
        ]);

        return $rsvps
            ->filter(fn (array $attendee): bool => $attendee['response'] == 'yes')
            ;
    }
}
