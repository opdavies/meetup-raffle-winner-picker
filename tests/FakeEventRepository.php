<?php

declare(strict_types=1);

namespace App\Tests;

use App\EventRepository;
use Tightenco\Collect\Support\Collection;

final class FakeEventRepository implements EventRepository
{
    public function getConfirmedAttendees(): Collection {
        return Collection::make([
            ['name' => 'matthew s.'],
            ['name' => 'Michael P.'],
            ['name' => 'Kathryn "Kat" R.'],
        ]);
    }
}
