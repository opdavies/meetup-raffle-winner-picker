<?php

declare(strict_types=1);

namespace App\Tests;

use App\EventRepository;

final class FakeEventRepository implements EventRepository
{
    public function getConfirmedAttendees(): array {
        return [];
    }
}
