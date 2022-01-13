<?php

namespace App;

use Tightenco\Collect\Support\Collection;

interface EventRepository
{
    public function getConfirmedAttendees(): Collection;
}
